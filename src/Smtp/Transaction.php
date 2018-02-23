<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp;

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
use PeeHaa\MailGrab\Smtp\Header\Buffer;
use PeeHaa\MailGrab\Smtp\Header\Header;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PeeHaa\MailGrab\Smtp\Response\ActionCompleted;
use PeeHaa\MailGrab\Smtp\Response\ClosingTransmission;
use PeeHaa\MailGrab\Smtp\Response\StartInput;
use PeeHaa\MailGrab\Smtp\Response\SyntaxError;
use PeeHaa\MailGrab\Smtp\Socket\ServerSocket;

class Transaction
{
    private $logger;

    private $socket;

    private $commandFactory;

    private $callback;

    private $status;

    /** @var Buffer */
    private $headerBuffer;

    private $message;

    public function __construct(Output $logger, ServerSocket $socket, CommandFactory $commandFactory, callable $callback)
    {
        $this->logger         = $logger;
        $this->socket         = $socket;
        $this->commandFactory = $commandFactory;
        $this->callback       = $callback;

        $this->status = new TransactionStatus(TransactionStatus::SEND_BANNER);

        $this->message = new Message();
    }

    public function isHandlingBody(): bool
    {
        return $this->status->equals(new TransactionStatus(TransactionStatus::BODY));
    }

    public function processLine(string $line): void
    {
        $this->logger->debug('Current client status is: ' . $this->status->getKey());

        $this->logger->smtpIn($line);

        if ($this->status->equals(new TransactionStatus(TransactionStatus::QUIT))) {
            $this->logger->debug('Client status already set to quit. Not processing new lines.');

            return;
        }

        try {
            $command = $this->commandFactory->build($this->status, $line);
        } catch (\Exception $e) {
            // @todo: return list of available commands based on status here

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

        $this->logger->debug('New client status is: ' . $this->status->getKey());
    }

    private function processHelo(Helo $command): void
    {
        $this->status = new TransactionStatus(TransactionStatus::INIT);

        $this->socket->write(
            (string) new ActionCompleted(
                sprintf('hello %s @ %s', $command->getAddress(), $this->socket->getRemoteAddress())
            )
        );
    }

    private function processEhlo(Ehlo $command): void
    {
        $this->status = new TransactionStatus(TransactionStatus::INIT);

        $this->socket->write(
            (string) new ActionCompleted(
                sprintf('hello %s @ %s', $command->getAddress(), $this->socket->getRemoteAddress())
            )
        );
    }

    private function processReset(): void
    {
        $this->status  = new TransactionStatus(TransactionStatus::INIT);
        $this->message = new Message();
    }

    private function processQuit(): void
    {
        $this->status = new TransactionStatus(TransactionStatus::QUIT);

        $this->socket->write((string) new ClosingTransmission('Goodbye.'));

        $this->socket->close();
    }

    private function processMailFrom(MailFrom $command): void
    {
        $this->status = new TransactionStatus(TransactionStatus::FROM);

        $this->message->setFrom($command->getAddress());

        $this->socket->write((string) new ActionCompleted('MAIL OK'));
    }

    private function processRcptTo(RcptTo $command): void
    {
        $this->status = new TransactionStatus(TransactionStatus::TO);

        $this->message->addRecipient($command->getAddress(), $command->getName());

        $this->socket->write((string) new ActionCompleted('Accepted'));
    }

    private function processStartData(): void
    {
        $this->status = new TransactionStatus(TransactionStatus::HEADERS);

        $this->socket->write((string) new StartInput('Enter message, end with CRLF . CRLF'));
    }

    private function processStartHeader(StartHeader $command): void
    {
        $this->addHeaderWhenNeeded();

        $this->headerBuffer = new Buffer($command->getKey());

        $this->headerBuffer->append($command->getValue());

        $this->status = new TransactionStatus(TransactionStatus::UNFOLDING);
    }

    private function unfold(Unfold $command): void
    {
        $this->headerBuffer->append($command->getChunk());
    }

    private function startBody(): void
    {
        $this->addHeaderWhenNeeded();

        $this->status = new TransactionStatus(TransactionStatus::BODY);
    }

    private function addBodyLine(BodyLine $command): void
    {
        $this->message->appendToBody($command->getLine());
    }

    private function endBody(): void
    {
        $this->addHeaderWhenNeeded();

        $this->status = new TransactionStatus(TransactionStatus::PROCESSING);

        $this->socket->write((string) new ActionCompleted('OK'));

        ($this->callback)(clone $this->message);

        $this->processReset();
    }

    private function addHeaderWhenNeeded(): void
    {
        if ($this->status->equals(new TransactionStatus(TransactionStatus::UNFOLDING))) {
            $this->message->addHeader(new Header($this->headerBuffer));
        }
    }
}
