<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http;

use Amp\ByteStream\ResourceOutputStream;
use Amp\Http\Server\RequestHandler;
use Amp\Http\Server\RequestHandler\CallableRequestHandler;
use Amp\Http\Server\Response;
use Amp\Http\Server\Router;
use Amp\Http\Server\Server as HttpServer;
use Amp\Http\Server\StaticContent\DocumentRoot;
use Amp\Http\Server\Websocket\Application;
use Amp\Http\Server\Websocket\Websocket;
use Amp\Http\Status;
use Amp\Log\ConsoleFormatter;
use Amp\Log\StreamHandler;
use Amp\Promise;
use Monolog\Logger;
use function Amp\File\get;
use function Amp\Socket\listen;

class Server
{
    private $server;

    public function __construct(Application $webSocketApplication, string $documentRoot, array $addresses, int $port)
    {
        $logHandler = new StreamHandler(new ResourceOutputStream(\STDOUT));
        $logHandler->setFormatter(new ConsoleFormatter(ConsoleFormatter::DEFAULT_FORMAT, ConsoleFormatter::SIMPLE_DATE, true));
        $logger = new Logger('server');
        $logger->pushHandler($logHandler);

        $this->server = new HttpServer(
            $this->buildServers($addresses, $port),
            $this->buildRouter($webSocketApplication, $documentRoot),
            $logger//new NullLogger()
        );
    }

    private function buildServers(array $addresses, int $port): array
    {
        return array_reduce($addresses, function(array $servers, $address) use ($port) {
            $servers[] = listen(sprintf('%s:%d', $address, $port));

            return $servers;
        }, []);
    }

    private function buildRouter(Application $webSocketApplication, string $documentRootPath): Router
    {
        $router = new Router();

        $router->addRoute('GET', '/ws', new Websocket($webSocketApplication));

        $documentRoot = new DocumentRoot($documentRootPath);
        $documentRoot->setFallback($this->buildFallback($documentRootPath));

        $router->setFallback($documentRoot);

        return $router;
    }

    private function buildFallback(string $documentRootPath): RequestHandler
    {
        return new CallableRequestHandler(function () use ($documentRootPath) {
            static $response = null;

            if (!$response) {
                $response = yield get($documentRootPath . '/index.html');
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
