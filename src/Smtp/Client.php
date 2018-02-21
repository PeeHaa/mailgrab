<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp;

use Amp\Promise;
use Amp\Socket\ServerSocket;
use PeeHaa\MailGrab\Smtp\Command\BodyLine;
use PeeHaa\MailGrab\Smtp\Command\Ehlo;
use PeeHaa\MailGrab\Smtp\Command\EndBody;
use PeeHaa\MailGrab\Smtp\Command\Factory as CommandFactory;
use PeeHaa\MailGrab\Smtp\Command\Helo;
use PeeHaa\MailGrab\Smtp\Command\MailFrom;
use PeeHaa\MailGrab\Smtp\Command\Quit;
use PeeHaa\MailGrab\Smtp\Command\RcptTo;
use PeeHaa\MailGrab\Smtp\Command\Rset;
use PeeHaa\MailGrab\Smtp\Command\StartBody;
use PeeHaa\MailGrab\Smtp\Command\StartData;
use PeeHaa\MailGrab\Smtp\Command\StartHeader;
use PeeHaa\MailGrab\Smtp\Command\Unfold;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PeeHaa\MailGrab\Smtp\Response\ActionCompleted;
use PeeHaa\MailGrab\Smtp\Response\ClosingTransmission;
use PeeHaa\MailGrab\Smtp\Response\ServiceReady;
use PeeHaa\MailGrab\Smtp\Response\StartInput;
use PeeHaa\MailGrab\Smtp\Response\SyntaxError;
use function Amp\call;

// @todo: split this up into a client and a transaction
class Client
{
    private const LINE_DELIMITER = "\r\n";

    private $id;

    private $banner;

    private $socket;

    private $commandFactory;

    private $logger;

    private $status;

    private $message;

