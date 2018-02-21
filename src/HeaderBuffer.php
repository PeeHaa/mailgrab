<?php declare(strict_types=1);

namespace PeeHaa\MailGrab;

class HeaderBuffer
{
    private $key;

    private $buffer;

    public function __construct(string $key, string $chunk)
    {
        $this->key    = $key;
        $this->buffer = $chunk;
    }

    public function append(string $chunk): void
    {
        $this->buffer .= $chunk;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->buffer;
    }
}
