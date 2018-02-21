<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp\Response;

class ActionCompleted extends Message
{
    public function __construct(string $message)
    {
        parent::__construct(250, $message);
    }
}
