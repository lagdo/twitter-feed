<?php

namespace Lagdo\TwitterFeed;

use GuzzleHttp\Client as HttpClient;

/**
 * TwitterFeed client
 */
class Client
{
    /**
     * The constructor
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * Get the most recent tweets posted by the user and the users he follows.
     *
     * @param integer $count    The max numver of tweets to return
     *
     * @return array
     */
    public function getHomeTimeline($count)
    {
        return [];
    }

    /**
     * Get the most recent Tweets posted by the user.
     *
     * @param integer $count    The max numver of tweets to return
     *
     * @return array
     */
    public function getUserTimeline($count)
    {
        return [];
    }

    /**
     * Get the 20 most recent mentions (Tweets containing a users’s @handle) for the authenticating user.
     *
     * @param integer $count    The max numver of tweets to return
     *
     * @return array
     */
    public function getMentionsTimeline($count)
    {
        return [];
    }
}
