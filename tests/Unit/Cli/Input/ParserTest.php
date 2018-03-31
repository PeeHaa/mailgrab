<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Cli\Input;

use PeeHaa\MailGrab\Cli\Input\Argument;
use PeeHaa\MailGrab\Cli\Input\Parser;
use PHPUnit\Framework\TestCase;

class ParserTest extends TestCase
{
    public function testParseRemovesCommand()
    {
        $arguments = (new Parser())->parse(['command', '--flag']);

        $this->assertCount(1, $arguments);
        /** @var Argument[] $arguments */
        $this->assertSame('flag', $arguments[0]->getKey());
    }

    public function testParseParsesArguments()
    {
        $arguments = (new Parser())->parse(['command', '--flag1', '--flag2']);

        $this->assertCount(2, $arguments);
        /** @var Argument[] $arguments */
        $this->assertSame('flag1', $arguments[0]->getKey());
        $this->assertSame('flag2', $arguments[1]->getKey());
    }
}
