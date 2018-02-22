<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Smtp\Command;

use PeeHaa\MailGrab\Smtp\TransactionStatus;

class Factory
{
    private $availableCommands = [];

    public function __construct()
    {
        $this->availableCommands = [
            TransactionStatus::SEND_BANNER => [
                Quit::class,
                Ehlo::class,
                Helo::class,
            ],
            TransactionStatus::INIT => [
                MailFrom::class,
                Quit::class,
            ],
            TransactionStatus::FROM => [
                RcptTo::class,
                Quit::class,
                Rset::class,
            ],
            TransactionStatus::TO => [
                Quit::class,
                StartData::class,
                Rset::class,
                RcptTo::class,
            ],
            TransactionStatus::HEADERS => [
                EndBody::class,
                StartHeader::class,
                StartBody::class,
            ],
            TransactionStatus::UNFOLDING => [
                StartBody::class,
                EndBody::class,
                Unfold::class,
                StartHeader::class,
            ],
            TransactionStatus::BODY => [
                EndBody::class,
                BodyLine::class,
            ],
            TransactionStatus::PROCESSING => [

            ],
        ];
    }

    public function build(TransactionStatus $clientStatus, string $line): Command
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
