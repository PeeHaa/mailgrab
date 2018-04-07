<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\WebSocket;

use Amp\Http\Server\Request;
use Amp\Http\Server\Response;
use Amp\Http\Server\Websocket\Application;
use Amp\Http\Server\Websocket\Endpoint;
use Amp\Http\Server\Websocket\Message as WebSocketMessage;
use PeeHaa\AmpWebsocketCommand\Executor;
use PeeHaa\MailGrab\Http\Entity\Mail;
use PeeHaa\MailGrab\Http\Storage\Storage;
use PeeHaa\MailGrab\Smtp\Command\Factory;
use PeeHaa\MailGrab\Smtp\Log\Level;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PeeHaa\MailGrab\Smtp\Message;
use PeeHaa\MailGrab\Smtp\Server;
use function Amp\asyncCall;

class Handler implements Application
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
        }

        return $response;
    }

    public function onOpen(int $clientId, Request $request)
    {
        // empty on purpose
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

    public function onData(int $clientId, WebSocketMessage $message)
    {
        $rawCommand = yield $message->read();

        $command = json_decode($rawCommand, true);

        $this->endpoint->send((string) yield $this->executor->execute($rawCommand), $clientId);

        if ($command['command'] === 'delete') {
            $result = yield $this->executor->execute(json_encode([
                'command' => 'deleteNotification',
                'id'      => $command['id'],
            ]));

            $this->endpoint->broadcast((string) $result, [$clientId]);
        }

        if ($command['command'] === 'selectMail') {
            $result = yield $this->executor->execute(json_encode([
                'command' => 'readNotification',
                'id'      => $command['id'],
            ]));

            $this->endpoint->broadcast((string) $result, [$clientId]);
        }
    }

    public function onClose(int $clientId, int $code, string $reason)
    {

    }

    public function onStop()
    {
        // intentionally left blank
    }
}
