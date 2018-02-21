<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Response;

class StartInput extends Message
{
    public function __construct(string $message)
    {
        parent::__construct(354, $message);
    }
}
