<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp\Socket;

use Amp\Promise;
use Amp\Socket\Server as AmpServer;
use PeeHaa\MailGrab\Smtp\Log\Output;
use function Amp\call;

class Server extends AmpServer
{
    private $logger;

    public function __construct(Output $logger, $socket, int $chunkSize = 65536)
    {
        $this->logger = $logger;

        parent::__construct($socket, $chunkSize);
    }

    public function accept(): Promise {
        return call(function() {
            $ampSocket = yield parent::accept();

            return new ServerSocket($this->logger, $ampSocket);
        });
    }
}
