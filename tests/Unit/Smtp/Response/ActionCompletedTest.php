<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Response;

use PeeHaa\MailGrab\Smtp\Response\ActionCompleted;
use PHPUnit\Framework\TestCase;

class ActionCompletedTest extends TestCase
{
    public function testConstructorSetsDataCorrectly()
    {
        $this->assertSame("250 foobar\r\n", (string) new ActionCompleted('foobar'));
    }
}
