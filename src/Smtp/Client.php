<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp;

use Amp\Promise;
use PeeHaa\MailGrab\Smtp\Command\Factory as CommandFactory;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PeeHaa\MailGrab\Smtp\Response\ServiceReady;
use PeeHaa\MailGrab\Smtp\Response\SyntaxError;
use PeeHaa\MailGrab\Smtp\Socket\ServerSocket;
use function Amp\call;

class Client
{
    private const BANNER = 'Welcome to MailGrab SMTP server';

    private const LINE_DELIMITER = "\r\n";

    private $socket;

    private $commandFactory;

    private $callback;

    private $logger;

    /** @var Transaction */
    private $transaction;

    public function __construct(
        ServerSocket $socket,
        CommandFactory $commandFactory,
        callable $callback,
        Output $logger
    ) {
        $this->socket         = $socket;
        $this->commandFactory = $commandFactory;
        $this->callback       = $callback;
        $this->logger         = $logger;
    }

    public function listen(): Promise
    {
        return call(function() {
            yield $this->socket->write((string) (new ServiceReady(self::BANNER)));

            $this->transaction = new Transaction($this->logger, $this->socket, $this->commandFactory, [$this, 'processNewMessage']);

            $buffer = '';

            // phpcs:disable SlevomatCodingStandard.ControlStructures.DisallowYodaComparison.DisallowedYodaComparison
            while (null !== $chunk = yield $this->socket->read()) {
                // phpcs:enable
                $this->logger->messageIn($chunk);

                $limit = 512;

                if ($this->transaction->isHandlingBody()) {
                    $limit = 1000;
                }

                $buffer .= $chunk;

                // phpcs:disable SlevomatCodingStandard.ControlStructures.DisallowYodaComparison.DisallowedYodaComparison
                while (false !== $pos = strpos($buffer, self::LINE_DELIMITER)) {
                    // phpcs:enable
                    $line   = substr($buffer, 0, $pos);
                    $buffer = substr($buffer, $pos + strlen(self::LINE_DELIMITER));

                    if (strlen($line) > $limit) {
                        $this->socket->write((string) new SyntaxError('Line length limit exceeded.'));

                        continue;
                    }

                    $this->transaction->processLine($line);
                }
            }
        });
    }

    public function processNewMessage(Message $message)
    {
        ($this->callback)($message);
    }
}
