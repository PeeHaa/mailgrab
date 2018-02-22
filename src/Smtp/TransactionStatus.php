<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp;

use MyCLabs\Enum\Enum;

class TransactionStatus extends Enum
{
    public const NEW         = 0;
    public const SEND_BANNER = 1;
    public const INIT        = 2;
    public const FROM        = 3;
    public const TO          = 4;
    public const HEADERS     = 5;
    public const UNFOLDING   = 6;
    public const BODY        = 7;
    public const PROCESSING  = 8;
    public const QUIT        = 9;
}
