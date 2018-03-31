<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Cli;

class Option
{
    private $description;

    private $short;

    private $long;

    private $required = false;

    private $default;

    private $input;

    public function __construct(string $description)
    {
        $this->description = $description;
    }

    public function setShort(string $option): self
    {
        $this->short = $option;

        return $this;
    }

    public function setLong(string $option): self
    {
        $this->long = $option;

        return $this;
    }

    public function makeRequired(): self
    {
        $this->required = true;

        return $this;
    }

    public function setDefault(string $default): self
    {
        $this->default = $default;

        return $this;
    }

    public function hasDefault(): bool
    {
        return $this->default !== null;
    }

    public function getDefault(): ?string
    {
        return $this->default;
    }

    public function input(string $name): self
    {
        $this->input = $name;

        return $this;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function hasShort(): bool
    {
        return $this->short !== null;
    }

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function hasLong(): bool
    {
        return $this->long !== null;
    }

    public function getLong(): ?string
    {
        return $this->long;
    }

    public function hasInput(): bool
    {
        return $this->input !== null;
    }

    public function getInput(): string
    {
        return $this->input;
    }

    public function isRequired(): bool
    {
        return $this->required;
    }
}
