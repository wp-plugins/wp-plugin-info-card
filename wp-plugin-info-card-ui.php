<?php
/***************************************************************
 * Hooks custom TinyMCE button function
 ***************************************************************/ 
function wppic_add_mce_button() {

	if ( !current_user_can( 'edit_posts' ) && !current_user_can( 'edit_pages' ) )
	return;
	
	if ( 'true' == get_user_option( 'rich_editing' ) ) {
		
		add_filter( 'mce_external_plugins', 'wppic_add_tinymce_plugin' );
		add_filter( 'mce_buttons', 'wppic_register_mce_button' );
		
		// Load stylesheet for tinyMCE button only
		wp_enqueue_style( 'wppic-admin-css', WPPIC_URL . 'css/wppic-admin-style.css', array(), NULL, NULL);
		
	}
	
}
add_action('admin_head', 'wppic_add_mce_button');


/***************************************************************
 * Load custom js options - TinyMCE API
 ***************************************************************/ 
function wppic_add_tinymce_plugin( $plugin_array ) {
	$plugin_array['wppic_mce_button'] = WPPIC_URL . '/js/wppic-mce-ui.js';
	return $plugin_array;
}


/***************************************************************
 * Register new button in the editor
 ***************************************************************/ 
function wppic_register_mce_button( $buttons ) {
	array_push( $buttons, 'wppic_mce_button' );
	return $buttons;
}