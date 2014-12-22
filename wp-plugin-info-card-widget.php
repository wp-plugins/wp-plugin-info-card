<?php
/***************************************************************
 * Enqueue style on dashboard if widget is activated
 * Action is call during widget registration
 ***************************************************************/ 
function wppic_widget_enqueue($hook) {
    if ( 'index.php' != $hook ) {
        return;
    }
	//Enqueue sripts and style
	wppic_admin_scripts();
	wppic_admin_css();
}


/***************************************************************
 * Register Dashboard Widget
 ***************************************************************/ 
if (!function_exists('wppic_dashboard_widgets')) {
	function wppic_add_dashboard_widgets() {
		$wppicSettings = get_option('wppic_settings');
		if( isset($wppicSettings['widget'] ) && $wppicSettings['widget'] == true ){
			wp_add_dashboard_widget('wppic-dashboard-widget','<img src="' . WPPIC_URL . 'img/wppic.svg" class="wppic-logo" alt="b*web" style="display:none"/>&nbsp;&nbsp;' . WPPIC_NAME . ' board', 'wppic_widgets');
			add_action( 'admin_enqueue_scripts', 'wppic_widget_enqueue' );
		}
	}
}
add_action('wp_dashboard_setup', 'wppic_add_dashboard_widgets');


/***************************************************************
 * Dashboard Widget function 
 ***************************************************************/  
function wppic_widgets() {
	
	$listState = false;
	$ajaxClass = '';

	$wppicSettings = get_option('wppic_settings');
	if( isset($wppicSettings['ajax']) && $wppicSettings['ajax'] == true )
		$ajaxClass = 'ajax-call';
	
	
	$wppicTypes = array();
	$wppicTypes = apply_filters( 'wppic_add_widget_type', $wppicTypes );
	
	$content = '<div class="wp-pic-list ' . $ajaxClass . '">';

	foreach( $wppicTypes as $wppicType ){
		
		$rows = array();
		
		if( isset($wppicSettings[$wppicType[1]] ) && !empty($wppicSettings[$wppicType[1]] ) ){
			
			$listState = true;
			$otherLists = false;
			
			foreach( $wppicTypes as $wppicList ){
				if( $wppicType[1] != $wppicList[1] )
				$rows[] = $wppicList[1];
			}

			foreach( $rows as $row ){
				if( isset($wppicSettings[$row] ) && !empty($wppicSettings[$row] ) ){
					$otherLists = true;					
				}
			}

			if( $otherLists ){
				$content .= '<h4>' . $wppicType[2] . '</h4>';
			}
		
			if( isset($wppicSettings['ajax']) && $wppicSettings['ajax'] == true ){
				$content .= '<div class="wp-pic-loading" style="background-image: url(' . admin_url() . 'images/spinner-2x.gif);" data-type="' . $wppicType[0] . '" data-list="' . htmlspecialchars( json_encode( ( $wppicSettings[$wppicType[1]] ) ), ENT_QUOTES, 'UTF-8' ) . '"></div>';
			} else {
				$content .= wppic_widget_render( $wppicType[0], $wppicSettings[$wppicType[1]] );
			}
			
		}
		
	}
		
	//Nothing found
	if( !$listState ) {

		$content .= '<div class="wp-pic-item" style="display:block;">';
		$content .= '<span class="wp-pic-no-item"><a href="admin.php?page=' . WPPIC_ID . '">' . __('Nothing found, please add at least one item in the WP Plugin Info Card settings page.', 'wppic-translate') . '</a></span>';
		$content .= '</div>';
		
	}

	$content .= '</div>';
	
	echo $content;
		
} //end of wppic_widgets


/***************************************************************
 * Widget Ajax callback 
 ***************************************************************/  
function wppic_widget_render($type=NULL, $slugs=NULL){

	if( isset( $_POST['wppic-type'] ) && !empty( $_POST['wppic-type'] ) ){
		$type = $_POST['wppic-type'];
	} 
	
	if( isset( $_POST['wppic-list'] ) && !empty( $_POST['wppic-list'] ) ){
		$slugs = array($_POST['wppic-list']);
	} 
	
	$content = '';

	if(!empty($slugs)) {
		foreach($slugs as $slug){
			$wppic_plugin_data = wppic_api_parser($type, $slug, '5', true);

			if(!empty($wppic_plugin_data->name)){
				
				$content .= '<div class="wp-pic-item ' . $slug . '">';
				$content .= '<a class="wp-pic-widget-name" href="' . $wppic_plugin_data->url . '" target="_blank" title="' . __('WordPress.org Plugin Page', 'wppic-translate') . '">' . $wppic_plugin_data->name .'</a>';
				$content .= '<span class="wp-pic-widget-rating"><span>' . __('Ratings:', 'wppic-translate') . '</span> ' . $wppic_plugin_data->rating .'%';
				if( !empty( $wppic_plugin_data->num_ratings ) )
					$content .= ' (' . $wppic_plugin_data->num_ratings . ' votes)';
				$content .= '</span>';
				$content .= '<span class="wp-pic-widget-downloaded"><span>' . __('Downloads:', 'wppic-translate') . '</span> ' . $wppic_plugin_data->downloaded .'</span>';
				$content .= '<p class="wp-pic-widget-updated"><span>' . __('Last Updated:', 'wppic-translate') . '</span> ' . $wppic_plugin_data->last_updated;
				if( !empty( $wppic_plugin_data->version ) )
					$content .= ' (v.' . $wppic_plugin_data->version .')';
				$content .= '</p>';
				$content .= '</div>';
			
			} else {
				
				$content .= '<div class="wp-pic-item ' . $slug . '">';
				$content .= '<span class="wp-pic-no-item">' . __('Item not found:', 'wppic-translate') . ' "' . $slug . '" ' . __('does not exist.', 'wppic-translate') . '</span>';
				$content .= '</div>';
				
			}
		}
		
	}

	if(!empty($_POST['wppic-list'])) {
		echo $content;
		die();	
	} else {
		return $content;
	}
			
}
add_action( 'wp_ajax_wppic_widget_render', 'wppic_widget_render' );