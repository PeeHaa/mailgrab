<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp;

use Amp\Promise;
use PeeHaa\MailGrab\Smtp\Client;
use PeeHaa\MailGrab\Smtp\Command\Factory;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PeeHaa\MailGrab\Smtp\Message;
use PeeHaa\MailGrab\Smtp\Socket\ServerSocket;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    public function testListenReturnsPromise()
    {
        $client = new Client(
            $this->createMock(ServerSocket::class),
            $this->createMock(Factory::class),
            // phpcs:disable
            function() {},
            // phpcs:enable
            $this->createMock(Output::class)
        );

        $this->assertInstanceOf(Promise::class, $client->listen());
    }

    public function testProcessNewMessage()
    {
        $messageMock = $this->createMock(Message::class);

        $client = new Client(
            $this->createMock(ServerSocket::class),
            $this->createMock(Factory::class),
            function($message) use ($messageMock) {
                $this->assertSame($messageMock, $message);
            },
            $this->createMock(Output::class)
        );

        $client->processNewMessage($messageMock);
    }
}
