<?php
/***************************************************************
 * Fetching plugin data through WordPress Plugin API
 ***************************************************************/
if (!function_exists('WP_Plugin_API_Parser')) {
	function WP_Plugin_API_Parser($type, $slug, $expiration = 720, $widget = NULL){
		
		if($widget == true){
			$widget = 'widget_';
			$expiration = 10;
		} else {
			$widget = '';
		}
		
		$wppic_data = get_transient( 'wppic_'. $widget . $type . '_' . preg_replace('/\-/', '_', $slug) );
		
		//check if expiration is numeric, only digit char
		if(empty($expiration) || !ctype_digit($expiration))
			$expiration = 720;
			
		if ( false === $wppic_data) {
			
			if ( $type == 'plugin') {
				
				require_once(ABSPATH . 'wp-admin/includes/plugin-install.php');
				$plugin_info = $api = plugins_api( 'plugin_information', array(
					'slug' => $slug,
					'is_ssl' => is_ssl(),
					'fields' => array( 'sections' => false, 'tags' => false , 'icons' => true, 'banners' => true )
				) );
			
				$wppic_data  = (object) array( 
					'slug' 			=> $slug,
					'url' 			=> 'https://wordpress.org/plugins/'.$slug.'/',
					'name' 			=> $plugin_info->name,
					'icons' 		=> $plugin_info->icons,
					'banners' 		=> $plugin_info->banners,
					'version' 		=> $plugin_info->version,
					'author' 		=> $plugin_info->author,
					'requires' 		=> $plugin_info->requires,
					'rating' 		=> $plugin_info->rating,
					'num_ratings' 	=> $plugin_info->num_ratings,
					'downloaded' 	=> $plugin_info->downloaded,
					'last_updated' 	=> $plugin_info->last_updated,
					'download_link' => $plugin_info->download_link,
				);
			
			} else if ( $type == 'theme') {

				require_once( ABSPATH . 'wp-admin/includes/theme.php' );
				$theme_info = themes_api('theme_information', array(
						'slug' => $slug,
						'fields' => array( 'sections' => false, 'tags' => false ) 
					) 
				);

				$wppic_data  = (object) array( 
					'slug' 			=> $slug,
					'url'			=> 'https://wordpress.org/themes/'.$slug.'/',
					'name' 			=> $theme_info->name,
					'version' 		=> $theme_info->version,
					'author' 		=> '<a href="https://profiles.wordpress.org/' . $theme_info->author . '/" target="_blanck" title="' . $theme_info->author . '">' . $theme_info->author . '</a>',
					'screenshot_url'=> $theme_info->screenshot_url,
					'rating' 		=> $theme_info->rating,
					'num_ratings' 	=> $theme_info->num_ratings,
					'downloaded' 	=> $theme_info->downloaded,
					'last_updated' 	=> $theme_info->last_updated,
					'homepage' 		=> $theme_info->homepage,
					'download_link' => $theme_info->download_link
				);
			

			} else {
				
				$wppic_data = false;
				
			}
			
			//Transient duration 5min
			set_transient( 'wppic_'. $widget . $type . '_' . preg_replace('/\-/', '_', $slug), $wppic_data, $expiration*60);
		}
		
		return $wppic_data;
	}
}