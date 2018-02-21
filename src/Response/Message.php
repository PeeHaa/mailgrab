<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Response;

class Message
{
    private $code;

    private $message;

    public function __construct(int $code, string $message)
    {
        $this->code    = $code;
        $this->message = $message;
    }

    public function __toString(): string
    {
        return sprintf("%d %s\r\n", $this->code, $this->message);
    }
}
