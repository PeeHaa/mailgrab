<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp;

use PeeHaa\MailGrab\Smtp\Command\Factory;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PeeHaa\MailGrab\Smtp\Server;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    public function testRunWithoutAddresses()
    {
        $server = new Server(
            $this->createMock(Factory::class),
            function() {},
            $this->createMock(Output::class),
            [],
            9025
        );

        $this->assertInstanceOf(Server::class, $server);

        $server->run();
    }
}
