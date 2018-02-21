<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Command;

class StartHeader implements Command
{
    private const PATTERN = '/^([\w\-]+):\s*(.*)$/';

    private $key;

    private $value;

    public static function isValid(string $line): bool
    {
        return preg_match(self::PATTERN, $line) === 1;
    }

    public function __construct(string $line)
    {
        preg_match(self::PATTERN, $line, $matches);

        $this->key   = $matches[1];
        $this->value = $matches[2];
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
