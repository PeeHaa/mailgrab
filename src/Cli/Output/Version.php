<?php declare(strict_types=1);

namespace PeeHaa\MailGrab\Cli\Output;

class Version
{
    private const BUILD_INFO_PATH = '%s/info/build.version';

    private $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    public function render(): string
    {
        if ($this->hasBuildInfo()) {
            $version = $this->getVersionFromBuildInfo();
        } else {
            $version = $this->getVersionInfoFromGit();
        }

        return substr(trim($version), 1);
    }

    private function hasBuildInfo(): bool
    {
        return file_exists(sprintf(self::BUILD_INFO_PATH, $this->basePath));
    }

    private function getVersionFromBuildInfo(): string
    {
        return file_get_contents(sprintf(self::BUILD_INFO_PATH, $this->basePath));
    }

    private function getVersionInfoFromGit(): string
    {
        return shell_exec('git describe --tags');
    }
}
