<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Command;

interface Command
{
    public static function isValid(string $line): bool;
}
