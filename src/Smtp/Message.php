<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp;

class Message
{
    private $from;

    private $recipients = [];

    /** @var null|HeaderBuffer */
    private $headerBuffer;

    private $headers = [];

    private $body = '';

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

    public function createHeaderBuffer(string $key, string $chunk): void
    {
        $this->headerBuffer = new HeaderBuffer($key, $chunk);
    }

    public function appendToHeaderBuffer(string $chunk): void
    {
        if ($this->headerBuffer === null) {
            throw new \Exception('No open buffer');
        }

        $this->headerBuffer->append($chunk);
    }

    public function finalizeHeader(): void
    {
        $this->headers[strtolower($this->headerBuffer->getKey())] = $this->headerBuffer;

        $this->headerBuffer = null;
    }

    public function appendToBody(string $content): void
    {
        $this->body .= $content . "\r\n";
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }
}
