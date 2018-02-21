<?php declare(strict_types=1);

namespace PeeHaa\MailGrab;

use PeeHaa\MailGrab\Command\Factory;
use PeeHaa\MailGrab\Log\Level;
use PeeHaa\MailGrab\Log\Output;

require_once __DIR__ . '/../vendor/autoload.php';

(new Server(new Factory(), new Output(new Level(Level::ALL))))->run();
