<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Log;

class Output
{
    private $textualLevels = [
        Level::INFO       => 'INFO',
        Level::MESSAGE_IN => 'INCOMING',
        Level::SMTP_IN    => 'SMTP_IN',
        Level::SMTP_OUT   => 'SMTP_OUT',
        Level::DEBUG      => 'DEBUG',
    ];

    private $logLevel;

    public function __construct(Level $level)
    {
        $this->logLevel = $level;
    }

    public function info(string $message, array $context = []): void
    {
        $this->log(new Level(Level::INFO), $message, $context);
    }

    public function messageIn(string $message, array $context = []): void
    {
        $this->log(new Level(Level::MESSAGE_IN), $message, $context);
    }

    public function smtpIn(string $message, array $context = []): void
    {
        $this->log(new Level(Level::SMTP_IN), $message, $context);
    }

    public function smtpOut(string $message, array $context = []): void
    {
        $this->log(new Level(Level::SMTP_OUT), $message, $context);
    }

    public function debug(string $message, array $context = []): void
    {
        $this->log(new Level(Level::DEBUG), $message, $context);
    }

    public function log(Level $level, string $message, array $context = [])
    {
        if (!$this->meetsLogLevel($level)) {
            return;
        }

        echo sprintf(
            '%s [%s] %s',
            (new \DateTime())->format('Y-m-d H:i:s'),
            $this->textualLevels[$level->getValue()],
            $this->replaceNonPrintableCharacters($message)
        ) . PHP_EOL;

        if (!empty($context)) {
            echo json_encode($context) . PHP_EOL;
        }
    }

    private function meetsLogLevel(Level $level): bool
    {
        return (bool) ($this->logLevel->getValue() & $level->getValue());
    }

    private function replaceNonPrintableCharacters(string $message): string
    {
        return str_replace(["\r", "\n", "\t"], ['\r', '\n', '\t'], $message);
    }
}
