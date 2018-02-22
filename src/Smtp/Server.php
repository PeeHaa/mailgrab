<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp;

use PeeHaa\MailGrab\Smtp\Command\Factory as CommandFactory;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PeeHaa\MailGrab\Smtp\Socket\ServerSocket;
use function Amp\asyncCall;
use function PeeHaa\MailGrab\listen;

class Server
{
    private const ADDRESS = 'tcp://127.0.0.1:8025';

    private const BANNER = 'Welcome to MailGrab SMTP server';

    private $commandFactory;

    private $callback;

    private $logger;

    public function __construct(CommandFactory $commandFactory, callable $callback, Output $logger)
    {
        $this->commandFactory = $commandFactory;
        $this->callback       = $callback;
        $this->logger         = $logger;
    }

    public function run()
    {
        asyncCall(function () {
            $server = listen($this->logger, self::ADDRESS);

            $this->logger->info('Server started and listening on ' . $server->getAddress());

            while ($socket = yield $server->accept()) {
                $this->handleClient($socket);
            }
        });
    }

    private function handleClient(ServerSocket $socket)
    {
        asyncCall(function () use ($socket) {
            $client = new Client(self::BANNER, $socket, $this->commandFactory, $this->callback, $this->logger);

            $this->logger->info('Accepted new client: ' . $client->getId());

            yield $client->listen();

            $this->logger->info('Disconnected client: ' . $client->getId());
        });
    }
}
