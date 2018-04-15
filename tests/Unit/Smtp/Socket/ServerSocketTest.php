<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Socket;

use Amp\Socket\ServerSocket as AmpServerSocket;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PeeHaa\MailGrab\Smtp\Socket\ServerSocket;
use PHPUnit\Framework\TestCase;

class ServerSocketTest extends TestCase
{
    public function testWritesToLog()
    {
        $logger = $this->createMock(Output::class);
        $socket = $this->createMock(AmpServerSocket::class);

        $logger
            ->expects($this->once())
            ->method('smtpOut')
            ->with('foo', ['bar'])
        ;

        (new ServerSocket($logger, $socket))->write('foo', ['bar']);
    }

    public function testCallsSocketMethod()
    {
        $logger = $this->createMock(Output::class);
        $socket = $this->createMock(AmpServerSocket::class);

        $socket
            ->expects($this->once())
            ->method('write')
            ->with('foo', ['bar'])
        ;

        (new ServerSocket($logger, $socket))->write('foo', ['bar']);
    }
}
