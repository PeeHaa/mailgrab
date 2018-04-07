<?php declare(strict_types=1);

namespace PeeHaa\MailGrab;

use PeeHaa\ArrayPath\ArrayPath;

class Configuration
{
    private $arrayPath;

    private $configuration;

    public function __construct(ArrayPath $arrayPath, array $configuration)
    {
        $this->arrayPath     = $arrayPath;
        $this->configuration = $configuration;
    }

    public function exists(string $key): bool
    {
        return $this->arrayPath->exists($this->configuration, $key);
    }

    public function get(string $key)
    {
        return $this->arrayPath->get($this->configuration, $key);
    }

    public function set(string $key, $value): void
    {
        $this->arrayPath->set($this->configuration, $key, $value);
    }
}
