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
				wp_add_dashboard_widget('wppic-dashboard-widget','<img src="' . WPPIC_URL . 'img/wppic.svg" class="wppic-logo" alt="b*web"/>&nbsp;&nbsp;' . WPPIC_NAME . ' board', 'wppic_widgets');
				add_action( 'admin_enqueue_scripts', 'wppic_widget_enqueue' );
			}
	}
}
add_action('wp_dashboard_setup', 'wppic_add_dashboard_widgets');


/***************************************************************
 * Dashboard Widget function 
 ***************************************************************/  
if ( !function_exists( 'wppic_widgets' ) ) {
	function wppic_widgets() {
		$ajaxClass = '';
		
		$wppicSettings = get_option('wppic_settings');
		if( $wppicSettings['ajax'] == true ){
				$ajaxClass = 'ajax-call';
		}
		
		$content .= '<div class="wp-pic-list ' . $ajaxClass . '">';
		
		//Plugins list
		if(!empty($wppicSettings['list'])){
			
			if( !empty($wppicSettings['theme-list']) ){
				$content .= '<h4>' . __('Plugins', 'wppic-translate') . '</h4>';
			}
			
			if( $wppicSettings['ajax'] == true ){
				$content .= '<div class="wp-pic-loading" style="background-image: url(' . admin_url() . 'images/spinner-2x.gif);" data-type="plugin" data-list="' . htmlspecialchars(json_encode($wppicSettings['list']), ENT_QUOTES, 'UTF-8') . '"></div>';
			} else {
				 $content .= wppic_ajax_widget('plugin', $wppicSettings['list']);
			}
		}
			
		//Thmes list
		if( !empty($wppicSettings['theme-list']) ){
			
			if( !empty($wppicSettings['list']) ){
				$content .= '<h4>' . __('Themes', 'wppic-translate') . '</h4>';
			}
			
			if( $wppicSettings['ajax'] == true ){
				$content .= '<div class="wp-pic-loading" style="background-image: url(' . admin_url() . 'images/spinner-2x.gif);" data-type="theme" data-list="' . htmlspecialchars(json_encode($wppicSettings['theme-list']), ENT_QUOTES, 'UTF-8') . '"></div>';
			} else {
				 $content .= wppic_ajax_widget('theme', $wppicSettings['theme-list']);
			}
		}
			
		//Nothing found
		if( empty($wppicSettings['list']) && empty($wppicSettings['theme-list']) ) {
	
			$content .= '<div class="wp-pic-item ' . $slug . '" style="display:block;">';
				$content .= '<span class="wp-pic-no-plugin">' . __('Nothing found, please go to WP Plugin Info Card <a href="admin.php?page='.WPPIC_ID.'">' . __('Settings', 'wppic-translate') . '</a> and add some plugins and themes.', 'wppic-translate') . '</span>';
			$content .= '</div>';
			
		}

		$content .= '</div>';
		
		echo $content;
			
	} //end of wppic_widgets
}


/***************************************************************
 * Widget Ajax callback 
 ***************************************************************/  
function wppic_ajax_widget($type=NULL, $slugs=NULL){

	if(!empty($_POST['wppic-type'])){
		$type = $_POST['wppic-type'];
	} 
	
	if(!empty($_POST['wppic-list'])){
		$slugs = array($_POST['wppic-list']);
	} 

	if(!empty($slugs)) {
		foreach($slugs as $slug){
			$wppic_plugin_data = WP_Plugin_API_Parser($type, $slug, 5, true);

			if(!empty($wppic_plugin_data->name)){
				$content .= '<div class="wp-pic-item ' . $slug . '">';
					$content .= '<a class="wp-pic-widget-name" href="' . $wppic_plugin_data->url . '" target="_blank" title="' . __('WordPress.org Plugin Page', 'wppic-translate') . '">' . $wppic_plugin_data->name .'</a>';
					$content .= '<span class="wp-pic-widget-rating"><span>' . __('Ratings:', 'wppic-translate') . '</span> ' . $wppic_plugin_data->rating .'% (' . $wppic_plugin_data->num_ratings . ' votes)</span>';
					$content .= '<span class="wp-pic-widget-downloaded"><span>' . __('Downloads:', 'wppic-translate') . '</span> ' . number_format($wppic_plugin_data->downloaded, 0, ',', ',') .'</span>';
					$content .= '<p class="wp-pic-widget-updated"><span>' . __('Last Updated:', 'wppic-translate') . '</span> ' . date(get_option( 'date_format' ), strtotime($wppic_plugin_data->last_updated)) .' (v.' . $wppic_plugin_data->version .')</p>';
				$content .= '</div>';
			
			} else {
				
				$content .= '<div class="wp-pic-item ' . $slug . '">';
					$content .= '<span class="wp-pic-no-plugin">' . __('Plugin not found:', 'wppic-translate') . ' "' . $slug . '" ' . __('does not exist.', 'wppic-translate') . '</span>';
				$content .= '</div>';
				
			}
		}
		
	} else {

		$content .= '<div class="wp-pic-item ' . $slug . '">';
			$content .= '<span class="wp-pic-no-plugin">' . __('No plugin found', 'wppic-translate') . '</span>';
		$content .= '</div>';
		
	}
	
	echo $content;
	
	if(!empty($_POST['wppic-list']))
	die();
	
}
add_action( 'wp_ajax_wppic_ajax_widget', 'wppic_ajax_widget' );
