<?php
add_filter( 'wppic_add_api_parser', 'wppic_plugin_api_parser', 9, 3 );
add_filter( 'wppic_add_template', 'wppic_plugin_template', 9, 2 );
add_filter( 'wppic_add_mce_type', 'wppic_plugin_mce_type' );
add_filter( 'wppic_add_list_form', 'wppic_plugin_list_form' );
add_filter( 'wppic_add_widget_type', 'wppic_plugin_widget_type' );
add_filter( 'wppic_add_list_valdiation', 'wppic_plugin_list_valdiation' );


/***************************************************************
 * Fetching plugins data with WordPress.org Plugin API
 ***************************************************************/
function wppic_plugin_api_parser($wppic_data, $type, $slug ){

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
			'downloaded' 	=> number_format($plugin_info->downloaded, 0, ',', ','),
			'last_updated' 	=> date(get_option( 'date_format' ), strtotime($plugin_info->last_updated)),
			'download_link' => $plugin_info->download_link,
		);
	
	}
	
	return $wppic_data;
	
}


/***************************************************************
 * Plugin shortcode template prepare
 ***************************************************************/
function wppic_plugin_template($content, $data){
	
	$type = $data[0];
	$wppic_data = $data[1];
	$image = $data[2];
	$layout = '-' . $data[3];
	
	if ( $type == 'plugin') {

		//load custom user template if exists
		$WPPICtemplatefile = '/wppic-templates/wppic-template-plugin';
		ob_start();
		if ( file_exists( get_stylesheet_directory() . $WPPICtemplatefile .  $layout . '.php' ) ) { 
			include( get_stylesheet_directory() . $WPPICtemplatefile .  $layout . '.php' ); 
		} else if ( file_exists(WPPIC_PATH . $WPPICtemplatefile .  $layout . '.php' ) ) { 
			include( WPPIC_PATH . $WPPICtemplatefile .  $layout . '.php' ); 
		} else {
			include( WPPIC_PATH . $WPPICtemplatefile . '.php' ); 
		}
		$content .= ob_get_clean();

	}
	
	return $content;
	
}


/***************************************************************
 * Add plugin type to mce list
 ***************************************************************/
function wppic_plugin_mce_type( $parameters ){
	$parameters['types'][] = array( 'text' => 'Plugin', 'value' => 'plugin' );
	return $parameters;
}


/***************************************************************
 * Plugin input option list
 ***************************************************************/
function wppic_plugin_list_form( $parameters ){
	$parameters[] = array('list', __('Add a plugin', 'wppic-translate'), __('Please refer to the plugin URL on wordpress.org to determine its slug', 'wppic-translate'), 'https://wordpress.org/plugins/<strong>THE-SLUG</strong>/' );
	return $parameters;
}


/***************************************************************
 * Plugin input validation
 ***************************************************************/
function wppic_plugin_list_valdiation( $parameters ){
	$parameters[] = array('list', __('is not a valid plugin name format. This key has been deleted.', 'wppic-translate'), '/^[a-z0-9\-]+$/');
	return $parameters;
}


/***************************************************************
 * Plugin widget list
 ***************************************************************/
function wppic_plugin_widget_type( $parameters ){
	$parameters[] = array( 'plugin', 'list', __('Plugins', 'wppic-translate') );
	return $parameters;
}