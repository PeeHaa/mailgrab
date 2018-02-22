<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp\Socket;

use Amp\Promise;
use Amp\Socket\ServerSocket as AmpServerSocket;
use PeeHaa\MailGrab\Smtp\Log\Output;

/**
 * @method Promise write(string $data)
 * @method Promise read()
 * @method Promise close()
 * @method getRemoteAddress()
 */
class ServerSocket
{
    private $logger;

    private $ampSocket;

    public function __construct(Output $logger, AmpServerSocket $ampSocket)
    {
        $this->logger    = $logger;
        $this->ampSocket = $ampSocket;
    }

    public function __call(string $name, array $arguments)
    {
        if ($name === 'write') {
            $this->logger->smtpOut(...$arguments);
        }

        return $this->ampSocket->$name(...$arguments);
    }
}
