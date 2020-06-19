jaxon.twitterFeed = {
    _timeout: null, // The value returned by the javascript setTimeout() function
    interval: 60,   // The interval between refresh
    timelines: [],
    enableRefresh: function() {
        if (jaxon.twitterFeed._timeout != null)
            return;
        jaxon.twitterFeed._timeout = setTimeout(jaxon.twitterFeed.initFetch, jaxon.twitterFeed.interval * 1000);
    },
    disableRefresh: function() {
        jaxon.twitterFeed._timeout != null && clearTimeout(jaxon.twitterFeed._timeout);
        jaxon.twitterFeed._timeout = null;
    },
    initFetch: function() {
        jaxon.twitterFeed.disableRefresh();
        jaxon.twitterFeed.timelines.forEach((timeline) => {
            const elt = jaxon.$('twitter_feed_' + timeline);
            if((elt)) {
                jaxon.twitterFeed.execFetch(timeline);
            }
        });
    },
    execFetch: function(timeline) {
        <?php echo $this->clientCall ?>;
    }
}
