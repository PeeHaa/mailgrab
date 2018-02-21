<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Examples;

require_once __DIR__ . '/../vendor/autoload.php';

$transport = (new \Swift_SmtpTransport('localhost', 8025));

$mailer = new \Swift_Mailer($transport);
$logger = new \Swift_Plugins_Loggers_ArrayLogger();
$mailer->registerPlugin(new \Swift_Plugins_LoggerPlugin($logger));

$message = (new \Swift_Message('Wonderful Subject'))
    ->setFrom(['john@doe.com' => 'John Doe'])
    ->setTo(['receiver@domain.org', 'other@domain.org' => 'A name'])
    ->setBody('Here is the message itself')
;

if(!$mailer->send($message)) {
    echo 'Message could not be sent.';
    echo 'Mailer Error: ' . $logger->dump();;
} else {
    echo 'Message has been sent';
}
