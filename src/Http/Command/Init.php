<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Command;

use Amp\Promise;
use PeeHaa\AmpWebsocketCommand\Command;
use PeeHaa\AmpWebsocketCommand\Input;
use PeeHaa\AmpWebsocketCommand\Success;
use PeeHaa\MailGrab\Configuration;
use PeeHaa\MailGrab\Http\Entity\Mail;
use PeeHaa\MailGrab\Http\Storage\Storage;
use function Amp\call;

class Init implements Command
{
    private $storage;

    private $configuration;

    public function __construct(Storage $storage, Configuration $configuration)
    {
        $this->storage       = $storage;
        $this->configuration = $configuration;
    }

    public function execute(Input $input): Promise
    {
        return call(function() {
            return new Success([
                'command' => 'init',
                'mails'   => $this->buildList(),
                'config'  => $this->buildConfiguration(),
            ]);
        });
    }

    private function buildList(): array
    {
        $list = [];

        /** @var Mail $mail */
        foreach ($this->storage as $mail) {
            $list[] = [
                'id'                => $mail->getId(),
                'subject'           => $mail->getSubject(),
                'searchableContent' => $mail->getSearchableContent(),
                'timestamp'         => $mail->getTimestamp()->format(\DateTime::RFC3339_EXTENDED),
                'read'              => $mail->isRead(),
                'project'           => $mail->getProject(),
            ];
        }

        return $list;
    }

    private function buildConfiguration(): array
    {
        return [
            'hostname' => $this->configuration->get('hostname'),
            'smtpport' => $this->configuration->get('smtpport'),
        ];
    }
}
