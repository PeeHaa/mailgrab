<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Entity;

use PeeHaa\MailGrab\Smtp\Header\Header;
use PeeHaa\MailGrab\Smtp\Message;
use Ramsey\Uuid\Uuid;

class Mail
{
    private $id;

    private $from;

    private $to = [];

    private $message;

    private $subject = '';

    private $timestamp;

    private $read = false;

    private $project = '0';

    public function __construct(Message $message)
    {
        $this->id      = Uuid::uuid4()->toString();
        $this->from    = $message->getFrom();
        $this->message = $message;

        foreach ($message->getRecipients() as $email => $name) {
            $this->to[] = new Recipient($email, $name);
        }

        /** @var Header[] $headers */
        $headers = $message->getHeaders();

        if (isset($headers['subject'])) {
            $this->subject = $headers['subject']->getValue();
        }

        $this->timestamp = new \DateTimeImmutable();
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function getTo(): string
    {
        return implode(', ', array_reduce($this->to, function(array $carry, Recipient $recipient) {
            $carry[] = sprintf('%s <%s>', $recipient->getName(), $recipient->getEmail());

            return $carry;
        }, []));
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function getMessage(): Message
    {
        return $this->message;
    }

    public function setRead(): void
    {
        $this->read = true;
    }

    public function isRead(): bool
    {
        return $this->read;
    }

    public function getProject(): string
    {
        return $this->project;
    }
}
