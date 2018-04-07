<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit;

use PeeHaa\ArrayPath\ArrayPath;
use PeeHaa\ArrayPath\NotFoundException;
use PeeHaa\MailGrab\Configuration;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ConfigurationTest extends TestCase
{
    /** @var MockObject|Configuration */
    private $configuration;

    public function setUp()
    {
        $this->configuration = new Configuration(new ArrayPath(), [
            'level1-1' => 'value1-1',
            'level1-2' => [
                'level2-1' => 'value2-1',
                'level2-2' => 'value2-2',
            ],
        ]);
    }

    public function testExistsReturnsTrueWhenItemExists()
    {
        $this->assertTrue($this->configuration->exists('level1-1'));
    }

    public function testExistsReturnsTrueWhenItemExistsDeeperLevel()
    {
        $this->assertTrue($this->configuration->exists('level1-2.level2-2'));
    }

    public function testExistsReturnsFalseWhenItemDoesNotExist()
    {
        $this->assertFalse($this->configuration->exists('level1-3'));
    }

    public function testGetReturnsValueForSingleLevel()
    {
        $this->assertSame('value1-1', $this->configuration->get('level1-1'));
    }

    public function testGetReturnsValueForDeeperLevel()
    {
        $this->assertSame('value2-2', $this->configuration->get('level1-2.level2-2'));
    }

    public function testGetThrowsUpWhenKeyDoesNotExist()
    {
        $this->expectException(NotFoundException::class);

        $this->configuration->get('level1-3');
    }

    public function testSetSetsValueCorrectly()
    {
        $this->configuration->set('level1-3.foo', 'bar');

        $this->assertSame('bar', $this->configuration->get('level1-3.foo'));
    }
}
