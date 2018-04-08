<?php declare(strict_types=1);

namespace PeeHaa\MailGrabTest\Unit\Http\Command;

use PeeHaa\AmpWebsocketCommand\Input;
use PeeHaa\MailGrab\Configuration;
use PeeHaa\MailGrab\Http\Command\Init;
use PeeHaa\MailGrab\Http\Entity\Mail;
use PeeHaa\MailGrab\Http\Storage\Storage;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use function Amp\Promise\wait;

class InitTest extends TestCase
{
    /** @var MockObject|Storage */
    private $storageMock;

    /** @var MockObject|Configuration */
    private $configurationMock;

    /** @var MockObject|Input $inputMock */
    private $inputMock;

    /** @var MockObject|Mail */
    private $mailMock;

    public function setUp()
    {
        $this->storageMock = $this->createMock(Storage::class);

        $this->configurationMock = $this->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->inputMock = $this->getMockBuilder(Input::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;

        $this->mailMock = $this->getMockBuilder(Mail::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    public function testExecuteReturnsResponse()
    {
        $this->mailMock
            ->expects($this->once())
            ->method('getId')
            ->willReturn('ID')
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

        $mails = new \ArrayIterator([$this->mailMock]);

        $this->storageMock
            ->expects($this->any())
            ->method('rewind')
            ->willReturnCallback(function () use ($mails) {
                $mails->rewind();
            })
        ;

        $this->storageMock
            ->expects($this->any())
            ->method('current')
            ->willReturnCallback(function () use ($mails) {
                return $mails->current();
            })
        ;

        $this->storageMock
            ->expects($this->any())
            ->method('key')
            ->willReturnCallback(function () use ($mails) {
                return $mails->key();
            })
        ;

        $this->storageMock
            ->expects($this->any())
            ->method('next')
            ->willReturnCallback(function () use ($mails) {
                $mails->next();
            })
        ;

        $this->storageMock
            ->expects($this->any())
            ->method('valid')
            ->willReturnCallback(function () use ($mails) {
                return $mails->valid();
            })
        ;

        $this->configurationMock
            ->expects($this->at(0))
            ->method('get')
            ->willReturn('HOSTNAME')
            ->with('hostname')
        ;

        $this->configurationMock
            ->expects($this->at(1))
            ->method('get')
            ->willReturn('SMTPPORT')
            ->with('smtpport')
        ;

        $init = new Init($this->storageMock, $this->configurationMock);

        $result = wait($init->execute($this->inputMock));

        $this->assertSame('{"success":true,"payload":{"command":"init","mails":[{"id":"ID","subject":"SUBJECT","timestamp":"TIMESTAMP","read":false,"project":"PROJECT"}],"config":{"hostname":"HOSTNAME","smtpport":"SMTPPORT"}}}', (string) $result);
    }
}
