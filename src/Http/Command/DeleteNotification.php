<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Command;

use Amp\Promise;
use PeeHaa\AmpWebsocketCommand\Command;
use PeeHaa\AmpWebsocketCommand\Input;
use PeeHaa\AmpWebsocketCommand\Success;
use PeeHaa\MailGrab\Http\Storage\Storage;
use function Amp\call;

class DeleteNotification implements Command
{
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function execute(Input $input): Promise
    {
        return call(function() use ($input) {
            return new Success([
                'command' => 'deleteNotification',
                'id'      => $input->getParameter('id'),
            ]);
        });
    }
}
