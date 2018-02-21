<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Command;

class RcptTo implements Command
{
    private const PATTERN = '/^RCPT TO:\s*(?<name>.*?)?\s*?\<(?<email>.*)\>\s*$/';

    private $name;

    private $address;

    public static function isValid(string $line): bool
    {
        return preg_match(self::PATTERN, $line) === 1;
    }

    public function __construct(string $line)
    {
        preg_match(self::PATTERN, $line, $matches);

        $this->name = $matches['email'];

        if ($matches['name']) {
            $this->name = $matches['name'];
        }

        $this->address = $matches['email'];
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }
}
