<?php
add_filter( 'wppic_add_api_parser', 'wppic_theme_api_parser', 9, 3 );
add_filter( 'wppic_add_template', 'wppic_theme_template', 9, 2 );
add_filter( 'wppic_add_mce_type', 'wppic_theme_mce_type' );
add_filter( 'wppic_add_list_form', 'wppic_theme_list_form' );
add_filter( 'wppic_add_widget_type', 'wppic_theme_widget_type' );
add_filter( 'wppic_add_list_valdiation', 'wppic_theme_list_valdiation' );


/***************************************************************
 * Fetching themes data with WordPress.org Theme API
 ***************************************************************/
function wppic_theme_api_parser($wppic_data, $type, $slug ){

	if ( $type == 'theme') {

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
			'downloaded' 	=> number_format($theme_info->downloaded, 0, ',', ','),
			'last_updated' 	=> date(get_option( 'date_format' ), strtotime($theme_info->last_updated)),
			'homepage' 		=> $theme_info->homepage,
			'download_link' => $theme_info->download_link
		);

	}
	
	return $wppic_data;
	
}


/***************************************************************
 * Theme shortcode template prepare
 ***************************************************************/
function wppic_theme_template($content, $data){
	
	$type = $data[0];
	$wppic_data = $data[1];
	$image = $data[2];
	
	if ( $type == 'theme') {

		//ScreenShot URL
		if( !empty($image) ){
			$bgImage = 'style="background-image: url(' . $image . ');"';
		} else if( !empty( $wppic_data->screenshot_url ) ){
			$bgImage = 'style="background-image: url(https:' . esc_attr( $wppic_data->screenshot_url ) . ');"';
		} else {
			$bgImage = 'data="no-image"';
		}

		//load custom user template if exists
		ob_start();
		$WPPICtemplatefile = '/wppic-templates/wppic-template-theme.php';
		if ( file_exists( get_template_directory() . $WPPICtemplatefile ) ) { 
			include_once( get_template_directory() . $WPPICtemplatefile ); 
		} else {
			include_once( WPPIC_PATH . $WPPICtemplatefile ); 
		}
		$content .= ob_get_clean();
	
	}
	
	return $content;
	
}


/***************************************************************
 * Add theme type to mce list
 ***************************************************************/
function wppic_theme_mce_type( $parameters ){
	$parameters['types'][] = array( 'text' => 'Theme', 'value' => 'theme' );
	return $parameters;
}


/***************************************************************
 * Theme input option list
 ***************************************************************/
function wppic_theme_list_form( $parameters ){
	$parameters[] = array('theme-list', __('Add a theme', 'wppic-translate'), __('Please refer to the theme URL on wordpress.org to determine its slug', 'wppic-translate'), 'https://wordpress.org/themes/<strong>THE-SLUG</strong>/' );
	return $parameters;
}


/***************************************************************
 * Theme input validation
 ***************************************************************/
function wppic_theme_list_valdiation( $parameters ){
	$parameters[] = array('theme-list', __('is not a valid theme name format. This key has been deleted.', 'wppic-translate'), '/^[a-z0-9\-]+$/');
	return $parameters;
}


/***************************************************************
 * Theme widget list
 ***************************************************************/
function wppic_theme_widget_type( $parameters ){
	$parameters[] = array( 'theme', 'theme-list', __('Themes', 'wppic-translate') );
	return $parameters;
}