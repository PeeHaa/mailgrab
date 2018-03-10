<?php declare(strict_types=1);

namespace PeeHaa\MailGrab;

use Aerys\Host;
use Aerys\InternalRequest;
use Aerys\Request;
use Aerys\Response;
use Aerys\Router;
use function Amp\File\get;
use Auryn\Injector;
use PeeHaa\AmpWebsocketCommand\CommandTuple;
use PeeHaa\AmpWebsocketCommand\Executor;
use PeeHaa\MailGrab\Http\Command\Delete;
use PeeHaa\MailGrab\Http\Command\DeleteNotification;
use PeeHaa\MailGrab\Http\Command\GetHtml;
use PeeHaa\MailGrab\Http\Command\GetHtmlWithoutImages;
use PeeHaa\MailGrab\Http\Command\GetSource;
use PeeHaa\MailGrab\Http\Command\GetText;
use PeeHaa\MailGrab\Http\Command\Init;
use PeeHaa\MailGrab\Http\Command\NewMail;
use PeeHaa\MailGrab\Http\Command\ReadNotification;
use PeeHaa\MailGrab\Http\Command\RefreshMail;
use PeeHaa\MailGrab\Http\Command\SelectMail;
use PeeHaa\MailGrab\Http\Storage\Memory;
use PeeHaa\MailGrab\Http\Storage\Storage;
use PeeHaa\MailGrab\Http\WebSocket\Handler;
use function Aerys\root;
use function Aerys\websocket;

require_once __DIR__ . '/../vendor/autoload.php';

$auryn = new Injector();
$auryn->share($auryn); // yolo

$auryn->alias(Storage::class, Memory::class);
$auryn->share(Storage::class);

$auryn->delegate(Executor::class, function() use ($auryn) {
    $executor = new Executor($auryn);

    $executor->register(new CommandTuple('init', Init::class));
    $executor->register(new CommandTuple('newMail', NewMail::class));
    $executor->register(new CommandTuple('selectMail', SelectMail::class));
    $executor->register(new CommandTuple('refreshMail', RefreshMail::class));
    $executor->register(new CommandTuple('getText', GetText::class));
    $executor->register(new CommandTuple('getHtml', GetHtml::class));
    $executor->register(new CommandTuple('getHtmlWithoutImages', GetHtmlWithoutImages::class));
    $executor->register(new CommandTuple('getSource', GetSource::class));
    $executor->register(new CommandTuple('delete', Delete::class));
    $executor->register(new CommandTuple('deleteNotification', DeleteNotification::class));
    $executor->register(new CommandTuple('readNotification', ReadNotification::class));

    return $executor;
});

$auryn->define(Handler::class, [
    ':origin' => 'http://localhost:9000',
]);

$router = (new Router())
    ->route('GET', '/ws', websocket($auryn->make(Handler::class)))
;

return (new Host())
    ->name('localhost')
    ->expose('127.0.0.1', 9000)
    ->use($router)
    ->use(root(__DIR__ . '/../public'))
    ->use(function(Request $request, Response $response) {
        static $cache = '';

        if(!$cache) {
            $cache = yield get(__DIR__ . '/../public/index.html');
        }

        $response->end($cache);
    })
;
