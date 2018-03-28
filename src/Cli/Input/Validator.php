<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Cli\Input;

use PeeHaa\MailGrab\Cli\Command;

class Validator
{
    private $command;

    private $arguments;

    private $errors = [];

    public function __construct(Command $command, Argument ...$arguments)
    {
        $this->command   = $command;
        $this->arguments = $arguments;
    }

    public function validate(): void
    {
        foreach ($this->arguments as $argument) {
            if (!$argument->isLong() && !$this->command->isShortOption($argument->getKey())) {
                $this->errors[] = sprintf('Unrecognized option: %s', $argument->getKey());
            }

            if ($argument->isLong() && !$this->command->isLongOption($argument->getKey())) {
                $this->errors[] = sprintf('Unrecognized option: %s', $argument->getKey());
            }
        }
    }

    public function isValid(): bool
    {
        return count($this->errors) === 0;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
