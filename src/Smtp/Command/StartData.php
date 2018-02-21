<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp\Command;

class StartData implements Command
{
    private const PATTERN = '/^DATA$/';

    public static function isValid(string $line): bool
    {
        return preg_match(self::PATTERN, $line) === 1;
    }

    public function __construct(string $line)
    {
    }
}
