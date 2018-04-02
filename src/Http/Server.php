<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http;

use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\RequestHandler\CallableRequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Server\Router;
use Amp\Http\Server\Server as HttpServer;
use Amp\Http\Server\StaticContent\DocumentRoot;
use Amp\Http\Status;
use Amp\Promise;
use Psr\Log\NullLogger;
use function Amp\Socket\listen;

class Server
{
    private $server;

    public function __construct(string $documentRoot, array $addresses, int $port)
    {
        $this->server = new HttpServer(
            $this->buildServers($addresses, $port),
            $this->buildRouter($documentRoot),
            new NullLogger()
        );
    }

    private function buildServers(array $addresses, int $port): array
    {
        return array_reduce($addresses, function(array $servers, $address) use ($port) {
            $servers[] = listen(sprintf('%s:%d', $address, $port));

            return $servers;
        }, []);
    }

    private function buildRouter(string $documentRootPath): Router
    {
        $router = new Router();

        $router->addRoute('GET', '/ws', new CallableRequestHandler(function () {
            return new Response(Status::OK, ['content-type' => 'text/plain'], 'Hello, world!');
        }));

        $documentRoot = new DocumentRoot($documentRootPath);
        $documentRoot->setFallback($this->buildFallback($documentRootPath));

        $router->setFallback($documentRoot);

        return $router;
    }

    private function buildFallback(string $documentRootPath): RequestHandler
    {
        return new CallableRequestHandler(function () use ($documentRootPath) {
            static $response = null;

            if(!$response) {
                $response = yield \Amp\File\get($documentRootPath . '/index.html');
            }

            return new Response(Status::OK, ['content-type' => 'text/html'], $response);
        });
    }

    public function start(): Promise
    {
        return $this->server->start();
    }

    public function stop(): Promise
    {
        return $this->server->stop();
    }
}
