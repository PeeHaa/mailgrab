<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp\Header;

class Header
{
    private $key;

    private $value;

    public function __construct(Buffer $buffer)
    {
        $this->key   = $buffer->getKey();
        $this->value = $buffer->getBuffer();
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
