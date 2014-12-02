<?php
/***************************************************************
 * Back-End Scripts & Styles enqueueing
 ***************************************************************/
function wppic_admin_scripts() {
	wp_enqueue_script( 'wppic-admin-js', WPPIC_URL . 'js/wppic-admin-script.js', array( 'jquery' ),  NULL);
	wp_enqueue_script( 'wppic-js', WPPIC_URL . 'js/wppic-script.min.js', array( 'jquery' ),  NULL);
	wp_enqueue_script( 'jquery-ui-sortable', WPPIC_URL . '/wp-includes/js/jquery/ui/jquery.ui.sortable.min.js', array( 'jquery' ),  NULL);
}
function wppic_admin_css() {
	wp_enqueue_style( 'dashicons' );
	wp_enqueue_style( 'wppic-admin-css', WPPIC_URL . 'css/wppic-admin-style.css', array(), NULL, NULL);
}


/***************************************************************
 * Create admin page menu
 ***************************************************************/
function wppic_create_menu() {
	$admin_page = add_menu_page(WPPIC_NAME_FULL, WPPIC_NAME, 'manage_options', WPPIC_ID, 'wppic_settings_page',WPPIC_URL . 'img/icon-wppic.png');
	
	//Enqueue sripts and style
	add_action( 'admin_print_scripts-' . $admin_page, 'wppic_admin_scripts' );
	add_action( 'admin_print_styles-' . $admin_page, 'wppic_admin_css' );
	
}
add_action('admin_menu', 'wppic_create_menu');


/***************************************************************
 * Register plugin settings 
 ***************************************************************/ 
function wppic_register_settings() {
	register_setting( 
		'wppic_settings', 
		'wppic_settings',
		'wppic_validate'
	);
	add_settings_section(
		'wppic_options',
		'', 
		'',
		WPPIC_ID . 'options'
	);
	add_settings_field(
		'wppic-list-widget',
		__('Color scheme', 'wppic-translate'), 
		'wppic_color_scheme',
		WPPIC_ID . 'options',
		'wppic_options'
	);

	add_settings_section(
		'wppic_list',
		'', 
		'',
		WPPIC_ID . 'widget'
	);
	add_settings_field(
		'wppic-list-widget',
		__('Enable dashboard widget', 'wppic-translate'), 
		'wppic_list_widget',
		WPPIC_ID . 'widget',
		'wppic_list'
	);
	add_settings_field(
		'wppic-list-ajax',
		__('Ajaxify dashboard widget', 'wppic-translate'), 
		'wppic_list_ajax',
		WPPIC_ID . 'widget',
		'wppic_list'
	);
	add_settings_field(
		'wppic-plugin-list-form',
		__('List of plugins to display', 'wppic-translate'), 
		'wppic_plugin_list_form',
		WPPIC_ID . 'widget',
		'wppic_list'
	);
	add_settings_field(
		'wppic-theme-list-form',
		__('List of themes to display', 'wppic-translate'), 
		'wppic_theme_list_form',
		WPPIC_ID . 'widget',
		'wppic_list'
	);
}
add_action( 'admin_init', 'wppic_register_settings' );


/***************************************************************
 * Admin Notice
 ***************************************************************/
function wppic_notices_action() {
    settings_errors( 'wppic-admin-notice' );
}
add_action( 'admin_notices', 'wppic_notices_action' );


/***************************************************************
 * Admin page structure	
 ***************************************************************/
