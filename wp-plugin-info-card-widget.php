<?php
/***************************************************************
 * Enqueue style on dashboard if widget is activated
 ***************************************************************/ 
function wppic_widget_enqueue($hook) {
    if ( 'index.php' != $hook ) {
        return;
    }
	//Enqueue sripts and style
	wppic_admin_scripts();
	wppic_admin_css();
}
//action is call during widget registration

/***************************************************************
 * Register Dashboard Widget
 ***************************************************************/ 
if (!function_exists('wppic_dashboard_widgets')) {
	function wppic_add_dashboard_widgets() {
		$wppicSettings = get_option('wppic_settings');
		if($wppicSettings['widget'] == true){
			wp_add_dashboard_widget('wppic-dashboard-widget','<img src="' . WPPIC_URL . 'img/wppic.svg" class="wppic-logo" alt="b*web"/>&nbsp;&nbsp;' . WPPIC_NAME . ' board', 'wppic_widgets');
			add_action( 'admin_enqueue_scripts', 'wppic_widget_enqueue' );
		}
	}
}
add_action('wp_dashboard_setup', 'wppic_add_dashboard_widgets');


/***************************************************************
 * Dashboard Widget function 
 ***************************************************************/  
if (!function_exists('wppic_widgets')) {
	function wppic_widgets() {
		$ajaxClass = '';
		
		$wppicSettings = get_option('wppic_settings');
		if($wppicSettings['ajax'] == true){
				$ajaxClass = 'ajax-call';
		}
		
		$content .= '<div class="wp-pic-list ' . $ajaxClass . '">';
		
		if(!empty($wppicSettings['list'])){
			
			if($wppicSettings['ajax'] == true){
				$content .= '<div class="wp-pic-loading" style="background-image: url(' . admin_url() . 'images/spinner-2x.gif);" data-list="' . htmlspecialchars(json_encode($wppicSettings['list']), ENT_QUOTES, 'UTF-8') . '"></div>';
			} else {
				 $content .= wppic_ajax_widget($wppicSettings['list']);
			}
			
		} else {
	
			$content .= '<div class="wp-pic-item ' . $slug . '" style="display:block;">';
				$content .= '<span class="wp-pic-no-plugin">' . __('No plugin found', 'wppic-translate') . '</span>';
			$content .= '</div>';
			
		}

		$content .= '</div>';
		
		echo $content;
			
	} //end of wppic_widgets
}


/***************************************************************
 * Widget Ajax callback 
 ***************************************************************/  
function wppic_ajax_widget($slugs=NULL){

	if(!empty($_POST['wppic-list'])){
		$slugs = array($_POST['wppic-list']);
	} 
	
	if(!empty($slugs)) {
		foreach($slugs as $slug){
			$wppic_plugin_data = wp_Plugin_API_Parser($slug);

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
