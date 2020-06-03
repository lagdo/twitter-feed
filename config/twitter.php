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
        Lagdo\TwitterFeed\Client::class => function($di) {
            $package = $di->get(Lagdo\TwitterFeed\package::class);
            return new Lagdo\TwitterFeed\Client($package->getConfig());
        },
    ],
];
