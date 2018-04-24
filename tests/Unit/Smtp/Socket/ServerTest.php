<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Socket;

use Amp\Delayed;
use Amp\Loop;
use Amp\Promise;
use Amp\Socket\ClientSocket;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PeeHaa\MailGrab\Smtp\Socket\ServerSocket;
use PHPUnit\Framework\TestCase;
use function Amp\Socket\connect;
use function PeeHaa\MailGrab\listen;

class ServerTest extends TestCase
{
    public function testAcceptReturnsPromise()
    {
        $logger = $this->createMock(Output::class);

        $server = listen($logger, 'tcp://127.0.0.1:9995');

        $this->assertInstanceOf(Promise::class, $server->accept());
    }

    public function testAcceptReturnsResolvesToServerSocket()
    {
        $this->markTestSkipped('Need to find a way to test this.');

        Loop::run(function () {
            $logger = $this->createMock(Output::class);

            $server = listen($logger, 'tcp://127.0.0.1:9996');

            Loop::delay(2000, function () use ($server) {
                /** @var ClientSocket $socket */
                $socket = yield connect('tcp://127.0.0.1:9996');

                yield new Delayed(5000);

                $socket->close();
                $server->close();
            });

            $this->assertInstanceOf(ServerSocket::class, yield $server->accept());
        });
    }
}
