<?php
//Start plugin template
?>
<div class="wp-pic-flip" style="display: none;">
	<div class="wp-pic-face wp-pic-front">
		<a class="wp-pic-logo" href="<?php echo $wppic_data->url ?>" <?php echo $bgImage ?> target="_blank" title="<?php _e('WordPress.org Plugin Page', 'wppic-translate') ?>"></a>
		<p class="wp-pic-name"><?php echo $wppic_data->name ?></p>
		<p class="wp-pic-author"><?php _e('Author(s):', 'wppic-translate') ?> <?php echo $wppic_data->author ?></p>
		<div class="wp-pic-bottom">
			<div class="wp-pic-bar">
				<span class="wp-pic-rating"><?php echo $wppic_data->rating ?>%<em><?php _e('Ratings', 'wppic-translate') ?></em></span>
				<span class="wp-pic-downloaded"><?php echo $wppic_data->downloaded ?><em><?php _e('Downloads', 'wppic-translate') ?></em></span>
				<span class="wp-pic-requires"><?php echo $wppic_data->requires ?><em><?php _e('Requires', 'wppic-translate') ?></em></span>
			</div>
			<div class="wp-pic-download">
				<span><?php _e('Download', 'wppic-translate') ?></span>
			</div>
		</div>
	</div>
	<div class="wp-pic-face wp-pic-back">
		<a class="wp-pic-dl-ico" href="<?php echo $wppic_data->download_link ?>" title="<?php _e('Direct download', 'wppic-translate') ?>"></a>
		<p><a class="wp-pic-dl-link" href="<?php echo $wppic_data->download_link ?>" title="<?php _e('Direct download', 'wppic-translate') ?>"><?php echo basename($wppic_data->download_link) ?></a></p>
		<p class="wp-pic-version"><span><?php _e('Current Version:', 'wppic-translate') ?></span> <?php echo $wppic_data->version ?></p>
		<p class="wp-pic-updated"><span><?php _e('Last Updated:', 'wppic-translate') ?></span> <?php echo $wppic_data->last_updated ?></p>
		<div class="wp-pic-bottom">
			<div class="wp-pic-bar">
				<span class="wp-pic-rating"><?php echo $wppic_data->rating ?>%<em><?php _e('Ratings', 'wppic-translate') ?></em></span>
				<span class="wp-pic-downloaded"><?php echo $wppic_data->downloaded ?><em><?php _e('Downloads', 'wppic-translate') ?></em></span>
				<span class="wp-pic-requires"><?php echo $wppic_data->requires ?><em><?php _e('Requires', 'wppic-translate') ?></em></span>
			</div>
			<a class="wp-pic-page" href="<?php echo $wppic_data->url ?>" target="_blank" title="<?php _e('WordPress.org Plugin Page', 'wppic-translate') ?>"><?php _e('WordPress.org Plugin Page', 'wppic-translate') ?></a>
		</div>
		<a class="wp-pic-asset-bg" <?php echo $banner ?> href="<?php echo $wppic_data->url ?>" target="_blank" title="<?php _e('WordPress.org Plugin Page', 'wppic-translate') ?>">
			<span class="wp-pic-asset-bg-title"><span><?php echo $wppic_data->name ?></span></span>
		</a>
		<div class="wp-pic-goback" title="<?php _e('Back', 'wppic-translate') ?>"><span></span></div>
	</div>
</div>
<?php //end of template