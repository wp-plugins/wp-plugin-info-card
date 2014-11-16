<?php
/***************************************************************
 * Enqueue custom CSS
 ***************************************************************/
function wppic_register_sripts() {
	wp_register_style( 'wppic-style', plugins_url('css/wppic-style.min.css', __FILE__ ), NULL, NULL);
	wp_register_script( 'wppic-script', WPPIC_URL . 'js/wppic-script.min.js', array( 'jquery' ),  NULL, true);
}
function wppic_print_sripts() {
	global $wppicSripts;
	if ( ! $wppicSripts )
		return;

	
	if ($wppicSripts == 'ajax'){
		echo '<script>// <![CDATA[
			  var wppicAjax = { ajaxurl : "'.admin_url('admin-ajax.php').'" };
		 // ]]></script>';
	}
	
	wp_print_styles('wppic-style');
	wp_print_scripts('wppic-script');


}
add_action('init', 'wppic_register_sripts');
add_action('wp_footer', 'wppic_print_sripts');


/***************************************************************
 * Main shortcode function
 ***************************************************************/
if (!function_exists('wppic_shortcode_function')) {
	function wppic_shortcode_function( $atts, $content="" ) {
		
		//Retrieve & extract shorcode parameters
		extract(shortcode_atts(array(  
			"slug" => '',  			//plugin slug name
			"image" => '',  		//image url to replace WP logo (175px X 175px)
			"logo" => '',			//128×128.jpg|256×256.jpg|128×128.png|256×256.png|svg|no
			"banner" => '',  		//jpg|png|no
			"align" => '',  		//center|left|right
			"containerid" => '',  	//custom Div ID (could be use for anchor)
			"margin" => '',  		//custom container margin - eg: "15px 0"
			"clear" => '',  		//clear float before or after the card: before|after
			"expiration" => '',  	//transient duration in minutes - 0 for never expires
			"ajax" => '',  			//load plugin data async whith ajax: yes|no (default: no)
			"custom" => '',  		//value to print : url|name|version|author|requires|rating|num_ratings|downloaded|last_updated|download_link
		), $atts));
		
		//Global var to enqueue scripts + ajax param is set to yes
		global $wppicSripts;
		if($ajax == 'yes'){
			$wppicSripts = 'ajax';
		} else {
			$wppicSripts = true;
		}
				
		//Remove unnecessary spaces
		$slug = trim($slug);
		$image = trim($image);
		$logo = trim($logo);
		$banner = trim($banner);
		$containerid = trim($containerid);
		$margin = trim($margin);
		$clear = trim($clear);
		$expiration = trim($expiration);
		$ajax = trim($ajax);
		$custom = trim($custom);

		if(!empty($custom)){
			$wppic_plugin_data = wp_Plugin_API_Parser($slug, $expiration);
			
			if(empty($wppic_plugin_data->name))
			return '<strong>' . __('Plugin not found:', 'wppic-translate') . ' "' . $slug . '" ' . __('does not exist.', 'wppic-translate') . '</strong>';
		
			if(!empty($wppic_plugin_data->$custom))
			$content .= $wppic_plugin_data->$custom;
			
		} else {
			
			//Ajax requiered data
			$ajaxClass = '';
			$ajaxData = '';
			if($ajax == 'yes'){
				$ajaxClass = 'wp-pic-ajax';
				$ajaxData = 'data-slug="' . $slug . '" data-image="' . $image . '" data-logo="' . $logo . '" data-banner="' . $banner . '"  data-expiration="' . $expiration . '" ';
			}

			//Align card
			if( $align == 'right' || $align == 'left') {
				$align = 'float: ' . $align . '; ';
			}
			$alignCenter = false;
			if( $align == 'center') {
				$alignCenter = true;
				$align = '';
			}
			
			//Extra container ID
			if(!empty($containerid)){
				$containerid = ' id="' . $containerid . '"';
			} else {
				$containerid = ' id="wp-pic-'. $slug . '"';
			}

			//Custom container margin
			if(!empty($margin)){
				$margin = 'margin:' . $margin . ';';
			}

			//Custom style
			$style = '';
			if(!empty($margin) || !empty($align)){
				$style = 'style="' . $align . $margin . '"';
			}

			//Output
			if($clear == 'before')
			$content .= '<div style="clear:both"></div>';
			if($alignCenter)
			$content .= '<div class="wp-pic-center">';
			$content .= '<div class="wp-pic ' . $ajaxClass . '" ' . $containerid . $style . $ajaxData .' >';

			if($ajax != 'yes'){
				$content .= wppic_shortcode_content( $slug, $image, $logo, $banner, $expiration);
			} else {
				$content .= '<div class="wp-pic-body-loading"><div class="signal"></div></div>';
			}

			$content .= '</div>';
			//Align center
			if($alignCenter)
			$content .= '</div>';
			if($clear == 'after')
			$content .= '<div style="clear:both"></div>';

		}
		return $content;
			
	} //end of wppic_Shortcode
	
	add_shortcode( 'wp-pic', 'wppic_shortcode_function' );
}


