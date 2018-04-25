<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp;

use PeeHaa\MailGrab\Smtp\Command\Ehlo;
use PeeHaa\MailGrab\Smtp\Command\EndBody;
use PeeHaa\MailGrab\Smtp\Command\Factory;
use PeeHaa\MailGrab\Smtp\Command\Helo;
use PeeHaa\MailGrab\Smtp\Command\MailFrom;
use PeeHaa\MailGrab\Smtp\Command\Quit;
use PeeHaa\MailGrab\Smtp\Command\RcptTo;
use PeeHaa\MailGrab\Smtp\Command\Rset;
use PeeHaa\MailGrab\Smtp\Command\StartBody;
use PeeHaa\MailGrab\Smtp\Command\StartData;
use PeeHaa\MailGrab\Smtp\Command\StartHeader;
use PeeHaa\MailGrab\Smtp\Command\Unfold;
use PeeHaa\MailGrab\Smtp\Log\Output;
use PeeHaa\MailGrab\Smtp\Socket\ServerSocket;
use PeeHaa\MailGrab\Smtp\Transaction;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    public function testIsHandlingBodyWhenFalse()
    {
        $transaction = new Transaction(
            $this->createMock(Output::class),
            $this->createMock(ServerSocket::class),
            $this->createMock(Factory::class),
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $this->assertFalse($transaction->isHandlingBody());
    }

    public function testProcessLineStopsProcessingAfterQuitCommand()
    {
        $logger = $this->createMock(Output::class);

        $logger
            ->method('debug')
            ->willReturnCallback(function($message) {
                static $index = 0;

                if ($index === 3) {
                    $this->assertSame('Client status already set to quit. Not processing new lines.', $message);
                }

                $index++;
            })
        ;

        $socket = $this->createMock(ServerSocket::class);

        $socket
            ->expects($this->at(0))
            ->method('__call')
            ->with('write', ["221 Goodbye.\r\n"]);

        $commandFactory = $this->createMock(Factory::class);

        $commandFactory
            ->expects($this->at(0))
            ->method('build')
            ->willReturn(new Quit(''))
        ;

        $commandFactory
            ->expects($this->at(0))
            ->method('build')
            ->willReturn(new Helo('HELO example.com'))
        ;

        $transaction = new Transaction(
            $logger,
            $socket,
            $commandFactory,
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $transaction->processLine('QUIT');
        $transaction->processLine('NEW LINE');
    }

    public function testProcessLineThrowsSyntaxErrorOnUnknownCommand()
    {
        $socket = $this->createMock(ServerSocket::class);

        $socket
            ->expects($this->once())
            ->method('__call')
            ->with('write', ["500 \r\n"])
        ;

        $commandFactory = $this->createMock(Factory::class);

        $commandFactory
            ->expects($this->once())
            ->method('build')
            ->willThrowException(new \Exception())
        ;

        $transaction = new Transaction(
            $this->createMock(Output::class),
            $socket,
            $commandFactory,
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $transaction->processLine('UNKNOWN COMMAND');
    }

    public function testProcessLineProcessesHeloCommand()
    {
        $socket = $this->createMock(ServerSocket::class);

        $socket
            ->expects($this->at(0))
            ->method('__call')
            ->with('getRemoteAddress', [])
            ->willReturn('127.0.0.1:9999')
        ;

        $socket
            ->expects($this->at(1))
            ->method('__call')
            ->with('write', ["250 hello example.com @ 127.0.0.1:9999\r\n"])
        ;

        $commandFactory = $this->createMock(Factory::class);

        $commandFactory
            ->expects($this->once())
            ->method('build')
            ->willReturn(new Helo('HELO example.com'))
        ;

        $transaction = new Transaction(
            $this->createMock(Output::class),
            $socket,
            $commandFactory,
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $transaction->processLine('HELO');
    }

    public function testProcessLineProcessesEhloCommand()
    {
        $socket = $this->createMock(ServerSocket::class);

        $socket
            ->expects($this->at(0))
            ->method('__call')
            ->with('getRemoteAddress', [])
            ->willReturn('127.0.0.1:9999')
        ;

        $socket
            ->expects($this->at(1))
            ->method('__call')
            ->with('write', ["250 hello example.com @ 127.0.0.1:9999\r\n"])
        ;

        $commandFactory = $this->createMock(Factory::class);

        $commandFactory
            ->expects($this->once())
            ->method('build')
            ->willReturn(new Ehlo('EHLO example.com'))
        ;

        $transaction = new Transaction(
            $this->createMock(Output::class),
            $socket,
            $commandFactory,
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $transaction->processLine('EHLO');
    }

    public function testProcessLineProcessesResetCommand()
    {
        $socket = $this->createMock(ServerSocket::class);

        $commandFactory = $this->createMock(Factory::class);

        $commandFactory
            ->expects($this->once())
            ->method('build')
            ->willReturn(new Rset(''))
        ;

        $transaction = new Transaction(
            $this->createMock(Output::class),
            $socket,
            $commandFactory,
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $transaction->processLine('RSET');
    }

    public function testProcessLineProcessesQuitCommand()
    {
        $socket = $this->createMock(ServerSocket::class);

        $socket
            ->expects($this->at(0))
            ->method('__call')
            ->with('write', ["221 Goodbye.\r\n"]);

        $commandFactory = $this->createMock(Factory::class);

        $commandFactory
            ->expects($this->once())
            ->method('build')
            ->willReturn(new Quit(''))
        ;

        $transaction = new Transaction(
            $this->createMock(Output::class),
            $socket,
            $commandFactory,
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $transaction->processLine('QUIT');
    }

    public function testProcessLineProcessesQuitCommandAndClosesConection()
    {
        $socket = $this->createMock(ServerSocket::class);

        $socket
            ->expects($this->at(1))
            ->method('__call')
            ->with('close', [])
        ;

        $commandFactory = $this->createMock(Factory::class);

        $commandFactory
            ->expects($this->once())
            ->method('build')
            ->willReturn(new Quit(''));

        $transaction = new Transaction(
            $this->createMock(Output::class),
            $socket,
            $commandFactory,
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $transaction->processLine('QUIT');
    }

    public function testProcessLineProcessesMailFromCommand()
    {
        $socket = $this->createMock(ServerSocket::class);

        $socket
            ->expects($this->once())
            ->method('__call')
            ->with('write', ["250 MAIL OK\r\n"])
        ;

        $commandFactory = $this->createMock(Factory::class);

        $commandFactory
            ->expects($this->once())
            ->method('build')
            ->willReturn(new MailFrom('MAIL FROM: <example@example.com>'))
        ;

        $transaction = new Transaction(
            $this->createMock(Output::class),
            $socket,
            $commandFactory,
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $transaction->processLine('MAIL FROM');
    }

    public function testProcessLineProcessesRcptToCommand()
    {
        $socket = $this->createMock(ServerSocket::class);

        $socket
            ->expects($this->once())
            ->method('__call')
            ->with('write', ["250 Accepted\r\n"])
        ;

        $commandFactory = $this->createMock(Factory::class);

        $commandFactory
            ->expects($this->once())
            ->method('build')
            ->willReturn(new RcptTo('RCPT TO: <example@example.com>'))
        ;

        $transaction = new Transaction(
            $this->createMock(Output::class),
            $socket,
            $commandFactory,
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $transaction->processLine('RCPT TO');
    }

    public function testProcessLineProcessesStartDataCommand()
    {
        $socket = $this->createMock(ServerSocket::class);

        $socket
            ->expects($this->once())
            ->method('__call')
            ->with('write', ["354 Enter message, end with CRLF . CRLF\r\n"])
        ;

        $commandFactory = $this->createMock(Factory::class);

        $commandFactory
            ->expects($this->once())
            ->method('build')
            ->willReturn(new StartData(''))
        ;

        $transaction = new Transaction(
            $this->createMock(Output::class),
            $socket,
            $commandFactory,
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $transaction->processLine('START DATA');
    }

    public function testProcessLineProcessesStartHeaderCommand()
    {
        $socket = $this->createMock(ServerSocket::class);

        $commandFactory = $this->createMock(Factory::class);

        $commandFactory
            ->expects($this->once())
            ->method('build')
            ->willReturn(new StartHeader('Key: Value'))
        ;

        $transaction = new Transaction(
            $this->createMock(Output::class),
            $socket,
            $commandFactory,
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $transaction->processLine('START HEADER');
    }

    public function testProcessLineProcessesUnfoldCommand()
    {
        $socket = $this->createMock(ServerSocket::class);

        $commandFactory = $this->createMock(Factory::class);

        $commandFactory
            ->expects($this->at(0))
            ->method('build')
            ->willReturn(new StartHeader('Key: Value'))
        ;

        $commandFactory
            ->expects($this->at(1))
            ->method('build')
            ->willReturn(new Unfold(' More value'))
        ;

        $transaction = new Transaction(
            $this->createMock(Output::class),
            $socket,
            $commandFactory,
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $transaction->processLine('START HEADER');
        $transaction->processLine('UNFOLD');
    }

    public function testProcessLineProcessesStartBodyCommand()
    {
        $socket = $this->createMock(ServerSocket::class);

        $commandFactory = $this->createMock(Factory::class);

        $commandFactory
            ->expects($this->once())
            ->method('build')
            ->willReturn(new StartBody(''))
        ;

        $transaction = new Transaction(
            $this->createMock(Output::class),
            $socket,
            $commandFactory,
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $transaction->processLine('START BODY');

        $this->assertTrue($transaction->isHandlingBody());
    }

    public function testProcessLineProcessesAndBodyCommand()
    {
        $socket = $this->createMock(ServerSocket::class);

        $socket
            ->expects($this->once())
            ->method('__call')
            ->with('write', ["250 OK\r\n"])
        ;

        $commandFactory = $this->createMock(Factory::class);

        $commandFactory
            ->expects($this->once())
            ->method('build')
            ->willReturn(new EndBody('.'))
        ;

        $transaction = new Transaction(
            $this->createMock(Output::class),
            $socket,
            $commandFactory,
            // phpcs:disable
            function() {}
            // phpcs:enable
        );

        $transaction->processLine('END BODY');
    }
}
