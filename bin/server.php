<?php declare(strict_types=1);

namespace PeeHaa\MailGrab;

use PeeHaa\MailGrab\Smtp\Command\Factory;
use PeeHaa\MailGrab\Smtp\Log\Level;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PeeHaa\MailGrab\Smtp\Server;

require_once __DIR__ . '/../vendor/autoload.php';

(new Server(new Factory(), new Output(new Level(Level::ALL))))->run();
