<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Http\Response;

class Initialized
{
    public function __toString(): string
    {
        return json_encode([
            'type' => 'initialized',
            'data' => [],
        ]);
    }
}
