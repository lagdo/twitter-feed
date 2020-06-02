<?php

return [
    'directories' => [
        __DIR__ . '/../ajax' => [
            'namespace' => 'Lagdo\\TwitterFeed\\Ajax',
            'autoload' => false,
        ],
    ],
    'views' => [
        'lagdo::twitter_feed' => [
            'directory' => __DIR__ . '/../templates',
            'extension' => '.latte',
            'renderer' => 'latte',
        ],
    ],
    'container' => [
        Lagdo\TwitterFeed\Client::class => function() {
            return new Lagdo\TwitterFeed\Client();
        },
    ],
];
