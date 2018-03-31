<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Cli;

use PeeHaa\MailGrab\Cli\Option;
use PHPUnit\Framework\TestCase;

class OptionTest extends TestCase
{
    public function testDescriptionIsCorrectlySet()
    {
        $this->assertSame('My description', (new Option('My description'))->getDescription());
    }

    public function testHasShortReturnsTrueWhenShortOption()
    {
        $option = (new Option('My description'))->setShort('s');

        $this->assertTrue($option->hasShort());
    }

    public function testHasShortReturnsFalseWhenNotShortOption()
    {
        $option = (new Option('My description'));

        $this->assertFalse($option->hasShort());
    }

    public function testGetShortReturnsFlagWhenSet()
    {
        $option = (new Option('My description'))->setShort('s');

        $this->assertSame('s', $option->getShort());
    }

    public function testGetShortReturnsNullWhenNotSet()
    {
        $option = (new Option('My description'));

        $this->assertNull($option->getShort());
    }

    public function testHasLongReturnsTrueWhenLongOption()
    {
        $option = (new Option('My description'))->setLong('long');

        $this->assertTrue($option->hasLong());
    }

    public function testHasLongReturnsFalseWhenNotLongOption()
    {
        $option = (new Option('My description'));

        $this->assertFalse($option->hasLong());
    }

    public function testGetLongReturnsFlagWhenSet()
    {
        $option = (new Option('My description'))->setLong('long');

        $this->assertSame('long', $option->getLong());
    }

    public function testGetLongReturnsNullWhenNotSet()
    {
        $option = (new Option('My description'));

        $this->assertNull($option->getLong());
    }

    public function testIsRequiredReturnsTrueWhenRequired()
    {
        $option = (new Option('My description'))->makeRequired();

        $this->assertTrue($option->isRequired());
    }

    public function testIsRequiredReturnsFalseWhenNotRequired()
    {
        $option = (new Option('My description'));

        $this->assertFalse($option->isRequired());
    }

    public function testHasDefaultReturnsFalseWhenNoDefaultValueIsAvailable()
    {
        $option = (new Option('My description'));

        $this->assertFalse($option->hasDefault());
    }

    public function testHasDefaultReturnsTrueWhenDefaultValueIsAvailable()
    {
        $option = (new Option('My description'))->setDefault('data');

        $this->assertTrue($option->hasDefault());
    }

    public function testGetDefaultReturnsDefaultWhenSet()
    {
        $option = (new Option('My description'))->setDefault('data');

        $this->assertSame('data', $option->getDefault());
    }

    public function testGetDefaultReturnsNullWhenNotSet()
    {
        $option = (new Option('My description'));

        $this->assertNull($option->getDefault());
    }

    public function testHasInputReturnsFalseWhenNotAnInputOption()
    {
        $option = (new Option('My description'));

        $this->assertFalse($option->hasInput());
    }

    public function testHasInputReturnsTrueWhenAnInputOption()
    {
        $option = (new Option('My description'))->input('PROMPT');

        $this->assertTrue($option->hasInput());
    }

    public function testGetInputReturnsKeyWhenAnInputOption()
    {
        $option = (new Option('My description'))->input('PROMPT');

        $this->assertSame('PROMPT', $option->getInput());
    }

    public function testGetInputReturnsNullWhenNotAnInputOption()
    {
        $option = (new Option('My description'));

        $this->assertNull($option->getInput());
    }
}