/***************************************************************
 * Content shortcode function
 ***************************************************************/
function wppic_shortcode_content($slug=NULL, $image=NULL, $logo=NULL, $banner=NULL, $expiration=NULL){
	
	$ajaxFadeIn = '';

	if(!empty($_POST['slug'])){
		$slug = $_POST['slug'];
		$ajaxFadeIn = 'style="display:none"';
	} 
	if(!empty($_POST['image'])){
		$image = $_POST['image'];
	} 
	if(!empty($_POST['logo'])){
		$logo = $_POST['logo'];
	} 
	if(!empty($_POST['banner'])){
		$banner = $_POST['banner'];
	} 
	if(!empty($_POST['expiration'])){
		$expiration = $_POST['expiration'];
	} 

	$wppic_plugin_data = wp_Plugin_API_Parser($slug, $expiration);
	
	
	//if plugin does not exists
	if(empty($wppic_plugin_data->name)){
		
		$error .= '<div class="wp-pic-flip" ' . $ajaxFadeIn . '>';
			$error .= '<div class="wp-pic-face wp-pic-front">';
				$error .=  '<span class="wp-pic-no-plugin">' . __('Plugin not found:', 'wppic-translate') . '</br><i>"' . $slug . '"</i></br>' . __('does not exist.', 'wppic-translate') . '</span>';
				$error .=  	'<div class="monster-wrapper">
								<div class="eye-left"></div>
								<div class="eye-right"></div>
								<div class="mouth">
									<div class="tooth-left"></div>
									<div class="tooth-right"></div>
								</div>
								<div class="arm-left"></div>
								<div class="arm-right"></div>
								<div class="dots"></div>
							</div>';
			$error .= '</div>';
		$error .= '</div>';
		
		if(!empty($_POST['slug'])) {
			echo $error;
			die();
		} else {
			return $error;
		}
		
	}

	//Fix for requiered version with extra info : WP 3.9, BP 2.1+
	if(is_numeric($wppic_plugin_data->requires)){
		$wppic_plugin_data->requires = 'WP ' . $wppic_plugin_data->requires . '+';
	}
		
	//Provided logo URL
	$bgImage = '';
	if(!empty($image)){
		$bgImage = 'style="background-image: url(' . $image . ');"';
	} else {
		if($logo == '128x128.jpg' || $logo == '128x128.png' || $logo == '256x256.jpg' || $logo == '256x256.png'){
			$bgImage = 'style="background-image:  url(http://ps.w.org/' . $slug . '/assets/icon-' . $logo . ');"';
		} else if($logo == 'svg'){
			$bgImage = 'style="background-image:  url(http://ps.w.org/' . $slug . '/assets/icon.svg)"';
		} else if($logo == 'no'){
			$bgImage = 'style="background-image:  url(' . plugins_url('/img/wp-pic-sprite.png', __FILE__ ) . ');"';
		} else {
			$bgImage = 'style="background-image:  none, url(http://ps.w.org/' . $slug . '/assets/icon.svg), url(' . plugins_url('/img/wp-pic-sprite.png', __FILE__ ) . ');"';
		}
	}

	//Specify banner extension
	if(!empty($banner)){
		if($banner == "no"){
			$banner = '';
		} else {
			$banner = 'style="background-image: url(http://ps.w.org/' . $slug . '/assets/banner-772x250.' . $banner . ');"';
		}
	} else {
		$banner = 'style="background-image:  none, url(http://ps.w.org/' . $slug . '/assets/banner-772x250.png), url(http://ps.w.org/' . $slug . '/assets/banner-772x250.jpg)"';
	}

	$content .= '<div class="wp-pic-flip" ' . $ajaxFadeIn . '>';
		$content .= '<div class="wp-pic-face wp-pic-front">';
			$content .= '<a class="wp-pic-logo" href="' . $wppic_plugin_data->url . '" ' . $bgImage . ' target="_blank" title="' . __('WordPress.org Plugin Page', 'wppic-translate') . '"></a>';
			$content .= '<p class="wp-pic-name">' . $wppic_plugin_data->name .'</p>';
			$content .= '<p class="wp-pic-author">' . __('Author(s):', 'wppic-translate') . ' ' . $wppic_plugin_data->author .'</p>';
			$content .= '<div class="wp-pic-bottom">';
				$content .= '<div class="wp-pic-bar">';
					$content .= '<span class="wp-pic-rating">' . $wppic_plugin_data->rating . '%<em>' . __('Ratings', 'wppic-translate') . '</em></span>';
					$content .= '<span class="wp-pic-downloaded">' . number_format($wppic_plugin_data->downloaded, 0, ',', ',') . '<em>' . __('Downloads', 'wppic-translate') . '</em></span>';
					$content .= '<span class="wp-pic-requires">' . $wppic_plugin_data->requires . '<em>' . __('Requires', 'wppic-translate') . '</em></span>';
				$content .= '</div>';
				$content .= '<div class="wp-pic-download">';
					$content .= '<span>' . __('Download', 'wppic-translate') . '</span>';
				$content .= '</div>';
			$content .= '</div>';
		$content .= '</div>';
		$content .= '<div class="wp-pic-face wp-pic-back">';
			$content .= '<a class="wp-pic-dl-ico" href="' . $wppic_plugin_data->download_link . '" title="' . __('Direct download', 'wppic-translate') . '"></a>';
			$content .= '<p><a class="wp-pic-dl-link" href="' . $wppic_plugin_data->download_link . '" title="' . __('Direct download', 'wppic-translate') . '">' . basename($wppic_plugin_data->download_link) . '</a></p>';
			$content .= '<p class="wp-pic-version"><span>' . __('Current Version:', 'wppic-translate') . '</span> ' . $wppic_plugin_data->version .'</p>';
			$content .= '<p class="wp-pic-updated"><span>' . __('Last Updated:', 'wppic-translate') . '</span> ' . date(get_option( 'date_format' ), strtotime($wppic_plugin_data->last_updated)) .'</p>';
			$content .= '<div class="wp-pic-bottom">';
				$content .= '<div class="wp-pic-bar">';
					$content .= '<span class="wp-pic-rating">' . $wppic_plugin_data->rating . '%<em>' . __('Ratings', 'wppic-translate') . '</em></span>';
					$content .= '<span class="wp-pic-downloaded">' . number_format($wppic_plugin_data->downloaded, 0, ',', ',') .'<em>' . __('Downloads', 'wppic-translate') . '</em></span>';
					$content .= '<span class="wp-pic-requires">' . $wppic_plugin_data->requires . '<em>' . __('Requires', 'wppic-translate') . '</em></span>';
				$content .= '</div>';
				$content .= '<a class="wp-pic-wporg" href="' . $wppic_plugin_data->url . '" target="_blank" title="' . __('WordPress.org Plugin Page', 'wppic-translate') . '">' . __('WordPress.org Plugin Page', 'wppic-translate') . '</a>';
			$content .= '</div>';
			$content .= '<div class="wp-pic-asset-bg" ' . $banner . '>';
				$content .= '<div class="wp-pic-asset-bg-title"><span>' . $wppic_plugin_data->name . '</span></div>';
			$content .= '</div>';
			$content .= '<div class="wp-pic-goback" title="' . __('Back', 'wppic-translate') . '"></div>';
		$content .= '</div>';
	$content .= '</div>';
	
	
	if(!empty($_POST['slug'])) {
		echo $content;
		die();
	} else {
		return $content;
	}
}
add_action( 'wp_ajax_async_wppic_shortcode_content', 'wppic_shortcode_content' );
add_action( 'wp_ajax_nopriv_async_wppic_shortcode_content', 'wppic_shortcode_content' );
