<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Command;

class Helo implements Command
{
    private const PATTERN = '/^HELO (.*)$/';

    private $address;

    public static function isValid(string $line): bool
    {
        return preg_match(self::PATTERN, $line) === 1;
    }

    public function __construct(string $line)
    {
        preg_match(self::PATTERN, $line, $matches);

        $this->address = $matches[1];
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}
