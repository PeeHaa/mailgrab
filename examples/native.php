<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Examples;

require_once __DIR__ . '/../vendor/autoload.php';

ini_set('SMTP', 'localhost');
ini_set('smtp_port', '9025');
ini_set('sendmail_from', 'peehaa@example.com');

mail('user@example.com', 'Native mail()', 'My message');
