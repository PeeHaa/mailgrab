<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Cli\Output;

use PeeHaa\MailGrab\Cli\Command;
use PeeHaa\MailGrab\Cli\Option;
use PeeHaa\MailGrab\Cli\Output\Help;
use PHPUnit\Framework\TestCase;

class HelpTest extends TestCase
{
    public function testRenderRendersCommandDescription()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('Test command', $lines[0]);
    }

    public function testRenderRendersExampleWithoutPath()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertRegExp('~^binaryname ~', $lines[2]);
    }

    public function testRenderRendersExample()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('binaryname [--long] [-short]', $lines[2]);
    }

    public function testRenderRendersOnlyLongExampleWhenBothLongAndShortAreAvailable()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
            (new Option('Combi option'))->setLong('combi')->setShort('c'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('binaryname [--long] [-short] [--combi]', $lines[2]);
    }

    public function testRenderRendersExampleWithMandatoryLongOption()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long')->makeRequired(),
            (new Option('Short option'))->setShort('short'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('binaryname --long [-short]', $lines[2]);
    }

    public function testRenderRendersExampleWithMandatoryShortOption()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short')->makeRequired(),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('binaryname [--long] -short', $lines[2]);
    }

    public function testRenderRendersExampleWithShortOptionWithInput()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short')->input('KEY'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('binaryname [--long] [-short=KEY]', $lines[2]);
    }

    public function testRenderRendersExampleWithLongOptionWithInput()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long')->input('KEY'),
            (new Option('Short option'))->setShort('short'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('binaryname [--long=KEY] [-short]', $lines[2]);
    }

    public function testRenderRendersExampleWithShortOptionWithInputWithDefaultValue()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short')->input('KEY')->setDefault('def'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('binaryname [--long] [-short=def]', $lines[2]);
    }

    public function testRenderRendersExampleWithLongOptionWithInputWithDefaultValue()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long')->input('KEY')->setDefault('def'),
            (new Option('Short option'))->setShort('short'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('binaryname [--long=def] [-short]', $lines[2]);
    }

    public function testRenderOnlyRendersUsageWhenOptionsAreDefined()
    {
        $command = new Command('Test command', ...[]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertCount(5, $lines);
    }

    public function testRenderUsageTitleCorrectlyWhenOptionsAreAvailable()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('Usage:', $lines[4]);
    }

    public function testRenderUsageOfShortOptionsCorrectly()
    {
        $command = new Command('Test command', ...[
            (new Option('Short option'))->setShort('short'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('  -short            Short option', $lines[5]);
    }

    public function testRenderUsageOfLongOptionsCorrectly()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('  --long            Long option', $lines[5]);
    }

    public function testRenderUsageOfLongAndShortOptionsCorrectly()
    {
        $command = new Command('Test command', ...[
            (new Option('Combi option'))->setLong('combi')->setShort('c'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('  -c, --combi       Combi option', $lines[5]);
    }

    public function testRenderUsageOfShortOptionsWithInputCorrectly()
    {
        $command = new Command('Test command', ...[
            (new Option('Short option'))->setShort('short')->input('KEY'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('  -short=KEY        Short option', $lines[5]);
    }

    public function testRenderUsageOfLongOptionsWithInputCorrectly()
    {
        $command = new Command('Test command', ...[
            (new Option('Long option'))->setLong('long')->input('KEY'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('  --long=KEY        Long option', $lines[5]);
    }

    public function testRenderUsageOfLongAndShortOptionsWithInputCorrectly()
    {
        $command = new Command('Test command', ...[
            (new Option('Combi option'))->setLong('combi')->setShort('c')->input('KEY'),
        ]);

        $lines = explode(PHP_EOL, (new Help())->render('cli/binaryname', $command));

        $this->assertSame('  -c, --combi=KEY   Combi option', $lines[5]);
    }
}
