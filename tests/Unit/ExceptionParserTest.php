<?php

use YayDigital\FailedJobsMonitor\ExceptionParser;

class ExceptionParserTest extends Orchestra\Testbench\TestCase
{
    public function testSimple()
    {
        $rawException = <<<EOL
ErrorException: Undefined index: labels in /tmp/failed-jobs-monitor/vendor/yay-innovations/test/src/Class.php:180
Stack trace:
#0 /tmp/failed-jobs-monitor/vendor/test/test/src/Class.php(185): Illuminate\Foundation\Bootstrap\HandleExceptions->handleError(8, 'Undefined index...', '/tmp...', 185, Array)
#1 {main}
EOL;

        $exception = new ExceptionParser($rawException);

        $this->assertEquals('ErrorException: Undefined index: labels', $exception->getError());
        $this->assertEquals('vendor/yay-innovations/test/src/Class.php:180', $exception->getLocation());

        $stack = $exception->getStack();

        $this->assertEquals('#0 vendor/test/test/src/Class.php(185): Illuminate\Foundation\Bootstrap\HandleExceptions->handleError(8, \'Undefined index...\', \'/tmp...\', 185, Array)
#1 {main}', $stack);
    }

    public function testWithIn()
    {
        $rawException = <<<EOL
GuzzleHttp\Exception\ServerException: Server error: POST resulted in a `500 Internal Server Error` response:
(truncated)
 in /tmp/failed-jobs-monitor/vendor/yay-innovations/test/src/Class.php:180
Stack trace:
#0 /tmp/failed-jobs-monitor/vendor/test/test/src/Class.php(185): Illuminate\Foundation\Bootstrap\HandleExceptions->handleError(8, 'Undefined index...', '/tmp...', 185, Array)
#1 {main}
EOL;

        $exception = new ExceptionParser($rawException);

        $this->assertEquals('GuzzleHttp\Exception\ServerException: Server error: POST resulted in a `500 Internal Server Error` response:
(truncated)', $exception->getError());
        $this->assertEquals('vendor/yay-innovations/test/src/Class.php:180', $exception->getLocation());

        $stack = $exception->getStack();

        $this->assertEquals('#0 vendor/test/test/src/Class.php(185): Illuminate\Foundation\Bootstrap\HandleExceptions->handleError(8, \'Undefined index...\', \'/tmp...\', 185, Array)
#1 {main}', $stack);
    }
}