    public function __construct(string $banner, ServerSocket $socket, CommandFactory $commandFactory, Output $logger)
    {
        $this->id             = $socket->getRemoteAddress();
        $this->banner         = $banner;
        $this->socket         = $socket;
        $this->commandFactory = $commandFactory;
        $this->logger         = $logger;
        $this->status         = new ClientStatus(ClientStatus::NEW);
        $this->message        = new Message();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function listen(): Promise
    {
        return call(function() {
            yield $this->socket->write((string) (new ServiceReady($this->banner)));

            $this->logger->smtpOut((string) (new ServiceReady($this->banner)));

            $this->status = new ClientStatus(ClientStatus::SEND_BANNER);

            $buffer = '';

            while (null !== $chunk = yield $this->socket->read()) {
                $this->logger->messageIn($chunk);

                if ($this->status->equals(new ClientStatus(ClientStatus::NEW))) {
                    $this->socket->close();

                    return;
                }

                $limit = 512;

                if ($this->status->equals(new ClientStatus(ClientStatus::BODY))) {
                    $limit = 1000;
                }

                $buffer .= $chunk;

                while(false !== $pos = strpos($buffer, self::LINE_DELIMITER)) {
                    $line   = substr($buffer, 0, $pos);
                    $buffer = substr($buffer, $pos + strlen(self::LINE_DELIMITER));

                    if (strlen($line) > $limit) {
                        $this->logger->smtpOut((string) new SyntaxError('Line length limit exceeded.'));

                        $this->socket->write((string) new SyntaxError('Line length limit exceeded.'));

                        continue;
                    }

                    $this->logger->debug('Current client status is: ' . $this->status->getKey());

                    $this->processLine($line);

                    $this->logger->debug('New client status is: ' . $this->status->getKey());
                }
            }
        });
    }

    private function processLine(string $line): void
    {
        $this->logger->smtpIn($line);

        if ($this->status->equals(new ClientStatus(ClientStatus::QUIT))) {
            $this->logger->debug('Client status already set to quit. Not processing new lines.');

            return;
        }

        try {
            $command = $this->commandFactory->build($this->status, $line);
        } catch (\Exception $e) {
            // @todo: return list of available commands based on status here

            $this->logger->smtpOut((string) new SyntaxError($e->getMessage()));

            $this->socket->write((string) new SyntaxError($e->getMessage()));

            return;
        }

        $this->logger->debug('Command to run: ' . get_class($command));

        switch (get_class($command)) {
            case Helo::class:
                /** @var Helo $command */
                $this->processHelo($command);
                return;
            case Ehlo::class:
                /** @var Ehlo $command */
                $this->processEhlo($command);
                return;

            case Rset::class:
                $this->processReset();
                return;

            case Quit::class:
                $this->processQuit();
                return;

            case MailFrom::class:
                /** @var MailFrom $command */
                $this->processMailFrom($command);
                return;

            case RcptTo::class:
                /** @var RcptTo $command */
                $this->processRcptTo($command);
                return;

            case StartData::class:
                $this->processStartData();
                return;

            case StartHeader::class:
                /** @var StartHeader $command */
                $this->processStartHeader($command);
                return;

            case Unfold::class:
                /** @var Unfold $command */
                $this->unfold($command);
                return;

            case StartBody::class:
                $this->startBody();
                return;

            case BodyLine::class:
                /** @var BodyLine $command */
                $this->addBodyLine($command);
                return;

            case EndBody::class:
                $this->endBody();
                return;
        }
    }

    private function processHelo(Helo $command): void
    {
        $this->status = new ClientStatus(ClientStatus::INIT);

        $this->socket->write(
            (string) new ActionCompleted(sprintf('hello %s @ %s', $command->getAddress(), $this->getId()))
        );

        $this->logger->smtpOut(
            (string) new ActionCompleted(sprintf('hello %s @ %s', $command->getAddress(), $this->getId()))
        );
    }

    private function processEhlo(Ehlo $command): void
    {
        $this->status = new ClientStatus(ClientStatus::INIT);

        $this->socket->write(
            (string) new ActionCompleted(sprintf('hello %s @ %s', $command->getAddress(), $this->getId()))
        );

        $this->logger->smtpOut(
            (string) new ActionCompleted(sprintf('hello %s @ %s', $command->getAddress(), $this->getId()))
        );
    }

    private function processReset(): void
    {
        $this->status  = new ClientStatus(ClientStatus::INIT);
        $this->message = new Message();
    }

    private function processQuit(): void
    {
        $this->status = new ClientStatus(ClientStatus::QUIT);

        $this->socket->write((string) new ClosingTransmission('Goodbye.'));

        $this->logger->smtpOut((string) new ClosingTransmission('Goodbye.'));

        $this->socket->close();
    }

    private function processMailFrom(MailFrom $command): void
    {
        $this->status = new ClientStatus(ClientStatus::FROM);

        $this->message->setFrom($command->getAddress());

        $this->socket->write((string) new ActionCompleted('MAIL OK'));

        $this->logger->smtpOut((string) new ActionCompleted('MAIL OK'));
    }

    private function processRcptTo(RcptTo $command): void
    {
        $this->status = new ClientStatus(ClientStatus::TO);

        $this->message->addRecipient($command->getAddress(), $command->getName());

        $this->socket->write((string) new ActionCompleted('Accepted'));

        $this->logger->smtpOut((string) new ActionCompleted('Accepted'));
    }

    private function processStartData(): void
    {
        $this->status = new ClientStatus(ClientStatus::HEADERS);

        $this->socket->write((string) new StartInput('Enter message, end with CRLF . CRLF'));

        $this->logger->smtpOut((string) new StartInput('Enter message, end with CRLF . CRLF'));
    }

    private function processStartHeader(StartHeader $command): void
    {
        if ($this->status->equals(new ClientStatus(ClientStatus::UNFOLDING))) {
            $this->message->finalizeHeader();
        }

        $this->message->createHeaderBuffer($command->getKey(), $command->getValue());

        $this->status = new ClientStatus(ClientStatus::UNFOLDING);
    }

    private function unfold(Unfold $command): void
    {
        $this->message->appendToHeaderBuffer($command->getChunk());
    }

    private function startBody(): void
    {
        if ($this->status->equals(new ClientStatus(ClientStatus::UNFOLDING))) {
            $this->message->finalizeHeader();
        }

        $this->status = new ClientStatus(ClientStatus::BODY);
    }

    private function addBodyLine(BodyLine $command): void
    {
        $this->message->appendToBody($command->getLine());
    }

    private function endBody(): void
    {
        if ($this->status->equals(new ClientStatus(ClientStatus::UNFOLDING))) {
            $this->message->finalizeHeader();
        }

        $this->status = new ClientStatus(ClientStatus::PROCESSING);

        $this->socket->write((string) new ActionCompleted('OK'));

        $this->logger->smtpOut((string) new ActionCompleted('OK'));

        $this->processReset();
    }
}
