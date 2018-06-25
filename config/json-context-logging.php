<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default
    |--------------------------------------------------------------------------
    |
    | Default logging settings.
    |
    */

    'default' => [

        // The path to write logs to
        'path' => storage_path('logs'),

        // The filename for the default log
        'file' => 'context.log',


        'handler' => [
            // The class name for the handler to use
            'class'      => \Monolog\Handler\RotatingFileHandler::class,
            // Custom parameters for constructor
            'parameters' => [
                'max_files' => null,
                'level'     => \Monolog\Logger::DEBUG,
            ],
        ],

        'formatter' => [
            // Available: 'standard' (= null); 'pure'
            'type'        => null,
            // Data format to pass to formatter (default = 'Y-m-d H:i:s')
            'date_format' => null,
        ],

        'context' => [
            // A short string identifying this application
            'application' => config('app.name', app()->environment()),
            // The default category name (any custom string) for log entries
            'category'    => null,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Channels
    |--------------------------------------------------------------------------
    |
    | You can configure different logging paths and handlers per channel here.
    |
    | All keys are optional. Any not present will take their values from the
    | defaults configured above.
    |
    */

    'channels' => [

        'testing' => [
            'path'      => storage_path('logs/testing'),
            'file'      => 'testing.log',
            'handler'   => [
                'parameters' => [
                    'max_files' => 7,
                ],
            ],
            'formatter' => [
            ],
            'context' => [
                'category' => 'testing.default',
            ],
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Directories
    |--------------------------------------------------------------------------
    |
    |
    */

    'directories' => [

        // Silently create a new directory for the logs if it does not yet exist.
        'make_if_not_exists' => true,
        // The flags to set for a newly create log dir.
        'chmod'              => 755,
    ],

];
