<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Bin;

use PeeHaa\MailGrab\Cli\Command;
use PeeHaa\MailGrab\Cli\Input\Parser;
use PeeHaa\MailGrab\Cli\Input\Validator;
use PeeHaa\MailGrab\Cli\Option;

require_once __DIR__ . '/../vendor/autoload.php';

$command = new Command('Starts the MailGrab SMTP catch-all SMTP server', ...[
    (new Option('Displays this help information'))->setShort('h')->setLong('help'),
    (new Option('Sets the port for the web interface'))->setLong('port')->input('PORT'),
    (new Option('Sets the port for the SMTP server'))->setLong('smtpport')->input('PORT'),
]);

$arguments = (new Parser())->parse($argv);

$validator = new Validator($command, ...$arguments);
$validator->validate();

if (!$validator->isValid()) {
    echo implode(PHP_EOL, $validator->getErrors());

    exit(1);
}

if ($command->isHelp(...$arguments)) {
    echo $command->getDescription() . PHP_EOL . PHP_EOL;

    echo 'Usage:' . PHP_EOL;

    /** @var Option $option */
    foreach ($command->getOptions() as $option) {
        $title = '  ';

        if ($option->hasShort()) {
            $title .= '-' . $option->getShort();

            if ($option->hasInput()) {
                $title .= '=' . $option->getInput();
            }
        }

        if ($option->hasLong()) {
            if (trim($title)) $title .= ', ';

            $title .= '--' . $option->getLong();

            if ($option->hasInput()) {
                $title .= '=' . $option->getInput();
            }
        }

        echo str_pad($title, 20) . $option->getDescription() . PHP_EOL;
    }
}
