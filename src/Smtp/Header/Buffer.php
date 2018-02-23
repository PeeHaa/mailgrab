<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp\Header;

class Buffer
{
    private $key;

    private $buffer = '';

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function append(string $chunk): void
    {
        $this->buffer .= $chunk;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getBuffer(): string
    {
        return $this->buffer;
    }
}
