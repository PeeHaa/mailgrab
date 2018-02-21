<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp\Command;

interface Command
{
    public static function isValid(string $line): bool;
}
