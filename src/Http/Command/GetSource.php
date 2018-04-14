<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Command;

use Amp\Promise;
use PeeHaa\AmpWebsocketCommand\Command;
use PeeHaa\AmpWebsocketCommand\Failure;
use PeeHaa\AmpWebsocketCommand\Input;
use PeeHaa\AmpWebsocketCommand\Success;
use PeeHaa\MailGrab\Http\Storage\Storage;
use function Amp\call;

class GetSource implements Command
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
                return new Success([
                    'command' => 'source',
                    'source'  => $this->get($input->getParameter('id')),
                ]);
            } catch (\Throwable $e) {
                return new Failure('foobar');
            }
        });
    }

    private function get(string $id): string
    {
        if (!$this->storage->has($id)) {
            throw new \Exception('Message not found');
        }

        $mail = $this->storage->get($id);

        return $mail->getSource();
    }
}
