<?php declare(strict_types=1);

namespace PeeHaa\MailGrab;

use Aerys\Host;
use Aerys\Router;
use Auryn\Injector;
use PeeHaa\MailGrab\Http\WebSocket\Handler;
use function Aerys\root;
use function Aerys\websocket;

require_once __DIR__ . '/../vendor/autoload.php';

$auryn = new Injector();

$auryn->define(Handler::class, [
    ':origin' => 'http://localhost:8000',
]);

$router = (new Router())
    ->route('GET', '/ws', websocket($auryn->make(Handler::class)))
;

return (new Host())
    ->name('localhost')
    ->expose('127.0.0.1', 8000)
    ->use($router)
    ->use(root(__DIR__ . '/../public'))
;
