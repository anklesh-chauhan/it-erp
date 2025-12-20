<?php

declare(strict_types=1);

return [

    'enabled' => env('BOOST_ENABLED', true),

    'browser_logs_watcher' => env('BOOST_BROWSER_LOGS_WATCHER', true),

    'testing' => [
        'framework' => 'pest',
    ],

];
