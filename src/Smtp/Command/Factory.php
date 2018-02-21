<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp\Command;

use PeeHaa\MailGrab\Smtp\ClientStatus;

class Factory
{
    private $availableCommands = [];

    public function __construct()
    {
        $this->availableCommands = [
            ClientStatus::SEND_BANNER => [
                Quit::class,
                Ehlo::class,
                Helo::class,
            ],
            ClientStatus::INIT => [
                MailFrom::class,
                Quit::class,
            ],
            ClientStatus::FROM => [
                RcptTo::class,
                Quit::class,
                Rset::class,
            ],
            ClientStatus::TO => [
                Quit::class,
                StartData::class,
                Rset::class,
                RcptTo::class,
            ],
            ClientStatus::HEADERS => [
                EndBody::class,
                StartHeader::class,
                StartBody::class,
            ],
            ClientStatus::UNFOLDING => [
                StartBody::class,
                EndBody::class,
                Unfold::class,
                StartHeader::class,
            ],
            ClientStatus::BODY => [
                EndBody::class,
                BodyLine::class,
            ],
            ClientStatus::PROCESSING => [

            ],
        ];
    }

    public function build(ClientStatus $clientStatus, string $line): Command
    {
        if (!array_key_exists($clientStatus->getValue(), $this->availableCommands)) {
            throw new \Exception('Syntax error, command unrecognised');
        }

        /** @var Command $command */
        foreach ($this->availableCommands[$clientStatus->getValue()] as $command) {
            if (!$command::isValid($line)) continue;

            return new $command($line);
        }

        throw new \Exception('Syntax error, command unrecognised');
    }
}
