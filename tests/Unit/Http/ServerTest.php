<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Http;

use Amp\Artax\Client;
use Amp\Artax\DefaultClient;
use Amp\Artax\Response;
use Amp\Http\Server\Websocket\Application;
use Amp\Loop;
use Amp\Promise;
use PeeHaa\MailGrab\Http\Server;
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
