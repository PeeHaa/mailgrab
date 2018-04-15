<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Command;

use PeeHaa\MailGrab\Smtp\Command\Command;
use PeeHaa\MailGrab\Smtp\Command\Ehlo;
use PeeHaa\MailGrab\Smtp\Command\Factory;
use PeeHaa\MailGrab\Smtp\TransactionStatus;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testBuildThrowsOnUnknownCommand()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Syntax error, command unrecognised');

        $transactionStatus = new class(PHP_INT_MAX) extends TransactionStatus {
            public const UNKNOWN = PHP_INT_MAX;
        };

        (new Factory())->build($transactionStatus, 'foobar');
    }

    public function testBuildThrowsOnUnknownCommandWhichIsNotAllowedInCurrentState()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Syntax error, command unrecognised');

        $transactionStatus = new TransactionStatus(TransactionStatus::FROM);

        (new Factory())->build($transactionStatus, 'EHLO foobar');
    }

    public function testBuildReturnsCommandWhenValid()
    {
        $transactionStatus = new TransactionStatus(TransactionStatus::SEND_BANNER);

        $command = (new Factory())->build($transactionStatus, 'EHLO foobar');

        $this->assertInstanceOf(Command::class, $command);
    }

    public function testBuildReturnsEhloCommandWhenValid()
    {
        $transactionStatus = new TransactionStatus(TransactionStatus::SEND_BANNER);

        $command = (new Factory())->build($transactionStatus, 'EHLO foobar');

        $this->assertInstanceOf(Ehlo::class, $command);
    }
}
