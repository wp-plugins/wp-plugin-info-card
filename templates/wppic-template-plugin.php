<?php
	//Fix for requiered version with extra info : WP 3.9, BP 2.1+
	if(is_numeric($wppic_data->requires)){
		$wppic_data->requires = 'WP ' . $wppic_data->requires . '+';
	}
		
	//Icon URL
	$icon = '';
	$bgImage = '';
	if ( !empty( $wppic_data->icons['svg'] ) ) {
		$icon = $wppic_data->icons['svg'];
	} elseif ( !empty( $wppic_data->icons['2x'] ) ) {
		$icon = $wppic_data->icons['2x'];
	} elseif ( !empty( $wppic_data->icons['1x'] ) ) {
		$icon = $wppic_data->icons['1x'];
	} else {
		$icon = $wppic_data->icons['default'];
	}
	if( !empty($image) ){
		$bgImage = 'style="background-image:  none, url(' . $image . '), url(' . WPPIC_URL . '/img/wp-pic-sprite.png);"';
	} else {
		if( empty($icon) ){
			$bgImage = 'style="background-image:  url(' . WPPIC_URL . '/img/wp-pic-sprite.png);"';
		} else {
			$bgImage = 'style="background-image:  none, url(https:' . esc_attr( $icon ) . '), url(' . WPPIC_URL . '/img/wp-pic-sprite.png);"';
		}
	}

	//Plugin banner
	$banner = '';
	if ( !empty( $wppic_data->banners['low'] ) ) {
		$banner = 'style="background-image: url(https:' . esc_attr( $wppic_data->banners['low'] ) . ');"';
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
			<p class="wp-pic-updated"><span><?php _e('Last Updated:', 'wppic-translate') ?></span> <?php echo date(get_option( 'date_format' ), strtotime($wppic_data->last_updated)) ?></p>
			<div class="wp-pic-bottom">
				<div class="wp-pic-bar">
					<span class="wp-pic-rating"><?php echo $wppic_data->rating ?>%<em><?php _e('Ratings', 'wppic-translate') ?></em></span>
					<span class="wp-pic-downloaded"><?php echo number_format($wppic_data->downloaded, 0, ',', ',') ?><em><?php _e('Downloads', 'wppic-translate') ?></em></span>
					<span class="wp-pic-requires"><?php echo $wppic_data->requires ?><em><?php _e('Requires', 'wppic-translate') ?></em></span>
				</div>
				<a class="wp-pic-wporg" href="<?php echo $wppic_data->url ?>" target="_blank" title="<?php _e('WordPress.org Plugin Page', 'wppic-translate') ?>"><?php _e('WordPress.org Plugin Page', 'wppic-translate') ?></a>
			</div>
			<div class="wp-pic-asset-bg" <?php echo $banner ?>>
				<div class="wp-pic-asset-bg-title"><span><?php echo $wppic_data->name ?></span></div>
			</div>
			<div class="wp-pic-goback" title="<?php _e('Back', 'wppic-translate') ?>"><span></span></div>
		</div>
	</div>
<?php //end of template