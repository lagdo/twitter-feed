<?php

namespace Lagdo\TwitterFeed\Ajax;

use Lagdo\TwitterFeed\Client as TwitterClient;

use Jaxon\CallableClass;
use Exception;

/**
 * TwitterFeed Ajax client
 */
class Client extends CallableClass
{
    /**
     * The TwitterFeed client
     *
     * @var TwitterClient
     */
    protected $client;

    /**
     * The constructor
     *
     * @param TwitterClient  $client     The TwitterFeed client
     */
    public function __construct(TwitterClient $client)
    {
        $this->client = $client;
    }

    /**
     * Get the timeline corresponding to the given config entry.
     *
     * @param string $timeline  The account in the configuration
     *
     * @return array
     */
    public function getTimeline($timeline)
    {
        $tweets = $this->client->getTimeline(trim($timeline));
        return $this->showTweets($tweets, $account);
    }
}
