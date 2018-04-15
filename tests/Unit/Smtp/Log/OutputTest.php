<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Log;

use PeeHaa\MailGrab\Smtp\Log\Level;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PHPUnit\Framework\TestCase;

class OutputTest extends TestCase
{
    public function testReplaceNonPrintableCharactersReplacesTab()
    {
        $this->expectOutputRegex('~^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} \[INFO\] Foo\\\tBar~');

        (new Output(new Level(Level::ALL)))->info("Foo\tBar");
    }

    public function testReplaceNonPrintableCharactersReplacesLineFeed()
    {
        $this->expectOutputRegex('~^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} \[INFO\] Foo\\\nBar~');

        (new Output(new Level(Level::ALL)))->info("Foo\nBar");
    }

    public function testReplaceNonPrintableCharactersReplacesCarriageReturn()
    {
        $this->expectOutputRegex('~^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} \[INFO\] Foo\\\rBar~');

        (new Output(new Level(Level::ALL)))->info("Foo\rBar");
    }

    public function testDoesNotMeetLogLevel()
    {
        $this->expectOutputString('');

        (new Output(new Level(Level::SMTP_IN)))->info("Foo\rBar");
    }

    public function testInfoLogsWithoutContext()
    {
        $this->expectOutputRegex('~^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} \[INFO\] foobar~');

        (new Output(new Level(Level::ALL)))->info('foobar');
    }

    public function testInfoLogsWithContext()
    {
        $this->expectOutputRegex('~^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} \[INFO\] foobar' . PHP_EOL . '{"foo":"bar"}~');

        (new Output(new Level(Level::ALL)))->info('foobar', ['foo' => 'bar']);
    }

    public function testMessageInLogsWithoutContext()
    {
        $this->expectOutputRegex('~^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} \[INCOMING\] foobar~');

        (new Output(new Level(Level::ALL)))->messageIn('foobar');
    }

    public function testMessageInLogsWithContext()
    {
        $this->expectOutputRegex('~^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} \[INCOMING\] foobar' . PHP_EOL . '{"foo":"bar"}~');

        (new Output(new Level(Level::ALL)))->messageIn('foobar', ['foo' => 'bar']);
    }

    public function testSmtpInLogsWithoutContext()
    {
        $this->expectOutputRegex('~^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} \[SMTP_IN\] foobar~');

        (new Output(new Level(Level::ALL)))->smtpIn('foobar');
    }

    public function testSmtpInLogsWithContext()
    {
        $this->expectOutputRegex('~^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} \[SMTP_IN\] foobar' . PHP_EOL . '{"foo":"bar"}~');

        (new Output(new Level(Level::ALL)))->smtpIn('foobar', ['foo' => 'bar']);
    }

    public function testSmtpOutLogsWithoutContext()
    {
        $this->expectOutputRegex('~^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} \[SMTP_OUT\] foobar~');

        (new Output(new Level(Level::ALL)))->smtpOut('foobar');
    }

    public function testSmtpOutLogsWithContext()
    {
        $this->expectOutputRegex('~^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} \[SMTP_OUT\] foobar' . PHP_EOL . '{"foo":"bar"}~');

        (new Output(new Level(Level::ALL)))->smtpOut('foobar', ['foo' => 'bar']);
    }

    public function testDebugLogsWithoutContext()
    {
        $this->expectOutputRegex('~^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} \[DEBUG\] foobar~');

        (new Output(new Level(Level::ALL)))->debug('foobar');
    }

    public function testDebugLogsWithContext()
    {
        $this->expectOutputRegex('~^\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2} \[DEBUG\] foobar' . PHP_EOL . '{"foo":"bar"}~');

        (new Output(new Level(Level::ALL)))->debug('foobar', ['foo' => 'bar']);
    }
}
