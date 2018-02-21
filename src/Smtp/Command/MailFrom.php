<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp\Command;

class MailFrom implements Command
{
    private const PATTERN = '/^MAIL FROM:\s*\<(?<email>.*)\>( .*)?$/';

    private $address;

    public static function isValid(string $line): bool
    {
        return preg_match(self::PATTERN, $line) === 1;
    }

    public function __construct(string $line)
    {
        preg_match(self::PATTERN, $line, $matches);

        $this->address = $matches['email'];
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}
