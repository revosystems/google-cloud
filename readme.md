## Laravel Google cloud reporting and error handling library

### Installation
    
1. Composer
```
composer require revosystems/google-cloud
```

2. Configuration

On logging.php file add the following custom channel to channels array:
```
        'google' => [
            'driver'    => 'custom',
            'via'       => Revosystems\GoogleCloud\Logger::class,
        ],
```

And declare it on stack channel driver:
```
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily', 'google'],
            'ignore_exceptions' => false,
        ],
```

You can also send errors to google adding ErrorReport::report($e) to your project Exception Handler.

```
class Handler extends ExceptionHandler
    public function report(\Throwable $e)
    {
        if (! $this->shouldReport($e)) {
            return;
        }
        parent::report($e);
        Revosystems\GoogleCloud\ErrorReporter::report($e);
    }    
```

Finally, you only need to declare config variables at your .env file:

```
GOOGLE_CLOUD_PROJECT=<google-cloud-project-id>
GOOGLE_CLOUD_PROJECT_NAME=<without spaces, if not declared will use app('name')>
GOOGLE_CLOUD_PROJECT_VERSION=<ex: 1.0 If not declared will use app.version or 1.0 by default>
GOOGLE_APPLICATION_CREDENTIALS=<path to your service-account.json file>
```

Apart from that, you need to configure your google cloud filesystem on config/filesystems.php
``` 
       'gcs' => [
            'driver'            => 'gcs',
            'project_id'        => env('GOOGLE_CLOUD_PROJECT'),
            'project_name'      => env('GOOGLE_CLOUD_PROJECT_NAME', config('app.name', <default app name>)),
            'project_version'   => env('GOOGLE_CLOUD_PROJECT_VERSION', config('app.version', '1.0')),
            'key_file'          => env('GOOGLE_APPLICATION_CREDENTIALS')
        ],
```
