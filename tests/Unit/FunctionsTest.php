<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit;

use Amp\Promise;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PHPUnit\Framework\TestCase;
use function PeeHaa\MailGrab\listen;

class FunctionsTest extends TestCase
{
    public function testListenDoesNotThrowOnMissingScheme()
    {
        $logger = $this->createMock(Output::class);

        $server = listen($logger, '127.0.0.1:9995');

        $this->assertInstanceOf(Promise::class, $server->accept());

        $server->close();
    }

    public function testListenThrowsOnInvalidScheme()
    {
        $logger = $this->createMock(Output::class);

        $this->expectException(\Throwable::class);

        $server = listen($logger, 'ftp://127.0.0.1:9995');

        $this->assertInstanceOf(Promise::class, $server->accept());

        $server->close();
    }

    public function testListenThrowsWhenNotBeingAbleToConnect()
    {
        $logger = $this->createMock(Output::class);

        $this->expectException(\Throwable::class);

        $server = listen($logger, 'tcp://512.512.512.512:9000');

        $this->assertInstanceOf(Promise::class, $server->accept());

        $server->close();
    }
}
