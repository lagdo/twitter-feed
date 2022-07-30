<?php

namespace Lagdo\TwitterFeed;

use Jaxon\Utils\Config\Config;
use DG\Twitter\Twitter as TwitterClient;
use DG\Twitter\Exception as TwitterException;

use DateTime;
use Exception;

use function Jaxon\jaxon;

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
     * Get a timeline content from the cache
     *
     * @param string $timeline  The timeline key in the configuration
     *
     * @return array | false
     */
    protected function getCachedTimeline($timeline)
    {
        $cacheTime = 30; // 1 minute et 30 secondes
        $cacheFile = \dirname(__DIR__) . '/cache/' . $timeline . '.json';
        if(\file_exists($cacheFile) && \time() - $cacheTime < \filemtime($cacheFile)) {
            return \json_decode(\file_get_contents($cacheFile));
        }
        return false;
    }

    /**
     * Save a timeline content in the cache
     *
     * @param string $timeline  The timeline key in the configuration
     * @param array  $statuses  The timeline content
     *
     * @return void
     */
    protected function cacheTimeline($timeline, $statuses)
    {
        try
        {
            $cacheFile = \dirname(__DIR__) . '/cache/' . $timeline . '.json';
            \file_put_contents($cacheFile, \json_encode($statuses));
        }
        catch(Exception $e){}
    }

    /**
     * Fetch a timeline from Twitter
     *
     * @param string $timeline  The timeline key in the configuration
     *
     * @return array
     */
    protected function fetchTimeline($timeline)
    {
        $types = [
            'home' => TwitterClient::ME,
            'user' => TwitterClient::ME_AND_FRIENDS,
            'mentions' => TwitterClient::REPLIES,
        ];
        $type = $this->config->getOption("timelines.$timeline.type", 'home');
        if(!\array_key_exists($type, $types))
        {
            return [];
        }

        $authConfigKey = "timelines.$timeline.auth";
        if(\is_string(($auth = $this->config->getOption($authConfigKey))))
        {
            $authConfigKey = "auth.$auth";
        }

        try
        {
            $twitterClient = new TwitterClient(
                $this->config->getOption("$authConfigKey.consumer_key", ''),
                $this->config->getOption("$authConfigKey.consumer_secret", ''),
                $this->config->getOption("$authConfigKey.access_token", ''),
                $this->config->getOption("$authConfigKey.access_token_secret", '')
            );

            return $twitterClient->load($types[$type]);
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
     *
     * @return array
     */
    protected function formatTimeline($statuses, $count)
    {
        $statuses = \array_slice($statuses, 0, $count);
        $tweets = [];
        foreach($statuses as $status)
        {
            $date = DateTime::createFromFormat('D M d H:i:s O Y', $status->created_at);
            $tweets[] = (object)[
                'date' => $date->format('M d, Y H:i:s'),
                'message' => TwitterClient::clickable($status),
                'url' => 'https://twitter.com/i/web/status/' . $status->id_str,
                'user' => (object)[
                    'name' => $status->user->name,
                    'screen_name' => $status->user->screen_name,
                    'url' => 'https://twitter.com/' . $status->user->screen_name,
                    'image_url' => $status->user->profile_image_url_https,
                ],
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

        $statuses = $this->getCachedTimeline($timeline);
        if(!$statuses || \count($statuses) == 0)
        {
            $statuses = $this->fetchTimeline($timeline);
        }
        if(\count($statuses) == 0)
        {
            return [];
        }

        $count = \intval($this->config->getOption("timelines.$timeline.count", 10));
        $statuses = $this->formatTimeline($statuses, $count);

        // Save the timeline in the cache
        $this->cacheTimeline($timeline, $statuses);

        return $statuses;
    }
}
