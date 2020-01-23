<?php

namespace YayDigital\FailedJobsMonitor;

class ExceptionParser
{
    const RE_EXCEPTION_DETAILS = '/(.*) in ([^ ]*\.php:\d+)/s';

    /**
     * @var string
     */
    private $exception;

    public function __construct(string $exception)
    {
        $this->exception = $exception;
    }

    public function getError(): ?string
    {
        $basePath = $this->getBasePath($this->exception);
        $exception = str_replace($basePath, '', $this->exception);

        preg_match(self::RE_EXCEPTION_DETAILS, $exception, $matches);

        $error = optional($matches)[1];

        if ($error !== null) {
            return trim($error);
        }

        return null;
    }

    public function getLocation(): ?string
    {
        $basePath = $this->getBasePath($this->exception);
        $exception = str_replace($basePath, '', $this->exception);

        preg_match(self::RE_EXCEPTION_DETAILS, $exception, $matches);

        $location = optional($matches)[2];

        if ($location !== null) {
            return $location;
        }

        return null;
    }

    public function getStack(): string
    {
        preg_match(self::RE_EXCEPTION_DETAILS, $this->exception, $matches);

        [, $lines] = explode($matches[0], $this->exception);
        $lines = explode("\n", $lines);
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
