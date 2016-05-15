<div class="banner"><a href="#"><img src="images/beauty-salon.jpg" alt="Beauty Salon" width="275" height="180" /></a></div>
<div class="banner"><a href="#"><img src="images/best-dolls.jpg" alt="Best Dolls" width="275" height="180" /></a></div>
<div id="twitter_feeds">
	<h3>twitter feeds</h3>
	<?php if ($tweets) { ?>
		<?php $counter = 0;
		foreach ($tweets as $tweet) {
			$counter++; ?>
			<p><?php echo auto_link($tweet->text, 'url', true); ?></p>
			<?php if ($counter == 1) { ?>
				<div class="hr_dotted" style="margin-bottom:7px"></div>
		<?php } ?>
	<?php }
} ?>

</div>
<div style="clear:both"></div>
<script type="text/javascript" src="http://www.auctionnudge.com/feedback_build/js/UserID/salesvault/theme/table"></script><div id="auction-nudge-feedback" class="auction-nudge"><a href="http://www.auctionnudge.com/your-ebay-feedback">Live eBay Feedback From Auction Nudge</a></div>
<div style="clear:both"></div>