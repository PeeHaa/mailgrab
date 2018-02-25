<?php declare(strict_types=1);

namespace PeeHaa\MailGrab;

use Aerys\Server;
use Amp\Socket\Server as AmpServer;
use Amp\Socket\ServerListenContext;
use Amp\Socket\ServerTlsContext;
use Amp\Socket\SocketException;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PeeHaa\MailGrab\Smtp\Socket\Server as SocketServer;

/**
 * Listen for client connections on the specified server address.
 *
 * If you want to accept TLS connections, you have to use `yield $socket->enableCrypto()` after accepting new clients.
 *
 * @param Output $logger
 * @param string $uri URI in scheme://host:port format. TCP is assumed if no scheme is present.
 * @param ServerListenContext $socketContext Context options for listening.
 * @param ServerTlsContext $tlsContext Context options for TLS connections.
 *
 * @return Server
 *
 * @throws SocketException If binding to the specified URI failed.
 * @throws \Error If an invalid scheme is given.
 */
function listen(Output $logger, string $uri, ServerListenContext $socketContext = null, ServerTlsContext $tlsContext = null): AmpServer {
    $socketContext = $socketContext ?? new ServerListenContext;
    $tlsContext = $tlsContext ?? new ServerTlsContext;

    $scheme = \strstr($uri, "://", true);

    if ($scheme === false) {
        $scheme = "tcp";
    }

    if (!\in_array($scheme, ["tcp", "udp", "unix", "udg"])) {
        throw new \Error("Only tcp, udp, unix and udg schemes allowed for server creation");
    }

    $context = \stream_context_create(\array_merge(
        $socketContext->toStreamContextArray(),
        $tlsContext->toStreamContextArray()
    ));

    // Error reporting suppressed since stream_socket_server() emits an E_WARNING on failure (checked below).
    $server = @\stream_socket_server($uri, $errno, $errstr, STREAM_SERVER_BIND | STREAM_SERVER_LISTEN, $context);

    if (!$server || $errno) {
        throw new SocketException(\sprintf("Could not create server %s: [Error: #%d] %s", $uri, $errno, $errstr), $errno);
    }

    return new SocketServer($logger, $server, 65536);
}
