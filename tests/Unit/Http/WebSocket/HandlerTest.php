<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Http\WebSocket;

use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use PeeHaa\AmpWebsocketCommand\Executor;
use PeeHaa\MailGrab\Http\Storage\Storage;
use PeeHaa\MailGrab\Http\WebSocket\Handler;
use PHPUnit\Framework\TestCase;

class HandlerTest extends TestCase
{
    public function testOnHandshakeWithValidOrigin()
    {
        $this->markTestSkipped('The AMP interface hints against final classes making this a PITA.');

        $handler = new Handler(
            'http://example.com',
            $this->createMock(Executor::class),
            $this->createMock(Storage::class)
        );

        $request = $this->createMock(Request::class);

        $request
            ->expects($this->once())
            ->method('getHeader')
            ->with('origin')
            ->willReturn('http://example.com')
        ;

        $response = $this->createMock(Response::class);

        $response
            ->expects($this->never())
            ->method('setStatus')
        ;

        $handler->onHandshake($request, $response);
    }

    public function testOnHandshakeWithInvalidOrigin()
    {
        $this->markTestSkipped('The AMP interface hints against final classes making this a PITA.');

        $handler = new Handler(
            'http://example.com',
            $this->createMock(Executor::class),
            $this->createMock(Storage::class)
        );

        $request = $this->createMock(Request::class);

        $request
            ->expects($this->once())
            ->method('getHeader')
            ->with('origin')
            ->willReturn('http://not.example.com')
        ;

        $response = $this->createMock(Response::class);

        $response
            ->expects($this->once())
            ->method('setStatus')
            ->with(403)
        ;

        $handler->onHandshake($request, $response);
    }
}
