<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Cli\Input;

use PeeHaa\MailGrab\Cli\Command;
use PeeHaa\MailGrab\Cli\Input\Argument;
use PeeHaa\MailGrab\Cli\Input\Validator;
use PeeHaa\MailGrab\Cli\Option;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /** @var Argument[] */
    private $arguments;

    public function setUp()
    {
        $this->arguments = [
            new Argument('--long'),
            new Argument('-short'),
        ];
    }

    public function testIsValidReturnsTrueOnAllValidArguments()
    {
        $validator = new Validator(new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ]), ...$this->arguments);

        $validator->validate();

        $this->assertTrue($validator->isValid());
    }

    public function testIsValidReturnsFalseWhenAnUnrecognizedLongArgumentIsPassed()
    {
        $validator = new Validator(new Command('Test command', ...[
            (new Option('Short option'))->setShort('short'),
        ]), ...$this->arguments);

        $validator->validate();

        $this->assertFalse($validator->isValid());
    }

    public function testIsValidReturnsFalseWhenAnUnrecognizedShortArgumentIsPassed()
    {
        $validator = new Validator(new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
        ]), ...$this->arguments);

        $validator->validate();

        $this->assertFalse($validator->isValid());
    }

    public function testIsValidReturnsFalseWhenUnrecognizedShortAndLongArgumentsArePassed()
    {
        $validator = new Validator(new Command('Test command', ...[]), ...$this->arguments);

        $validator->validate();

        $this->assertFalse($validator->isValid());
    }

    public function testGetErrorsReturnsEmptyArrayOnAllValidArguments()
    {
        $validator = new Validator(new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
            (new Option('Short option'))->setShort('short'),
        ]), ...$this->arguments);

        $validator->validate();

        $this->assertCount(0, $validator->getErrors());
    }

    public function testGetErrorsReturnsCorrectMessageWhenAnUnrecognizedLongArgumentIsPassed()
    {
        $validator = new Validator(new Command('Test command', ...[
            (new Option('Short option'))->setShort('short'),
        ]), ...$this->arguments);

        $validator->validate();

        $errors = $validator->getErrors();

        $this->assertCount(1, $errors);
        $this->assertSame('Unrecognized option: long', $errors[0]);
    }

    public function testGetErrorsReturnsCorrectMessageWhenAnUnrecognizedShortArgumentIsPassed()
    {
        $validator = new Validator(new Command('Test command', ...[
            (new Option('Long option'))->setLong('long'),
        ]), ...$this->arguments);

        $validator->validate();

        $errors = $validator->getErrors();

        $this->assertCount(1, $errors);
        $this->assertSame('Unrecognized option: short', $errors[0]);
    }

    public function testGetErrorsReturnsCorrectMessagesWhenUnrecognizedShortAndLongArgumentsArePassed()
    {
        $validator = new Validator(new Command('Test command', ...[]), ...$this->arguments);

        $validator->validate();
        $errors = $validator->getErrors();

        $this->assertCount(2, $errors);
        $this->assertSame('Unrecognized option: long', $errors[0]);
        $this->assertSame('Unrecognized option: short', $errors[1]);
    }
}
