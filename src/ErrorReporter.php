<?php


namespace Revosystems\GoogleCloud;

use Google\Cloud\ErrorReporting\Bootstrap;
use Throwable;

class ErrorReporter
{
    public static function report(Throwable $e): void
    {
        if (app()->isLocal() || app()->runningUnitTests()) {
            return;
        }
        Bootstrap::init(Logger::psrBatchLogger());
        Bootstrap::exceptionHandler($e);
    }
}
