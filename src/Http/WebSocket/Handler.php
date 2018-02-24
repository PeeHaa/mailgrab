<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\WebSocket;

use Aerys\Request;
use Aerys\Response;
use Aerys\Websocket;
use Aerys\Websocket\Endpoint;
use PeeHaa\AmpWebsocketCommand\Executor;
use PeeHaa\MailGrab\Http\Entity\Mail;
use PeeHaa\MailGrab\Http\Storage\Storage;
use PeeHaa\MailGrab\Smtp\Command\Factory;
use PeeHaa\MailGrab\Smtp\Log\Level;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PeeHaa\MailGrab\Smtp\Message;
use PeeHaa\MailGrab\Smtp\Server;
use function Amp\asyncCall;

class Handler implements Websocket
{
    /** @var Endpoint */
    private $endpoint;

    private $origin;

    private $executor;

    private $storage;

    public function __construct(string $origin, Executor $executor, Storage $storage)
    {
        $this->origin   = $origin;
        $this->executor = $executor;
        $this->storage  = $storage;
    }

    public function onStart(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;

        asyncCall(function() {
            (new Server(new Factory(), [$this, 'pushMessage'], new Output(new Level(Level::INFO))))->run();
        });
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

    public function pushMessage(Message $message)
    {
        asyncCall(function() use ($message) {
            $mail = new Mail($message);

            $this->storage->add($mail);

            $result = yield $this->executor->execute(json_encode([
                'command' => 'newMail',
                'id'      => $mail->getId(),
            ]));

            $this->endpoint->broadcast((string) $result);
        });
    }

    public function onData(int $clientId, Websocket\Message $msg)
    {
        $this->endpoint->send((string) yield $this->executor->execute(yield $msg), $clientId);
    }

    public function onClose(int $clientId, int $code, string $reason)
    {

    }

    public function onStop()
    {
        // intentionally left blank
    }
}
