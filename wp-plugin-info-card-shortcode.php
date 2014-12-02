<?php
/***************************************************************
 * Enqueue custom CSS
 ***************************************************************/
function wppic_register_sripts() {
	wp_register_style( 'wppic-style', WPPIC_URL . 'css/wppic-style.min.css', NULL, NULL);
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
			"type" => '',  			//plugin | theme
			"slug" => '',  			//plugin slug name
			"image" => '',  		//image url to replace WP logo (175px X 175px)
			"align" => '',  		//center|left|right
			"containerid" => '',  	//custom Div ID (could be use for anchor)
			"margin" => '',  		//custom container margin - eg: "15px 0"
			"clear" => '',  		//clear float before or after the card: before|after
			"expiration" => '',  	//transient duration in minutes - 0 for never expires
			"ajax" => '',  			//load plugin data async whith ajax: yes|no (default: no)
			"scheme" => '',			//color scheme : default|scheme1->scheme10 (default: empty)
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
		$type = trim($type);
		$slug = trim($slug);
		$image = trim($image);
		$containerid = trim($containerid);
		$margin = trim($margin);
		$clear = trim($clear);
		$expiration = trim($expiration);
		$ajax = trim($ajax);
		$custom = trim($custom);


		if(empty($type)){
			$type = 'plugin';
		}

		if(!empty($custom)){
			$wppic_data = wp_Plugin_API_Parser($type, $slug, $expiration);
			
			if(empty($wppic_data->name))
			return '<strong>' . __('Plugin not found:', 'wppic-translate') . ' "' . $slug . '" ' . __('does not exist.', 'wppic-translate') . '</strong>';
		
			if(!empty($wppic_data->$custom))
			$content .= $wppic_data->$custom;
			
		} else {
			
			//Ajax requiered data
			$ajaxClass = '';
			$ajaxData = '';
			if($ajax == 'yes'){
				$ajaxClass = ' wp-pic-ajax';
				$ajaxData = 'data-type="' . $type . '" data-slug="' . $slug . '" data-image="' . $image . '" data-expiration="' . $expiration . '" ';
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

			//Color scheme
			if(empty($scheme)){
				$wppicSettings = get_option('wppic_settings');
				$scheme = $wppicSettings['colorscheme'];
				if(	$scheme == 'default'){
					$scheme = '';
				}
			}

			//Output
			if($clear == 'before')
			$content .= '<div style="clear:both"></div>';
			if($alignCenter)
			$content .= '<div class="wp-pic-center">';
			//Data attribute for ajax call
			$content .= '<div class="wp-pic ' . $type . ' ' . $scheme . ' ' . $ajaxClass . '" ' . $containerid . $style . $ajaxData .' >';

			if($ajax != 'yes'){
				$content .= wppic_shortcode_content($type, $slug, $image, $expiration);
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
function wppic_shortcode_content($type=NULL, $slug=NULL, $image=NULL, $expiration=NULL){
	
	$ajaxFadeIn = '';

	if(!empty($_POST['type'])){
		$type = $_POST['type'];
		$ajaxFadeIn = 'style="display:none"';
	} 
	if(!empty($_POST['slug'])){
		$slug = $_POST['slug'];
	} 
	if(!empty($_POST['image'])){
		$image = $_POST['image'];
	} 
	if(!empty($_POST['expiration'])){
		$expiration = $_POST['expiration'];
	} 

	$wppic_data = wp_Plugin_API_Parser($type, $slug, $expiration);
		
	
	//if plugin does not exists
	if(empty($wppic_data->name)){
		
		$error .= '<div class="wp-pic-flip" ' . $ajaxFadeIn . '>';
			$error .= '<div class="wp-pic-face wp-pic-front">';
				if ( $type == 'theme') {
					$error .=  '<span class="wp-pic-no-plugin">' . __('Theme not found:', 'wppic-translate');
				} else {
					$error .=  '<span class="wp-pic-no-plugin">' . __('Plugin not found:', 'wppic-translate');
				}
				$error .=  '</br><i>"' . $slug . '"</i></br>' . __('does not exist.', 'wppic-translate') . '</span>';
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

	//Load theme or plugin template
	ob_start();
	$content = '';
	if ( $type == 'theme') {
		
		//load custom user template if exists
		if ( file_exists(get_template_directory() . '/wppic/wppic-template-theme.php')) { 
			include_once(get_template_directory() . '/wppic/wppic-template-theme.php'); 
		} else {
			include_once(WPPIC_PATH . '/templates/wppic-template-theme.php'); 
		}
	
	} else {
		
		//load custom user template if exists
		if ( file_exists(get_template_directory() . '/wppic/wppic-template-plugin.php')) { 
			include_once(get_template_directory() . '/wppic/wppic-template-plugin.php'); 
		} else {
			include_once(WPPIC_PATH . '/templates/wppic-template-plugin.php'); 
		}
	
	}
	
	$content .= ob_get_clean();
	
	if(!empty($_POST['slug'])) {
		echo $content;
		die();
	} else {
		return $content;
	}
}
add_action( 'wp_ajax_async_wppic_shortcode_content', 'wppic_shortcode_content' );
add_action( 'wp_ajax_nopriv_async_wppic_shortcode_content', 'wppic_shortcode_content' );