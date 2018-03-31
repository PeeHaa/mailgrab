<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Bin;

use PeeHaa\MailGrab\Cli\Command;
use PeeHaa\MailGrab\Cli\Input\Parser;
use PeeHaa\MailGrab\Cli\Input\Validator;
use PeeHaa\MailGrab\Cli\Option;
use PeeHaa\MailGrab\Cli\Output\Help;

require_once __DIR__ . '/../vendor/autoload.php';

$command = new Command('Starts the MailGrab SMTP catch-all SMTP server', ...[
    (new Option('Displays this help information'))->setShort('h')->setLong('help'),
    (new Option('Sets the port for the web interface'))->setLong('port')->setDefault('9000')->input('PORT'),
    (new Option('Sets the port for the SMTP server'))->setLong('smtpport')->setDefault('9025')->input('PORT'),
]);

$arguments = (new Parser())->parse($argv);

$validator = new Validator($command, ...$arguments);
$validator->validate();

if (!$validator->isValid()) {
    echo implode(PHP_EOL, $validator->getErrors());

    exit(1);
}

if ($command->isHelp(...$arguments)) {
    echo (new Help())->render($argv[0], $command);

    exit;
}

var_dump($command->getConfiguration(...$arguments));
