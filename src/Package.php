<?php

namespace Lagdo\TwitterFeed;

use Jaxon\Plugin\Package as JaxonPackage;
use Lagdo\TwitterFeed\Ajax\Client as AjaxClient;

/**
 * TwitterFeed package
 */
class Package extends JaxonPackage
{
    /**
     * Get the path to the config file
     *
     * @return string
     */
    public static function getConfigFile()
    {
        return realpath(__DIR__ . '/../config/twitter.php');
    }

    /**
     * Get the HTML tags to include javascript code and files into the page
     *
     * @return string
     */
    public function getScript()
    {
        $clientCall = jaxon()->request(AjaxClient::class)->getTimeline(pm()->js('timeline'));
        return $this->view()->render('lagdo::twitter_feed::codes/script')
            ->with('clientCall', $clientCall);
    }

    /**
     * Get the javascript code to execute after page load
     *
     * @return string
     */
    public function getReadyScript()
    {
        $timelines = \array_keys($this->aOptions['timelines']);
        return "jaxon.twitterFeed.timelines=['" .
            implode("','", $timelines) . "'];jaxon.twitterFeed.initFetch();";
    }

    /**
     * Set the timeline to generate HTML code for.
     *
     * @param string $timeline    The timeline id in the configuration
     *
     * @return Package
     */
    public function timeline($timeline)
    {
        $this->timeline = $timeline;
        return $this;
    }

    /**
     * Get the HTML code of the package home page
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->view()->render('lagdo::twitter_feed::views/timeline/wrapper')
            ->with('timeline', $this->timeline);
    }
}
