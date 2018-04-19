<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Cli\Input;

class Argument
{
    private $long = false;

    private $key;

    private $value;

    public function __construct(string $argument)
    {
        $argument = strtolower($argument);

        $this->validate($argument);

        $this->setLongWhenApplicable($argument);
        $this->setKey($argument);
        $this->setValue($argument);
    }

    private function validate(string $argument): void
    {
        if (!preg_match('~^--?[a-z]+~', $argument)) {
            throw new \Exception('Malformed argument.');
        }
    }

    private function setLongWhenApplicable(string $argument): void
    {
        if (strpos($argument, '--') === 0) {
            $this->long = true;
        }
    }

    private function setKey(string $argument): void
    {
        $offset = 1;

        if ($this->long) {
            $offset++;
        }

        $this->key = substr(explode('=', $argument, 2)[0], $offset);
    }

    private function setValue(string $argument): void
    {
        $parts = explode('=', $argument, 2);

        if (count($parts) === 1) {
            return;
        }

        $value = $parts[1];

        while (preg_match('~^".*"$~', $value) === 1) {
            $value = substr($value, 1, -1);
        }

        $this->value = $value;
    }

    public function isLong(): bool
    {
        return $this->long;
    }

    public function getKey(): string
    {
        return $this->key;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }
}
