<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Command;

class BodyLine implements Command
{
    private const PATTERN = '/^(.*)$/';

    private $line;

    public static function isValid(string $line): bool
    {
        return preg_match(self::PATTERN, $line) === 1;
    }

    public function __construct(string $line)
    {
        $this->line = $line;
    }

    public function getLine(): string
    {
        return $this->line;
    }
}
