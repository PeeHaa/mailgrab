<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Command;

use Amp\Promise;
use PeeHaa\AmpWebsocketCommand\Command;
use PeeHaa\AmpWebsocketCommand\Input;
use PeeHaa\AmpWebsocketCommand\Success;
use PeeHaa\MailGrab\Http\Entity\Mail;
use PeeHaa\MailGrab\Http\Storage\Storage;
use function Amp\call;

class Init implements Command
{
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    public function execute(Input $input): Promise
    {
        return call(function() {
            return new Success([
                'command' => 'newMail',
                'mails'   => $this->buildList(),
            ]);
        });
    }

    private function buildList(): array
    {
        $list = [];

        /** @var Mail $mail */
        foreach ($this->storage as $mail) {
            $list[] = [
                'id'        => $mail->getId(),
                'subject'   => $mail->getSubject(),
                'timestamp' => $mail->getTimestamp()->format(\DateTime::RFC3339_EXTENDED),
                'read'      => $mail->isRead(),
                'project'   => $mail->getProject(),
            ];
        }

        return $list;
    }
}
