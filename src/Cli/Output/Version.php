<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Cli\Output;

class Version
{
    public function render(): string
    {
        if (file_exists(__DIR__ . '/../../../.git')) {
            $version = shell_exec('git describe --tags');
        } else {
            $version = file_get_contents(__DIR__ . '/../../../info/build.version');
        }

        return substr(trim($version), 1);
    }
}
