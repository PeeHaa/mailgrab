<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Http\WebSocket;

use Amp\Http\Server\Driver\Client;
use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use function Amp\Promise\wait;
use PeeHaa\AmpWebsocketCommand\Executor;
use PeeHaa\MailGrab\Http\Entity\Mail;
use PeeHaa\MailGrab\Http\Storage\Storage;
use PeeHaa\MailGrab\Http\WebSocket\Handler;
use PeeHaa\MailGrab\Smtp\Message;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

class HandlerTest extends TestCase
{
    public function testOnHandshakeWithValidOrigin()
    {
        $handler = new Handler(
            'http://example.com',
            $this->createMock(Executor::class),
            $this->createMock(Storage::class),
            ['127.0.0.1'],
            9025
        );

        $request = new Request($this->createMock(Client::class), 'GET', $this->createMock(UriInterface::class));
        $request->setHeader('origin', 'http://example.com');

        $response = new Response();

        $handler->onHandshake($request, $response);

        $this->assertSame(200, $response->getStatus());
    }

    public function testOnHandshakeWithInvalidOrigin()
    {
        $handler = new Handler(
            'http://example.com',
            $this->createMock(Executor::class),
            $this->createMock(Storage::class),
            ['127.0.0.1'],
            9025
        );

        $request = new Request($this->createMock(Client::class), 'GET', $this->createMock(UriInterface::class));
        $request->setHeader('origin', 'http://notexample.com');

        $response = new Response();

        $handler->onHandshake($request, $response);

        $this->assertSame(403, $response->getStatus());
    }

    public function testGetAttachment()
    {
        /** @var MockObject|Message $messageMock */
        $messageMock = $this->getMockBuilder(Message::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRawMessage')
            ->willReturn(file_get_contents(DATA_DIR . '/raw-message-with-attachments.txt'))
        ;

        $messageMock
            ->expects($this->any())
            ->method('getRecipients')
            ->willReturn([])
        ;

        $mail = new Mail($messageMock);

        $storage = $this->createMock(Storage::class);

        $storage
            ->expects($this->once())
            ->method('get')
            ->with('abc-123')
            ->willReturn($mail)
        ;

        $handler = new Handler(
            'http://example.com',
            $this->createMock(Executor::class),
            $storage,
            ['127.0.0.1'],
            9025
        );

        $this->assertCount(4, wait($handler->getAttachment('abc-123', 0)));
    }
}
