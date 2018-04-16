<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp;

use PeeHaa\MailGrab\Smtp\Command\Factory as CommandFactory;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PeeHaa\MailGrab\Smtp\Socket\ServerSocket;
use function Amp\asyncCall;
use function PeeHaa\MailGrab\listen;

class Server
{
    private $addresses = [];

    private $commandFactory;

    private $callback;

    private $logger;

    public function __construct(CommandFactory $commandFactory, callable $callback, Output $logger, array $addresses, int $port)
    {
        $this->commandFactory = $commandFactory;
        $this->callback       = $callback;
        $this->logger         = $logger;

        foreach ($addresses as $address) {
            $this->addresses[] = sprintf('tcp://%s:%d', $address, $port);
        }
    }

    public function run()
    {
        foreach ($this->addresses as $address) {
            asyncCall(function () use ($address) {
                $server = listen($this->logger, $address);

                $this->logger->info('Server started and listening on ' . $server->getAddress());

                while ($socket = yield $server->accept()) {
                    $this->handleClient($socket);
                }
            });
        }
    }

    private function handleClient(ServerSocket $socket)
    {
        asyncCall(function () use ($socket) {
            $client = new Client($socket, $this->commandFactory, $this->callback, $this->logger);

            $this->logger->info('Accepted new client');

            yield $client->listen();

            $this->logger->info('Disconnected client');
        });
    }
}
