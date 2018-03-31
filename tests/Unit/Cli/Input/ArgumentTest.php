<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Cli\Input;

use PeeHaa\MailGrab\Cli\Input\Argument;
use PHPUnit\Framework\TestCase;

class ArgumentTest extends TestCase
{
    public function testConstructorThrowsOnMalformedArgument()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Malformed argument');

        new Argument('---malformed');
    }

    public function testConstructorNormalizesCharacters()
    {
        $this->assertInstanceOf(Argument::class, new Argument('--FOOBAR'));
    }

    public function testIsLongCorrectlyReturnsOnLongArgument()
    {
        $this->assertTrue((new Argument('--foobar'))->isLong());
    }

    public function testIsLongCorrectlyReturnsOnShortArgument()
    {
        $this->assertFalse((new Argument('-foobar'))->isLong());
    }

    public function testGetKeyReturnsKeyOnLongArgument()
    {
        $this->assertSame('foobar', (new Argument('--foobar'))->getKey());
    }

    public function testGetKeyReturnsKeyOnShortArgument()
    {
        $this->assertSame('foobar', (new Argument('-foobar'))->getKey());
    }

    public function testGetKeyReturnsNormalizedKey()
    {
        $this->assertSame('foobar', (new Argument('--FOOBAR'))->getKey());
    }

    public function testGetKeyReturnsKeyWithoutInput()
    {
        $this->assertSame('foobar', (new Argument('--foobar=value'))->getKey());
    }

    public function testGetValueReturnsValueWhenAvailable()
    {
        $this->assertSame('value', (new Argument('--foobar=value'))->getValue());
    }

    public function testGetValueReturnsNullWhenNotAvailable()
    {
        $this->assertNull((new Argument('--foobar'))->getValue());
    }

    public function testGetValueUnwrapsSinglePairOfQuotes()
    {
        $this->assertSame('value', (new Argument('--foobar="value"'))->getValue());
    }

    public function testGetValueUnwrapsSeveralPairsOfQuotes()
    {
        $this->assertSame('value', (new Argument('--foobar="""value"""'))->getValue());
    }
}
