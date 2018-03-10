<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Command;

use Amp\Promise;
use PeeHaa\AmpWebsocketCommand\Command;
use PeeHaa\AmpWebsocketCommand\Failure;
use PeeHaa\AmpWebsocketCommand\Input;
use PeeHaa\AmpWebsocketCommand\Success;
use PeeHaa\MailGrab\Http\Storage\Storage;
use function Amp\call;

class Delete implements Command
{
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function execute(Input $input): Promise
    {
        return call(function() use ($input) {
            try {
                $this->deleteMail($input->getParameter('id'));
            } catch (\Throwable $e) {
                return new Failure('foobar');
            }

            return new Success([
                'command' => 'delete',
                'id'      => $input->getParameter('id'),
            ]);
        });
    }

    private function deleteMail(string $id): void
    {
        if (!$this->storage->has($id)) {
            throw new \Exception('Message not found');
        }

        $this->storage->delete($id);
    }
}
