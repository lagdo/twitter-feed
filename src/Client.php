<?php

namespace Lagdo\TwitterFeed;

use Jaxon\Utils\Config\Config;
use DG\Twitter\Twitter as TwitterClient;
use DG\Twitter\Exception as TwitterException;

/**
 * TwitterFeed client
 */
class Client
{
    /**
     * The Jaxon TwitterFeed package config
     *
     * @var Config
     */
    protected $config;

    /**
     * The constructor
     *
     * @param Config $config    The package config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Make the Twitter API client
     *
     * @param string $timeline  The timeline key in the configuration
     *
     * @return TwitterClient
     */
    public function twitterClient($timeline)
    {
        $authConfigKey = "timelines.$timeline.auth";
        if(\is_string(($auth = $this->config->getOption($authConfigKey))))
        {
            $authConfigKey = "auth.$auth";
        }
        return new TwitterClient(
            $this->config->getOption("$authConfigKey.consumer_key", ''),
            $this->config->getOption("$authConfigKey.consumer_secret", ''),
            $this->config->getOption("$authConfigKey.access_token", ''),
            $this->config->getOption("$authConfigKey.access_token_secret", '')
        );
    }

    /**
     * Get the most recent tweets posted by the user and the users he follows.
     *
     * @param string $timeline  The timeline key in the configuration
     *
     * @return array
     */
    protected function getHomeTimeline($timeline)
    {
        try
        {
            return $this->twitterClient($timeline)->load(TwitterClient::ME);
        }
        catch(TwitterException $e)
        {
            jaxon()->logger()->error($e->getMessage());
            return [];
        }
    }

    /**
     * Get the most recent Tweets posted by the user.
     *
     * @param string $timeline  The timeline key in the configuration
     *
     * @return array
     */
    protected function getUserTimeline($timeline)
    {
        try
        {
            return $this->twitterClient($timeline)->load(TwitterClient::ME_AND_FRIENDS);
        }
        catch(TwitterException $e)
        {
            jaxon()->logger()->error($e->getMessage());
            return [];
        }
    }

    /**
     * Get the most recent mentions for the user.
     *
     * @param string $timeline  The timeline key in the configuration
     *
     * @return array
     */
    protected function getMentionsTimeline($timeline)
    {
        try
        {
            return $this->twitterClient($timeline)->load(TwitterClient::REPLIES);
        }
        catch(TwitterException $e)
        {
            jaxon()->logger()->error($e->getMessage());
            return [];
        }
    }

    /**
     * Format statuses
     *
     * @param array $statuses   Statuses returned by the library, to be formatted.
     * @param integer $count    The max number of tweets to return
     * @return void
     */
    protected function formatTweets($statuses, $count)
    {
        $statuses = \array_slice($statuses, 0, $count);
        $tweets = [];
        foreach($statuses as $status)
        {
            $tweets[] = [
                'message' => TwitterClient::clickable($status),
                'date' => $status->created_at,
                'user' => $status->user->name,
            ];
        }
        return $tweets;
    }

    /**
     * Get the timeline corresponding to the given config entry.
     *
     * @param string $timeline  The timeline key in the configuration
     *
     * @return array
     */
    public function getTimeline($timeline)
    {
        $timeline = trim($timeline);
        // Check if the timeline exists in the config.
        if(!$this->config->getOption("timelines.$timeline", false))
        {
            jaxon()->logger()->error("No entry with id $timeline in the config.");
            return [];
        }

        $type = $this->config->getOption("timelines.$timeline.type", 'home');
        switch(trim($type))
        {
        case 'home';
            $statuses = $this->getHomeTimeline($timeline);
            break;
        case 'user';
            $statuses = $this->getUserTimeline($timeline);
            break;
        case 'mentions';
            $statuses = $this->getMentionsTimeline($timeline);
            break;
        default:
            return [];
        }

        $count = intval($this->config->getOption("timelines.$timeline.count", 10));
        return $this->formatTweets($statuses, $count);
    }
}
