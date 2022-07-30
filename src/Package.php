<?php

namespace Lagdo\TwitterFeed;

use Jaxon\Plugin\Package as JaxonPackage;
use Lagdo\TwitterFeed\Ajax\Client as AjaxClient;

use function Jaxon\pm;
use function array_keys;
use function implode;
use function realpath;

/**
 * TwitterFeed package
 */
class Package extends JaxonPackage
{
    /**
     * The timeline to display
     *
     * @var string
     */
    protected $timeline = '';

    /**
     * Get the path to the config file
     *
     * @return string
     */
    public static function config()
    {
        return realpath(__DIR__ . '/../config/twitter.php');
    }

    /**
     * Get the HTML tags to include CSS code and files into the page
     *
     * @return string
     */
    public function getCss(): string
    {
        return '<link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/lagdo/twitter-feed@master/dist/css/twitter.min.css" />';
    }

    /**
     * Get the HTML tags to include javascript code and files into the page
     *
     * @return string
     */
    public function getScript(): string
    {
        $clientCall = $this->factory()->request(AjaxClient::class)->getTimeline(pm()->js('timeline'));
        return $this->view()->render('lagdo::twitter_feed::codes/script')
            ->with('clientCall', $clientCall);
    }

    /**
     * Get the javascript code to execute after page load
     *
     * @return string
     */
    public function getReadyScript(): string
    {
        $timelines = array_keys($this->getOption('timelines', []));
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
    public function timeline($timeline): Package
    {
        $this->timeline = $timeline;
        return $this;
    }

    /**
     * Get the HTML code of the package home page
     *
     * @return string
     */
    public function getHtml(): string
    {
        return $this->view()->render('lagdo::twitter_feed::views/timeline/wrapper')
            ->with('timeline', $this->timeline);
    }
}
