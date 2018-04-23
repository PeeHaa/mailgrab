<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Http\Command;

use PeeHaa\AmpWebsocketCommand\Input;
use PeeHaa\AmpWebsocketCommand\Success;
use PeeHaa\MailGrab\Http\Command\SelectMail;
use PeeHaa\MailGrab\Http\Entity\Mail;
use PeeHaa\MailGrab\Http\Storage\Storage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class SelectMailTest extends TestCase
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

        $selectMail = new SelectMail($this->storageMock);

        $result = wait($selectMail->execute($this->inputMock));

        $this->assertInstanceOf(Success::class, $result);
        $this->assertSame('{"success":true,"payload":{"command":"mailInfo","info":{"id":"53d8d320-f546-406e-b17f-2938098cbb74","deleted":true}}}', (string) $result);
    }

    public function testExecuteReturnsResponseWhenMailIsAvailableAndHaveHtml()
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
            ->expects($this->once())
            ->method('getText')
            ->willReturn('TEXT')
        ;

        $this->mailMock
            ->expects($this->exactly(3))
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

        $selectMail = new SelectMail($this->storageMock);

        $result = wait($selectMail->execute($this->inputMock));

        $this->assertInstanceOf(Success::class, $result);
        $this->assertSame('{"success":true,"payload":{"command":"mailInfo","info":{"id":"ID","from":"FROM","to":"TO","cc":"CC","bcc":"BCC","subject":"SUBJECT","read":false,"deleted":false,"timestamp":"TIMESTAMP","project":"PROJECT","content":"HTML","hasText":true,"hasHtml":true,"attachments":[]}}}', (string) $result);
    }

    public function testExecuteReturnsResponseWhenMailIsAvailableAndDoesNotHaveHtml()
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
            ->willReturn('TEXT')
        ;

        $this->mailMock
            ->expects($this->exactly(2))
            ->method('GetHtml')
            ->willReturn(null)
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

        $selectMail = new SelectMail($this->storageMock);

        $result = wait($selectMail->execute($this->inputMock));

        $this->assertInstanceOf(Success::class, $result);
        $this->assertSame('{"success":true,"payload":{"command":"mailInfo","info":{"id":"ID","from":"FROM","to":"TO","cc":"CC","bcc":"BCC","subject":"SUBJECT","read":false,"deleted":false,"timestamp":"TIMESTAMP","project":"PROJECT","content":"TEXT","hasText":true,"hasHtml":false,"attachments":[]}}}', (string) $result);
    }

    public function testExecuteReturnsResponseWhenMailIsAvailableAndDoesHaveAttachments()
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
            ->willReturn('TEXT')
        ;

        $this->mailMock
            ->expects($this->exactly(2))
            ->method('GetHtml')
            ->willReturn(null)
        ;

        $this->mailMock
            ->expects($this->once())
            ->method('getAttachments')
            ->willReturn([
                [
                    'id'           => '11',
                    'name'         => 'Attachment 1',
                    'content-type' => 'text/plain',
                ],
                [
                    'id'           => '12',
                    'name'         => 'Attachment 2',
                    'content-type' => 'text/html',
                ],
            ])
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

        $selectMail = new SelectMail($this->storageMock);

        $result = wait($selectMail->execute($this->inputMock));

        $this->assertInstanceOf(Success::class, $result);
        $this->assertSame('{"success":true,"payload":{"command":"mailInfo","info":{"id":"ID","from":"FROM","to":"TO","cc":"CC","bcc":"BCC","subject":"SUBJECT","read":false,"deleted":false,"timestamp":"TIMESTAMP","project":"PROJECT","content":"TEXT","hasText":true,"hasHtml":false,"attachments":[{"id":"11","name":"Attachment 1","content-type":"text\/plain"},{"id":"12","name":"Attachment 2","content-type":"text\/html"}]}}}', (string) $result);
    }
}
