<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Http\Command;

use PeeHaa\AmpWebsocketCommand\Input;
use PeeHaa\AmpWebsocketCommand\Success;
use PeeHaa\MailGrab\Http\Command\RefreshMail;
use PeeHaa\MailGrab\Http\Entity\Mail;
use PeeHaa\MailGrab\Http\Storage\Storage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class RefreshMailTest extends TestCase
{
    /** @var MockObject|Storage */
    private $storageMock;

    /** @var MockObject|Input $inputMock */
    private $inputMock;

    /** @var MockObject|Mail */
    private $mailMock;

    private const ID = '53d8d320-f546-406e-b17f-2938098cbb74';

    public function setUp()
    {
        $this->storageMock = $this->createMock(Storage::class);

        $this->inputMock = $this->getMockBuilder(Input::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->mailMock = $this->getMockBuilder(Mail::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    public function testExecuteReturnsResponseWhenMailIsNotAvailable()
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

        $refreshMail = new RefreshMail($this->storageMock);

        $result = wait($refreshMail->execute($this->inputMock));

        $this->assertInstanceOf(Success::class, $result);
        $this->assertSame('{"success":true,"payload":{"command":"refreshInfo","info":{"id":"53d8d320-f546-406e-b17f-2938098cbb74","deleted":true}}}', (string) $result);
    }

    public function testExecuteReturnsResponseWhenMailIsAvailableAndHasText()
    {
        $this->mailMock
            ->expects($this->once())
            ->method('setRead')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn('ID')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getFrom')
            ->willReturn('FROM')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getTo')
            ->willReturn('TO')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('GetCc')
            ->willReturn('CC')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getBcc')
            ->willReturn('BCC')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getSubject')
            ->willReturn('SUBJECT')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getTimestamp')
            ->willReturnCallback(function() {
                $timestampMock = $this->createMock(\DateTimeImmutable::class);

                $timestampMock
                    ->expects($this->once())
                    ->method('format')
                    ->willReturn('TIMESTAMP')
                ;

                return $timestampMock;
            })
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('isRead')
            ->willReturn(false)
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getProject')
            ->willReturn('PROJECT')
        ;

        $this->mailMock
            ->expects($this->exactly(3))
            ->method('getText')
            ->willReturn('TEXT')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('GetHtml')
            ->willReturn('HTML')
        ;

        $this->storageMock
            ->expects($this->once())
            ->method('has')
            ->willReturn(true)
            ->with(self::ID)
        ;

        $this->storageMock
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->mailMock)
            ->with(self::ID)
        ;

        $this->inputMock
            ->expects($this->once())
            ->method('getParameter')
            ->willReturn(self::ID)
            ->with('id')
        ;

        $refreshMail = new RefreshMail($this->storageMock);

        $result = wait($refreshMail->execute($this->inputMock));

        $this->assertInstanceOf(Success::class, $result);
        $this->assertSame('{"success":true,"payload":{"command":"refreshInfo","info":{"id":"ID","from":"FROM","to":"TO","cc":"CC","bcc":"BCC","subject":"SUBJECT","read":false,"deleted":false,"timestamp":"TIMESTAMP","project":"PROJECT","content":"TEXT","hasText":true,"hasHtml":true}}}', (string) $result);
    }

    public function testExecuteReturnsResponseWhenMailIsAvailableAndDoesNotHasText()
    {
        $this->mailMock
            ->expects($this->once())
            ->method('setRead')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn('ID')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getFrom')
            ->willReturn('FROM')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getTo')
            ->willReturn('TO')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('GetCc')
            ->willReturn('CC')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getBcc')
            ->willReturn('BCC')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getSubject')
            ->willReturn('SUBJECT')
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getTimestamp')
            ->willReturnCallback(function() {
                $timestampMock = $this->createMock(\DateTimeImmutable::class);

                $timestampMock
                    ->expects($this->once())
                    ->method('format')
                    ->willReturn('TIMESTAMP')
                ;

                return $timestampMock;
            })
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('isRead')
            ->willReturn(false)
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getProject')
            ->willReturn('PROJECT')
        ;

        $this->mailMock
            ->expects($this->exactly(2))
            ->method('getText')
            ->willReturn(null)
        ;

        $this->mailMock
            ->expects($this->exactly(2))
            ->method('GetHtml')
            ->willReturn('HTML')
        ;

        $this->storageMock
            ->expects($this->once())
            ->method('has')
            ->willReturn(true)
            ->with(self::ID)
        ;

        $this->storageMock
            ->expects($this->once())
            ->method('get')
            ->willReturn($this->mailMock)
            ->with(self::ID)
        ;

        $this->inputMock
            ->expects($this->once())
            ->method('getParameter')
            ->willReturn(self::ID)
            ->with('id')
        ;

        $refreshMail = new RefreshMail($this->storageMock);

        $result = wait($refreshMail->execute($this->inputMock));

        $this->assertInstanceOf(Success::class, $result);
        $this->assertSame('{"success":true,"payload":{"command":"refreshInfo","info":{"id":"ID","from":"FROM","to":"TO","cc":"CC","bcc":"BCC","subject":"SUBJECT","read":false,"deleted":false,"timestamp":"TIMESTAMP","project":"PROJECT","content":"HTML","hasText":false,"hasHtml":true}}}', (string) $result);
    }
}
