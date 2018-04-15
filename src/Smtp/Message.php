<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp;

use PeeHaa\MailGrab\Smtp\Header\Header;

class Message
{
    private $rawMessage = '';

    private $from;

    private $recipients = [];

    /** @var Header[] */
    private $headers = [];

    public function appendToRawMessage(string $chunk): void
    {
        $this->rawMessage .= $chunk . "\r\n";
    }

    public function getRawMessage(): string
    {
        return $this->rawMessage;
    }

    public function setFrom(string $from): void
    {
        $this->from = $from;
    }

    public function getFrom(): string
    {
        return $this->from;
    }

    public function addRecipient(string $email, string $name): void
    {
        $this->recipients[$email] = $name;
    }

    public function getRecipients(): array
    {
        return $this->recipients;
    }

    public function addHeader(Header $header): void
    {
        $this->headers[strtolower($header->getKey())] = $header;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
