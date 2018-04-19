<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Cli\Input;

class Parser
{
    public function parse(array $arguments): array
    {
        $parsedArguments = [];

        array_shift($arguments);

        foreach ($arguments as $argument) {
            $parsedArguments[] = new Argument($argument);
        }

        return $parsedArguments;
    }
}
