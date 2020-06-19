<?php foreach ($this->tweets as $tweet): ?>
    <div class="twitter-status" style="padding:10px 5px;clear:both;">
        <div class="twitter-head">
            <div class="twitter-pic" style="float:left;">
                <a href="<?php echo $tweet->user->url ?>" target="_blank">
                    <img src="<?php echo $tweet->user->image_url ?>" images="" twitter-feed-icon.png="" width="42" height="42" alt="twitter icon">
                </a>
            </div>
            <div class="tweetprofilelink" style="margin-left:55px;">
                <div><strong><a href="<?php echo $tweet->user->url ?>" target="_blank"><?php echo $tweet->user->name ?></a></strong></div>
                <div><a href="<?php echo $tweet->user->url ?>" target="_blank">@<?php echo $tweet->user->screen_name ?></a></div>
            </div>
        </div>
        <div class="twitter-text" style="clear:both;margin-bottom:10px;">
            <?php echo $tweet->message ?>
        </div>
        <div class="twitter-link" style="float:left">
            <a href="<?php echo $tweet->url ?>" target="_blank"> Read </a>
        </div>
        <div class="twitter-date" style="float:right">
            <?php echo $tweet->date ?>
        </div>
        <div style="clear:both;">
        </div>
    </div>
<?php endforeach ?>
