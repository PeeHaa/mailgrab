<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Socket;

use Amp\Promise;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PHPUnit\Framework\TestCase;
use function PeeHaa\MailGrab\listen;

class ServerTest extends TestCase
{
    public function testAcceptReturnsPromise()
    {
        $logger = $this->createMock(Output::class);

        $server = listen($logger, 'tcp://127.0.0.1:9999');

        $this->assertInstanceOf(Promise::class, $server->accept());
    }
}
