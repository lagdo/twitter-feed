A PHP Twitter feed package based on the Jaxon library
=====================================================

This package can add a Twitter feed to any PHP application.

Features
--------

- Can fetch home, user or mentions timelines.
- Can show many timelines on a single page.
- Cache fetched timelines to limit calls to the Twitter service.
- Trigger refresh on a regular basis (one minute interval).

Documentation
-------------

Install the jaxon library so it bootstraps from a config file and handles ajax requests. Here's the [documentation](https://www.jaxon-php.org/docs/v3x/advanced/bootstrap.html).

Install this package with Composer. If a [Jaxon plugin](https://www.jaxon-php.org/docs/v3x/plugins/frameworks.html) exists for your framework, you can also install it. It will automate the previous step.

Declare the package and the Twitter feeds in the `app` section of the [Jaxon configuration file](https://www.jaxon-php.org/docs/v3x/advanced/bootstrap.html).

```php
    'app' => [
        // Other config options
        // ...
        'packages' => [
            Lagdo\TwitterFeed\Package::class => [
                'timelines' => [
                    'home' => [
                        'type'       => 'home', // The type of timeline
                        'count'      => 10,     // The number of tweets to show
                        'auth'       => [
                            'consumer_key'        => '',
                            'consumer_secret'     => '',
                            'access_token'        => '',
                            'access_token_secret' => '',
                        ],
                    ],
                ],
            ],
        ],
    ],
```

Multiple timelines can be declared. They can use the same authentication options or not.

```php
    'app' => [
        // Other config options
        // ...
        'packages' => [
            Lagdo\TwitterFeed\Package::class => [
                'auth' => [
                    'timeline_auth' => [
                        'consumer_key'        => '',
                        'consumer_secret'     => '',
                        'access_token'        => '',
                        'access_token_secret' => '',
                    ],
                ],
                'timelines' => [
                    'home_timeline' => [
                        'type'       => 'home',
                        'count'      => 10,
                        'auth'       => 'timeline_auth',
                    ],
                    'user_timeline' => [
                        'type'       => 'user',
                        'count'      => 10,
                        'auth'       => 'timeline_auth',
                    ],
                ],
            ],
        ],
    ],
```

Insert the CSS and javascript codes in the HTML pages of your application using calls to `jaxon()->getCss()` and `jaxon()->getScript(true)`.

In the page that displays the Twitter feed, insert its HTML code with a call to `jaxon()->package(\Lagdo\TwitterFeed\Package::class)->timeline($timeline)->getHtml()`. The timeline must be specified before getting the HTML code.

Two cases are then possible.
- If the Twitter feed is displayed on a dedicated page, make a call to `jaxon()->package(\Lagdo\TwitterFeed\Package::class)->ready()` when loading the page.
- If the Twitter feed is loaded with an Ajax request in a page already displayed, execute the javascript code returned the call to `jaxon()->package(\Lagdo\TwitterFeed\Package::class)->getReadyScript()` when loading the page.

Notes
-----

The styling of the timelines is defined in the CSS file under the `dist` directory.

Contribute
----------

- Issue Tracker: github.com/lagdo/twitter-feed/issues
- Source Code: github.com/lagdo/twitter-feed

License
-------

The project is licensed under the BSD 3-Clause license.
