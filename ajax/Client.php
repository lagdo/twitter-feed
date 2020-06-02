<?php

namespace Lagdo\TwitterFeed\Ajax;

use Lagdo\TwitterFeed\Package as TwitterFeedPackage;
use Lagdo\TwitterFeed\Client as TwitterFeedClient;
use TwitterAPIExchange;

use Jaxon\CallableClass;
use Exception;

/**
 * TwitterFeed Ajax client
 */
class Client extends CallableClass
{
    /**
     * The Jaxon TwitterFeed package
     *
     * @var TwitterFeedPackage
     */
    protected $package;

    /**
     * The TwitterFeed client
     *
     * @var TwitterFeedClient
     */
    protected $client;

    /**
     * The constructor
     *
     * @param TwitterFeedPackage $package    The TwitterFeed package
     * @param TwitterFeedClient  $client     The TwitterFeed client
     */
    public function __construct(TwitterFeedPackage $package, TwitterFeedClient $client)
    {
        $this->package = $package;
        $this->client = $client;
    }

    /**
     * Get a given account options
     *
     * @param string $account   The account in the configuration
     *
     * @return array
     */
    protected function getAccountOptions($account)
    {
        $options = $this->package->getOptions();
        $accountOptions = $options['accounts'][$account];
        // Set the "wait" option default value
        /*if(!\key_exists('wait', $accountOptions))
        {
            $accountOptions['wait'] = \key_exists('wait', $options) ? $options['wait'] : true;
        }*/
        return $accountOptions;
    }

    /**
     * Get the most recent tweets posted by the user and the users he follows.
     *
     * @param string $account   The account in the configuration
     *
     * @return array
     */
    public function getHomeTimeline($account)
    {
        $accountOptions = $this->getAccountOptions($account);
        try
        {
            $tweets = $this->client->getHomeTimeline($accountOptions);
            return $this->showTweets($tweets, $account);
        }
        catch(Exception $e)
        {
            $this->response->dialog->error("Unable to get home timeline.", 'Error');
            return $this->response;
        }
    }

    /**
     * Get the most recent Tweets posted by the user.
     *
     * @param string $account   The account in the configuration
     *
     * @return array
     */
    public function getUserTimeline($account)
    {
        $accountOptions = $this->getAccountOptions($account);
        try
        {
            $tweets = $this->client->getUserTimeline($accountOptions);
            return $this->showTweets($tweets, $account);
        }
        catch(Exception $e)
        {
            $this->response->dialog->error("Unable to get user timeline.", 'Error');
            return $this->response;
        }
    }

    /**
     * Get the 20 most recent mentions (Tweets containing a users’s @handle) for the authenticating user.
     *
     * @param string $account   The account in the configuration
     *
     * @return array
     */
    public function getMentionsTimeline($account)
    {
        $accountOptions = $this->getAccountOptions($account);
        try
        {
            $tweets = $this->client->getMentionsTimeline($accountOptions);
            return $this->showTweets($tweets, $account);
        }
        catch(Exception $e)
        {
            $this->response->dialog->error("Unable to get mentions timeline.", 'Error');
            return $this->response;
        }
    }
}
