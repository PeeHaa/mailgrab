<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Command;

class Unfold implements Command
{
    private const PATTERN = '/^\s+(.*)$/';

    private $chunk;

    public static function isValid(string $line): bool
    {
        return preg_match(self::PATTERN, $line) === 1;
    }

    public function __construct(string $line)
    {
        preg_match(self::PATTERN, $line, $matches);

        $this->chunk = $matches[1];
    }

    public function getChunk(): string
    {
        return $this->chunk;
    }
}
