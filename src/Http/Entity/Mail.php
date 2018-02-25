<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Entity;

use PeeHaa\MailGrab\Smtp\Message;
use Ramsey\Uuid\Uuid;
use ZBateson\MailMimeParser\MailMimeParser;

class Mail
{
    private $id;

    private $timestamp;

    private $rawMessage;

    private $parsedMessage;

    private $read = false;

    private $project = '0';

    public function __construct(Message $message)
    {
        $this->id            = Uuid::uuid4()->toString();
        $this->timestamp     = new \DateTimeImmutable();
        $this->rawMessage    = $message->getRawMessage();
        $this->parsedMessage = (new MailMimeParser())->parse($message->getRawMessage());
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getFrom(): string
    {
        return $this->parsedMessage->getHeader('from')->getRawValue();
    }

    public function getTo(): string
    {
        return $this->parsedMessage->getHeader('to')->getRawValue();
    }

    public function getSubject(): string
    {
        return $this->parsedMessage->getHeaderValue('subject');
    }

    public function getTimestamp(): \DateTimeImmutable
    {
        return $this->timestamp;
    }

    public function getText(): ?string
    {
        return $this->parsedMessage->getTextContent();
    }

    public function getHtml(): ?string
    {
        return $this->parsedMessage->getHtmlContent();
    }

    public function getSource(): string
    {
        return $this->rawMessage;
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
