<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\WebSocket;

use Aerys\Request;
use Aerys\Response;
use Aerys\Websocket;
use Aerys\Websocket\Endpoint;

class Handler implements Websocket
{
    /** @var Endpoint */
    private $endpoint;

    private $origin;

    public function __construct(string $origin)
    {
        $this->origin = $origin;
    }

    public function onStart(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    public function onHandshake(Request $request, Response $response)
    {
        if ($request->getHeader('origin') !== $this->origin) {
            $response->setStatus(403);
            $response->end('<h1>origin not allowed</h1>');

            return null;
        }

        return $request->getConnectionInfo()['client_addr'];
    }

    public function onOpen(int $clientId, $handshakeData)
    {

    }

    public function onData(int $clientId, Websocket\Message $msg)
    {
        // yielding $msg buffers the complete payload into a single string.
    }

    public function onClose(int $clientId, int $code, string $reason)
    {

    }

    public function onStop()
    {
        // intentionally left blank
    }
}
