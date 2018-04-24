<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Http;

use Amp\Artax\Client;
use Amp\Artax\DefaultClient;
use Amp\Artax\Response;
use Amp\Http\Server\Websocket\Application;
use Amp\Loop;
use Amp\Promise;
use Amp\Success;
use PeeHaa\MailGrab\Http\Server;
use PeeHaa\MailGrab\Http\WebSocket\Handler;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{
    public function testServerCanBeStarted()
    {
        Loop::run(function() {
            $application = $this->createMock(Application::class);

            $server = new Server($application, DATA_DIR, ['127.0.0.1'], 9998);

            $startPromise = $server->start();

            $this->assertInstanceOf(Promise::class, $startPromise);

            $stopPromise = $server->stop();

            $this->assertInstanceOf(Promise::class, $stopPromise);
        });
    }

    public function testReturnsIndex()
    {
        Loop::run(function() {
            $application = $this->createMock(Application::class);

            $server = new Server($application, DATA_DIR . '/DocRoot', ['127.0.0.1'], 9997);

            $server->start();

            /** @var Response $response */
            $response = yield (new DefaultClient())->request('http://127.0.0.1:9997', [
                Client::OP_TRANSFER_TIMEOUT => 4000,
            ]);

            $this->assertSame('foobar', yield $response->getBody());

            $server->stop();
        });
    }

    public function testDownloadRequest()
    {
        Loop::run(function() {
            $application = $this->createMock(Handler::class);

            $application
                ->method('getAttachment')
                ->willReturnCallback(function() {
                    return new Success([
                        'content-type' => 'text/html',
                        'name'         => 'test.html',
                        'content'      => '<foo>html</foo>',
                    ]);
                })
            ;

            $server = new Server($application, DATA_DIR . '/DocRoot', ['127.0.0.1'], 9998);

            $server->start();

            /** @var Response $response */
            $response = yield (new DefaultClient())->request('http://127.0.0.1:9998/0/uncategorized/abc-123/test-mail/attachment/0', [
                Client::OP_TRANSFER_TIMEOUT => 4000,
            ]);

            $this->assertSame('<foo>html</foo>', yield $response->getBody());

            $server->stop();
        });
    }

    public function testFallbackRequest()
    {
        Loop::run(function() {
            $application = $this->createMock(Application::class);

            $server = new Server($application, DATA_DIR . '/DocRoot', ['127.0.0.1'], 9998);

            $server->start();

            /** @var Response $response */
            $response = yield (new DefaultClient())->request('http://127.0.0.1:9998/fallback', [
                Client::OP_TRANSFER_TIMEOUT => 4000,
            ]);

            $this->assertSame('foobar', yield $response->getBody());

            $server->stop();
        });
    }
}
