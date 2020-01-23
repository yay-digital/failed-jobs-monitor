<?php

namespace YayDigital\FailedJobsMonitor;

class ExceptionParser
{
    const RE_EXCEPTION_DETAILS = '/(.*) in (.*)/';

    /**
     * @var string
     */
    private $exception;

    public function __construct(string $exception)
    {
        $this->exception = $exception;
    }

    public function getError(): string
    {
        $basePath = $this->getBasePath($this->exception);

        $line = explode("\n", $this->exception)[0];
        $line = str_replace($basePath, '', $line);

        preg_match(self::RE_EXCEPTION_DETAILS, $line, $matches);

        return $matches[1];
    }

    public function getLocation(): string
    {
        $basePath = $this->getBasePath($this->exception);

        $line = explode("\n", $this->exception)[0];
        $line = str_replace($basePath, '', $line);

        preg_match(self::RE_EXCEPTION_DETAILS, $line, $matches);

        return $matches[2];
    }

    public function getStack(): string
    {
        $lines = explode("\n", $this->exception);
        array_shift($lines);
        array_shift($lines);

        $exception = join("\n", $lines);

        $basePath = $this->getBasePath($exception);

        return str_replace($basePath, '', $exception);
    }

    private function getBasePath(string $exception): string
    {
        preg_match('/#\d+ (.*?\/)vendor\/.*?\.php\(\d+\)/', $exception, $matches);

        return $matches[1];
    }
}
