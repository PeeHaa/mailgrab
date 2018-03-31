<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Cli\Output;

use PeeHaa\MailGrab\Cli\Command;
use PeeHaa\MailGrab\Cli\Option;

class Help
{
    public function render(string $binary, Command $command): string
    {
        $output = '';

        $output .= $command->getDescription() . PHP_EOL . PHP_EOL;
        $output .= basename($binary) . $this->renderUsage($command) . PHP_EOL . PHP_EOL;
        $output .= 'Usage:' . PHP_EOL;

        /** @var Option $option */
        foreach ($command->getOptions() as $option) {
            $output .= $this->renderOption($option);
        }

        return $output;
    }

    private function renderUsage(Command $command): string
    {
        $usage = '';

        /** @var Option $option */
        foreach ($command->getOptions() as $option) {
            if ($option->getShort() === 'h') {
                continue;
            }

            if ($option->hasLong()) {
                $usage .= $this->renderLongUsage($option);

                continue;
            }

            if ($option->hasShort()) {
                $usage .= $this->renderShortUsage($option);

                continue;
            }
        }

        return $usage;
    }

    private function renderLongUsage(Option $option): string
    {
        $usage = '--' . $option->getLong();

        if ($option->hasInput()) {
            $usage .= '=' . ($option->hasDefault() ? $option->getDefault() : $option->getInput());
        }

        if (!$option->isRequired()) {
            $usage = sprintf('[%s]', $usage);
        }

        return ' ' . $usage;
    }

    private function renderShortUsage(Option $option): string
    {
        $usage = '-' . $option->getShort();

        if ($option->hasInput()) {
            $usage .= '=' . ($option->hasDefault() ? $option->getDefault() : $option->getInput());
        }

        if (!$option->isRequired()) {
            $usage = sprintf('[%s]', $usage);
        }

        return ' ' . $usage;
    }

    private function renderOption(Option $option): string
    {
        $title = '  ';

        if ($option->hasShort()) {
            $title .= $this->renderShortOption($option);
        }

        if ($option->hasLong()) {
            if (trim($title)) $title .= ', ';

            $title .= $this->renderLongOption($option);
        }

        return str_pad($title, 20) . $option->getDescription() . PHP_EOL;
    }

    private function renderShortOption(Option $option): string
    {
        $optionDefinition = '-' . $option->getShort();

        if ($option->hasInput()) {
            $optionDefinition .= '=' . $option->getInput();
        }

        return $optionDefinition;
    }

    private function renderLongOption(Option $option): string
    {
        $optionDefinition = '--' . $option->getLong();

        if ($option->hasInput()) {
            $optionDefinition .= '=' . $option->getInput();
        }

        return $optionDefinition;
    }
}
