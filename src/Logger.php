<?php


namespace Revosystems\GoogleCloud;

use Google\Cloud\Core\Report\SimpleMetadataProvider;
use Google\Cloud\Logging\LoggingClient;
use Google\Cloud\Logging\PsrLogger;
use Monolog\Handler\PsrHandler;
use Monolog\Logger as MonologLogger;

class Logger
{
    public function __invoke(array $config): ?MonologLogger
    {
        if (app()->isLocal() || app()->runningUnitTests()) {
            return null;
        }
        return new MonologLogger(static::config('name'), [new PsrHandler(static::psrBatchLogger())]);
    }

    public static function psrBatchLogger(): PsrLogger
    {
        return LoggingClient::psrBatchLogger(static::config('name'), [
            'metadataProvider' => static::metadataProvider()
        ]);
    }

    public static function metadataProvider(): SimpleMetadataProvider
    {
        return new SimpleMetadataProvider([], static::config('id'), static::config('name'), request()->header('X-Revo-Version') ?? static::config('version'));
    }

    public static function config(string $key): ?string
    {
        return config("filesystems.disks.gcs.project_{$key}");
    }
}
