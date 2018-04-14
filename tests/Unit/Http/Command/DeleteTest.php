<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Http\Command;

use PeeHaa\AmpWebsocketCommand\Failure;
use PeeHaa\AmpWebsocketCommand\Input;
use PeeHaa\AmpWebsocketCommand\Success;
use PeeHaa\MailGrab\Http\Command\Delete;
use PeeHaa\MailGrab\Http\Storage\Storage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class DeleteTest extends TestCase
{
    /** @var MockObject|Storage */
    private $storageMock;

    /** @var MockObject|Input $inputMock */
    private $inputMock;

    private const ID = '53d8d320-f546-406e-b17f-2938098cbb74';

    public function setUp()
    {
        $this->storageMock = $this->createMock(Storage::class);

        $this->inputMock = $this->getMockBuilder(Input::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    public function testExecuteReturnsFailureWhenMailIsNotAvailable()
    {
        $this->storageMock
            ->expects($this->once())
            ->method('has')
            ->willReturn(false)
            ->with(self::ID)
        ;

        $this->inputMock
            ->expects($this->once())
            ->method('getParameter')
            ->willReturn(self::ID)
            ->with('id')
        ;

        $delete = new Delete($this->storageMock);

        $result = wait($delete->execute($this->inputMock));

        $this->assertInstanceOf(Failure::class, $result);
    }

    public function testExecuteDeletesMails()
    {
        $this->storageMock
            ->expects($this->once())
            ->method('has')
            ->willReturn(true)
            ->with(self::ID)
        ;

        $this->storageMock
            ->expects($this->once())
            ->method('delete')
            ->with(self::ID)
        ;

        $this->inputMock
            ->expects($this->exactly(2))
            ->method('getParameter')
            ->willReturn(self::ID)
            ->with('id')
        ;

        $delete = new Delete($this->storageMock);

        $result = wait($delete->execute($this->inputMock));

        $this->assertInstanceOf(Success::class, $result);
    }

    public function testExecuteReturnsResponse()
    {
        $this->storageMock
            ->expects($this->once())
            ->method('has')
            ->willReturn(true)
            ->with(self::ID)
        ;

        $this->storageMock
            ->expects($this->once())
            ->method('delete')
            ->with(self::ID)
        ;

        $this->inputMock
            ->expects($this->exactly(2))
            ->method('getParameter')
            ->willReturn(self::ID)
            ->with('id')
        ;

        $delete = new Delete($this->storageMock);

        $result = wait($delete->execute($this->inputMock));

        $this->assertInstanceOf(Success::class, $result);
        $this->assertSame('{"success":true,"payload":{"command":"delete","id":"53d8d320-f546-406e-b17f-2938098cbb74"}}', (string) $result);
    }
}
