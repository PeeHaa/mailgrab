<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Command;

use Amp\Promise;
use PeeHaa\AmpWebsocketCommand\Command;
use PeeHaa\AmpWebsocketCommand\Input;
use PeeHaa\AmpWebsocketCommand\Success;
use PeeHaa\MailGrab\Http\Storage\Storage;
use function Amp\call;

class NewMail implements Command
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
                'command'  => 'newMail',
                'mails'    => $this->buildList($input->getParameter('id')),
            ]);
        });
    }

    private function buildList(string $id): array
    {
        if (!$this->storage->has($id)) {
            return [
                'id'      => $id,
                'deleted' => true,
            ];
        }

        $mail = $this->storage->get($id);

        return [
            [
                'id'        => $mail->getId(),
                'subject'   => $mail->getSubject(),
                'timestamp' => $mail->getTimestamp()->format(\DateTime::RFC3339_EXTENDED),
                'read'      => $mail->isRead(),
                'deleted'   => false,
                'project'   => $mail->getProject(),
            ],
        ];
    }
}
