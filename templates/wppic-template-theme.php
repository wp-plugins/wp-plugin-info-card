<?php
	//Fix for requiered version with extra info : WP 3.9, BP 2.1+
	if(is_numeric($wppic_data->requires)){
		$wppic_data->requires = 'WP ' . $wppic_data->requires . '+';
	}
		
	//Icon URL
	$screenshotUrl = '';
	$bgImage = '';
	if ( !empty( $wppic_data->screenshot_url ) ) {
		$screenshotUrl = $wppic_data->screenshot_url;
	}
	if( !empty($image) ){
		$bgImage = 'style="background-image:  none, url(' . $image . '), url(' . WPPIC_URL . '/img/wp-pic-sprite.png);"';
	} else {
		if( empty($screenshotUrl) ){
			$bgImage = 'style="background-image:  url(' . WPPIC_URL . '/img/wp-pic-sprite.png);"';
		} else {
			$bgImage = 'style="background-image:  none, url(https:' . esc_attr( $screenshotUrl ) . '), url(' . WPPIC_URL . '/img/wp-pic-sprite.png);"';
		}
	}

	//Theme banner (screenshot)
	$banner = '';
	if ( !empty( $wppic_data->screenshot_url ) ) {
		$banner = 'style="background-image: url(https:' . esc_attr( $wppic_data->screenshot_url ) . ');"';
	}

//Start template
?>
	<div class="wp-pic-flip" <?php echo $ajaxFadeIn ?>>
		<div class="wp-pic-face wp-pic-front">
			<a class="wp-pic-logo" href="<?php echo $wppic_data->url ?>" <?php echo $bgImage ?> target="_blank" title="<?php _e('WordPress.org Plugin Page', 'wppic-translate') ?>"></a>
			<p class="wp-pic-name"><?php echo $wppic_data->name ?></p>
			<p class="wp-pic-author"><?php _e('Author(s):', 'wppic-translate') ?> <?php echo $wppic_data->author ?></p>
			<div class="wp-pic-bottom">
				<div class="wp-pic-bar">
					<span class="wp-pic-rating"><?php echo $wppic_data->rating ?>%<em><?php _e('Ratings', 'wppic-translate') ?></em></span>
					<span class="wp-pic-downloaded"><?php echo number_format($wppic_data->downloaded, 0, ',', ',') ?><em><?php _e('Downloads', 'wppic-translate') ?></em></span>
					<span class="wp-pic-version"><?php echo $wppic_data->version ?><em><?php _e('Version', 'wppic-translate') ?></em></span>
				</div>
				<div class="wp-pic-download">
					<span><?php _e('Download', 'wppic-translate') ?></span>
				</div>
			</div>
		</div>
		<div class="wp-pic-face wp-pic-back">
			<a class="wp-pic-dl-ico" href="<?php echo $wppic_data->download_link ?>" title="<?php _e('Direct download', 'wppic-translate') ?>"></a>
			<p><a class="wp-pic-dl-link" href="<?php echo $wppic_data->download_link ?>" title="<?php _e('Direct download', 'wppic-translate') ?>"><?php echo basename($wppic_data->download_link) ?></a></p>
			<a class="wp-pic-preview" href="https://wp-themes.com/<?php echo $slug ?>" title="<?php _e('Theme Preview', 'wppic-translate') ?>" target="_blank"><span><?php _e('Theme Preview', 'wppic-translate') ?></span></a>
			<p class="wp-pic-updated"><span><?php _e('Last Updated:', 'wppic-translate') ?></span> <?php echo date(get_option( 'date_format' ), strtotime($wppic_data->last_updated)) ?></p>
			<div class="wp-pic-bottom">
				<div class="wp-pic-bar">
					<span class="wp-pic-rating"><?php echo $wppic_data->rating ?>%<em><?php _e('Ratings', 'wppic-translate') ?></em></span>
					<span class="wp-pic-downloaded"><?php echo number_format($wppic_data->downloaded, 0, ',', ',') ?><em><?php _e('Downloads', 'wppic-translate') ?></em></span>
					<span class="wp-pic-version"><?php echo $wppic_data->version ?><em><?php _e('Version', 'wppic-translate') ?></em></span>
				</div>
				<a class="wp-pic-wporg" href="<?php echo $wppic_data->url ?>" target="_blank" title="<?php _e('WordPress.org Theme Page', 'wppic-translate') ?>"><?php _e('WordPress.org Theme Page', 'wppic-translate') ?></a>
			</div>
			<div class="wp-pic-asset-bg" <?php echo $banner ?>>
				<div class="wp-pic-asset-bg-title"><span><?php echo $wppic_data->name ?></span></div>
			</div>
			<div class="wp-pic-goback" title="<?php _e('Back', 'wppic-translate') ?>"><span></span></div>
		</div>
	</div>
<?php //end of template