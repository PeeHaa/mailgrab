<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Smtp\Command;

use PeeHaa\MailGrab\Smtp\Command\Unfold;
use PHPUnit\Framework\TestCase;

class UnfoldTest extends TestCase
{
    public function testIsValidReturnsTrueOnValidHeaderChunk()
    {
        $this->assertTrue(Unfold::isValid(' foobar'));
    }

    public function testIsValidReturnsFalseOnMissingLeadingWhitespace()
    {
        $this->assertFalse(Unfold::isValid('foobar'));
    }

    public function testGetChunk()
    {
        $this->assertSame('foobar', (new Unfold(' foobar'))->getChunk());
    }
}
