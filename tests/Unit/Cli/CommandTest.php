<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Cli;

use PeeHaa\MailGrab\Cli\Command;
use PeeHaa\MailGrab\Cli\Input\Argument;
use PeeHaa\MailGrab\Cli\Option;
use PHPUnit\Framework\TestCase;

class CommandTest extends TestCase
{
    public function testGetDescription()
    {
        $this->assertSame('My description', (new Command('My description'))->getDescription());
    }

    public function testGetOptions()
    {
        $options = [
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ];

        $command = new Command('Test command', ...$options);

        $this->assertSame($options, $command->getOptions());
    }

    public function testIsShortOptionReturnsTrueWhenAvailable()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ]);

        $this->assertTrue($command->isShortOption('short'));
    }

    public function testIsShortOptionReturnsFalseWhenNotAvailable()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ]);

        $this->assertFalse($command->isShortOption('nonshort'));
    }

    public function testIsLongOptionReturnsTrueWhenAvailable()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ]);

        $this->assertTrue($command->isLongOption('long'));
    }

    public function testIsLongOptionReturnsFalseWhenNotAvailable()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ]);

        $this->assertFalse($command->isLongOption('nonlong'));
    }

    public function testIsHelpReturnsTrueWhenLongArgumentIsFound()
    {
        $command = new Command('Test command', ...[
            (new Option('Help option'))->setShort('h')->setLong('help'),
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ]);

        $arguments = [
            new Argument('--long'),
            new Argument('--help'),
        ];

        $this->assertTrue($command->isHelp(...$arguments));
    }

    public function testIsHelpReturnsFalseWhenHelpArgumentIsNotFound()
    {
        $command = new Command('Test command', ...[
            (new Option('Help option'))->setShort('h')->setLong('help'),
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ]);

        $arguments = [
            new Argument('--long'),
        ];

        $this->assertFalse($command->isHelp(...$arguments));
    }

    public function testGetConfigurationReturnsDefaultWebPort()
    {
        $command = new Command('Test command', ...[
            (new Option('Help option'))->setShort('h')->setLong('help'),
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ]);

        $arguments = [
            new Argument('--long'),
        ];

        $this->assertSame(9000, $command->getConfiguration(...$arguments)['port']);
    }

    public function testGetConfigurationReturnsDefaultSmtpPort()
    {
        $command = new Command('Test command', ...[
            (new Option('Help option'))->setShort('h')->setLong('help'),
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ]);

        $arguments = [
            new Argument('--long'),
        ];

        $this->assertSame(9025, $command->getConfiguration(...$arguments)['smtpport']);
    }

    public function testGetConfigurationSetsWebPort()
    {
        $command = new Command('Test command', ...[
            (new Option('Port option'))->setLong('port')->input('PORT'),
        ]);

        $arguments = [
            new Argument('--port=12'),
        ];

        $this->assertSame(12, $command->getConfiguration(...$arguments)['port']);
    }

    public function testGetConfigurationSetsSmtpPort()
    {
        $command = new Command('Test command', ...[
            (new Option('Port option'))->setLong('smtpport')->input('PORT'),
        ]);

        $arguments = [
            new Argument('--smtpport=25'),
        ];

        $this->assertSame(25, $command->getConfiguration(...$arguments)['smtpport']);
    }
}
