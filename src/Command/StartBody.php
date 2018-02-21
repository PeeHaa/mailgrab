<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Command;

class StartBody implements Command
{
    private const PATTERN = '/^$/';

    public static function isValid(string $line): bool
    {
        return preg_match(self::PATTERN, $line) === 1;
    }

    public function __construct(string $line)
    {
    }
}
