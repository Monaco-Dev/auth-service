<?php

namespace App\Logging;

use Google\Cloud\Logging\LoggingClient;
use Monolog\Handler\PsrHandler;
use Monolog\Logger;

class CreateGoogleCloudLogger
{
    public function __invoke()
    {
        $logName = config('app.name');
        $logging = new LoggingClient([
            'projectId' => config('filesystems.disks.gcs.project_id')
        ]);
        $psrLogger = $logging->psrLogger($logName);

        $handler = new PsrHandler($psrLogger);
        return new Logger($logName, [$handler]);
    }
}
