<?php declare(strict_types=1);

namespace PeeHaa\MailGrab;

use Amp\Loop;
use Amp\Socket\ServerSocket;
use PeeHaa\MailGrab\Command\Factory as CommandFactory;
use PeeHaa\MailGrab\Log\Output;
use function Amp\asyncCall;
use function Amp\Socket\listen;

class Server
{
    private const ADDRESS = 'tcp://127.0.0.1:8025';

    private const BANNER = 'Welcome to MailGrab SMTP server';

    private $commandFactory;

    private $logger;

    public function __construct(CommandFactory $commandFactory, Output $logger)
    {
        $this->commandFactory = $commandFactory;
        $this->logger         = $logger;
    }

    public function run()
    {
        Loop::run(function() {
            asyncCall(function () {
                $server = listen(self::ADDRESS);

                $this->logger->info('Server started and listening on ' . $server->getAddress());

                while ($socket = yield $server->accept()) {
                    $this->handleClient($socket);
                }
            });
        });
    }

    private function handleClient(ServerSocket $socket)
    {
        asyncCall(function () use ($socket) {
            $client = new Client(self::BANNER, $socket, $this->commandFactory, $this->logger);

            $this->logger->info('Accepted new client: ' . $client->getId());

            yield $client->listen();

            $this->logger->info('Disconnected client: ' . $client->getId());
        });
    }
}
