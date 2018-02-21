<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Log;

use MyCLabs\Enum\Enum;

class Level extends Enum
{
    public const INFO       = 1;
    public const MESSAGE_IN = 2;
    public const SMTP_IN    = 4;
    public const SMTP_OUT   = 8;
    public const DEBUG      = 16;
    public const ALL        = 31;
}