function wppic_settings_page() {
	
	//Get default card color shceme
	$wppicSettings = get_option('wppic_settings');
	$scheme = $wppicSettings['colorscheme'];
	if(	$scheme == 'default'){
		$scheme = '';
	}
					
	echo '
	<div class="wrap">
		<h2>' . WPPIC_NAME_FULL . '</h2>
		<div id="post-body-content">
			' . wppic_plugins_about() . '
			<div id="wppic-admin-page" class="meta-box-sortabless">
				<div id="wppic-shortcode" class="postbox">
					<h3 class="hndle"><span>' . __('How to use WP Plugin Info Card shortcodes?', 'wppic-translate') . '</span></h3>
					<div class="inside">
						' . wppic_shortcode_function( array ( 'type'=>'plugin', 'slug'=>'wp-plugin-info-card', 'image'=>'', 'align'=>'right', 'margin'=>'0 0 0 20px', 'scheme'=>$scheme  ) ) . '
						<h3 class="wp-pic-title">' . __('Shortcode parameters', 'wppic-translate') . '</h3>
						<ul>
							<li><strong>type:</strong> plugin, theme - ' . __('(default: plugin)', 'wppic-translate') . '</li>
							<li><strong>slug:</strong> ' . __('plugin slug name - Please refer to the plugin URL on wordpress.org to determine its slug: https://wordpress.org/plugins/THE-SLUG/', 'wppic-translate') . '</li>
							<li><strong>image:</strong> ' . __('image url to replace WP logo (default: empty)', 'wppic-translate') . '</li>
							<li><strong>align:</strong> center, left, right ' . __('(default: empty)', 'wppic-translate') . '</li>
							<li><strong>containerid:</strong> ' . __('custom div id, may be used for anchor (default: wp-pic-PLUGIN-NAME)', 'wppic-translate') . '</li>
							<li><strong>margin:</strong> ' . __('custom container margin - eg: "15px 0" (default: empty)', 'wppic-translate') . '</li>
							<li><strong>clear:</strong> ' . __('clear float before or after the card: before, after (default: empty', 'wppic-translate') . '</li>
							<li><strong>expiration:</strong> ' . __('cache duration in minutes - numeric format only (default: 720)', 'wppic-translate') . '</li>
							<li><strong>ajax: (BETA)</strong> ' . __('load the plugin data asynchronously with AJAX: yes, no (default: no)', 'wppic-translate') . '</li>
							<li><strong>custom:</strong> ' . __('value to print: (default: empty)', 'wppic-translate') . ' 
								<ul>
									<li>&nbsp;&nbsp;&nbsp;&nbsp;- ' . __('For plugins:', 'wppic-translate') . ' <i>url, name, icons, banners, version, author, requires, rating, num_ratings, downloaded, last_updated, download_link</i></li>
									<li>&nbsp;&nbsp;&nbsp;&nbsp;- ' . __('For themes:', 'wppic-translate') . ' <i>url, name, version, author, screenshot_url, rating, num_ratings, downloaded, last_updated, homepage, download_link</i></li>
								</ul>
							</li>
						</ul>
						<p>&nbsp;</p>
						<p>
							<pre> [wp-pic slug="adblock-notify-by-bweb" align="right" margin="0 0 0 20px" containerid="download-sexion" ajax="yes"] </pre>
						</p>
						<p class="documentation"><a href="http://b-website.com/wp-plugin-info-card-for-wordpress" target="_blank" title="'. __( 'Documentation and examples', 'wppic-translate' ) .'">'. __( 'Documentation and examples', 'wppic-translate' ) .' <span class="dashicons dashicons-external"></span></a></p>
					 </div>
				</div>
			</div>';
		?>
			<form method="post" id="wppic_settings" action="options.php">
				<?php settings_fields('wppic_settings') ?>
				<div class="meta-box-sortabless">
					<div id="wppic-form" class="postbox">
						<h3 class="hndle"><span><?php  _e('Shortcode options', 'wppic-translate') ?></span></h3>
						<div class="inside">
                            <table class="form-table">
                                <tr valign="top">
                                    <?php do_settings_sections(WPPIC_ID . 'options') ?>
                                </tr>
                            </table>
                            <?php submit_button() ?>
						</div>
					</div>
				</div>
				<div class="meta-box-sortabless">
					<div id="wppic-form" class="postbox">
						<h3 class="hndle"><span><?php  _e('Dashboard Widget Settings', 'wppic-translate') ?></span></h3>
						<div class="inside">
                            <table class="form-table">
                                <tr valign="top">
                                    <?php do_settings_sections(WPPIC_ID . 'widget') ?>
                                </tr>
                            </table>
                            <?php submit_button() ?>
						</div>
					</div>
				</div>
			</form> 
		</div>
    </div>
<?php
}


/***************************************************************
 * Dashboard widget activation
 ***************************************************************/
function wppic_color_scheme() {
	$wppicSettings = get_option('wppic_settings');
	$scheme = $wppicSettings['colorscheme'];
	
	$content = '<td>';
		$content .= '<select id="wppic-color-scheme" name="wppic_settings[colorscheme]">';
		$content .= '<option value="default"  '. selected( $scheme, 'default', FALSE ) . ' >Default</option>';
		$content .= '<option value="scheme1"  '. selected( $scheme, 'scheme1', FALSE ) . ' >Color scheme 1</option>';
		$content .= '<option value="scheme2"  '. selected( $scheme, 'scheme2', FALSE ) . ' >Color scheme 2</option>';
		$content .= '<option value="scheme3"  '. selected( $scheme, 'scheme3', FALSE ) . ' >Color scheme 3</option>';
		$content .= '<option value="scheme4"  '. selected( $scheme, 'scheme4', FALSE ) . ' >Color scheme 4</option>';
		$content .= '<option value="scheme5"  '. selected( $scheme, 'scheme5', FALSE ) . ' >Color scheme 5</option>';
		$content .= '<option value="scheme6"  '. selected( $scheme, 'scheme6', FALSE ) . ' >Color scheme 6</option>';
		$content .= '<option value="scheme7"  '. selected( $scheme, 'scheme7', FALSE ) . ' >Color scheme 7</option>';
		$content .= '<option value="scheme8"  '. selected( $scheme, 'scheme8', FALSE ) . ' >Color scheme 8</option>';
		$content .= '<option value="scheme9"  '. selected( $scheme, 'scheme9', FALSE ) . ' >Color scheme 9</option>';
		$content .= '<option value="scheme10" '. selected( $scheme, 'scheme10', FALSE ) . '>Color scheme 10</option>';
		$content .= '</select>';
		$content .= '<label for="wppic-color-scheme">' . __('Default color scheme for your cards.', 'wppic-translate') . '</label>';
	$content .= '</td>';
	echo $content;
}


/***************************************************************
 * Dashboard widget activation
 ***************************************************************/
function wppic_list_widget() {
	$wppicSettings = get_option('wppic_settings');
	$content = '<td>';
		$content .= '<input type="checkbox" id="wppic-widget" name="wppic_settings[widget]"  value="1" ';
		if( isset($wppicSettings['widget']) ) {
			$content .= checked( 1, $wppicSettings['widget'], false );
		}
		$content .= '/>';
		$content .= '<label for="wppic-widget">' . __('Help: Don\'t forget to open the dashboard option panel (top right) to insert it on your dashboard.', 'wppic-translate') . '</label>';
	$content .= '</td>';
	echo $content;
}


/***************************************************************
 * Dashboard widget Ajaxify
 ***************************************************************/
function wppic_list_ajax() {
	$wppicSettings = get_option('wppic_settings');
	$content = '<td>';
		$content .= '<input type="checkbox" id="wppic-ajax" name="wppic_settings[ajax]"  value="1" ';
		if( isset($wppicSettings['ajax']) ) {
			$content .= checked( 1, $wppicSettings['ajax'], false );
		}
		$content .= '/>';
		$content .= '<label for="wppic-ajax">' . __('Will load the data asynchronously with AJAX.', 'wppic-translate') . '</label>';
	$content .= '</td>';
	echo $content;
}


/***************************************************************
 * Dashoboard widget plugin list
 ***************************************************************/
function wppic_plugin_list_form() {
	$wppicSettings = get_option('wppic_settings');
        $content = '<td>';
            $content .= '<button class="wppic-add-fields">' . __('Add a plugin', 'wppic-translate') . '</button><input type="text" name="wppic-add" class="wppic-add"  value="">';
            $content .= '<ul id="wppic-plugin-list" class="wppic-list plugins">';
					if(!empty($wppicSettings['list'])){
						foreach($wppicSettings['list'] as $item){
							$content .= '<li class="wppic-dd"><input type="text" name="wppic_settings[list][]"  value="' . $item . '"><span class="wppic-remove-field" title="remove"></span></li>';
						}
					}
            $content .= '</ul>';
            $content .= '<p>' . __('Please refer to the plugin URL on wordpress.org to determine its slug', 'wppic-translate') . ': <i> https://wordpress.org/plugins/THE-SLUG/</i><p>';               
        $content .= '</td>';
	echo $content;
}

function wppic_theme_list_form() {
	$wppicSettings = get_option('wppic_settings');
        $content = '<td>';
            $content .= '<button class="wppic-add-fields">' . __('Add a theme', 'wppic-translate') . '</button><input type="text" name="wppic-add" class="wppic-add"  value="">';
            $content .= '<ul id="wppic-theme-list" class="wppic-list themes">';
					if(!empty($wppicSettings['theme-list'])){
						foreach($wppicSettings['theme-list'] as $item){
							$content .= '<li class="wppic-dd"><input type="text" name="wppic_settings[theme-list][]"  value="' . $item . '"><span class="wppic-remove-field" title="remove"></span></li>';
						}
					}
            $content .= '</ul>';
            $content .= '<p>' . __('Please refer to the theme URL on wordpress.org to determine its slug', 'wppic-translate') . ': <i> https://wordpress.org/themes/THE-SLUG/</i><p>';               
        $content .= '</td>';
	echo $content;
}


/***************************************************************
 * Form validator
 ***************************************************************/
function wppic_validate($input) {
	if(!empty($input['list'])){
		
		$list = array(
			array('list', __('is not a valid plugin name format. This key has been deleted.', 'wppic-translate')), 
			array('theme-list', __('is not a valid theme name format. This key has been deleted.', 'wppic-translate')), 
		);
		
		foreach($list as $element){
			//remove duplicate 
			$input[$element[0]] = array_unique($input[$element[0]]);
	
			foreach($input[$element[0]] as $key=>$item){
				if(!preg_match('/^[a-z][-a-z0-9]*$/', $item)) {
					if(!empty ($item)){
						add_settings_error(
							'wppic-admin-notice',
							'',
							'<i>"' . $item . '"</i> ' . $element[1],
							'error'
						);
					}
					unset($input[$element[0]][$key]);
				}
			}
		}
	}
	add_settings_error(
		'wppic-admin-notice',
		'',
		__('Options saved', 'wppic-translate'),
		'updated'
	);
	return $input;
}


/***************************************************************
 * About section
 ***************************************************************/
function wppic_plugins_about() {
	$content ='
    <div id="wppic-about-list">
        <a class="wppic-button wppic-pluginHome" href="http://b-website.com/wp-plugin-info-card-for-wordpress" target="_blank">' . __('Plugin home page', 'wppic-translate') . '</a>
        <a class="wppic-button wppic-pluginOther" href="http://b-website.com/category/plugins" target="_blank">' . __('My other plugins', 'wppic-translate') . '</a>
        <a class="wppic-button wppic-pluginPage" href="https://wordpress.org/plugins/wp-plugin-info-card/" target="_blank">WordPress.org</a>
        <a class="wppic-button wppic-pluginSupport" href="https://wordpress.org/support/plugin/wp-plugin-info-card" target="_blank">' . __('Support', 'wppic-translate') . '</a>
        <a class="wppic-button wppic-pluginRate" href="https://wordpress.org/support/view/plugin-reviews/wp-plugin-info-card#postform" target="_blank">' . __('Give me five!', 'wppic-translate') . '</a>
        <a class="wppic-button wppic-pluginContact" href="http://b-website.com/contact" target="_blank">' . __('Any suggestions?', 'wppic-translate') . '</a>
    </div>
    
	<div id="wppic-donate">
        ' . __('Did you like it? If so, please consider making a donation.', 'wppic-translate') . '
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="SVR5SERD3468E">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donate_SM.gif" border="0" name="submit" alt="' . __('PayPal - The safer, easier way to pay online!', 'wppic-translate') . '">
		<img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1">
		</form>
	</div>
	';
	return $content;
}