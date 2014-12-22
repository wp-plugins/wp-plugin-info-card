<?php
/**
 * Plugin Name: WP Plugin Info Card by b*web
 * Plugin URI: http://b-website.com/wp-plugin-info-card-for-wordpress
 * Description: WP Plugin Info Card displays plugins & themes identity cards in a beautiful box with a smooth rotation effect using WordPress.org Plugin API & WordPress.org Theme API. Dashboard widget included.
 * Author: Brice CAPOBIANCO
 * Author URI: http://b-website.com/
 * Version: 2.2
 * Domain Path: /langs
 * Text Domain: wppic-translate
 */


/***************************************************************
 * SECURITY : Exit if accessed directly
***************************************************************/
if ( !function_exists( 'add_action' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit();
}

if ( !defined( 'ABSPATH' ) ) {
	exit;
}


/***************************************************************
 * Define constants
 ***************************************************************/
if ( !defined('WPPIC_PATH' ) ) {
	define( 'WPPIC_PATH', plugin_dir_path( __FILE__ ) ); 
}
if ( !defined('WPPIC_URL' ) ) {
	define( 'WPPIC_URL', plugin_dir_url( __FILE__ ) ); 
}
if ( !defined('WPPIC_BASE' ) ) {
	define( 'WPPIC_BASE', plugin_basename( __FILE__ ) ); 
}
if ( !defined('WPPIC_NAME' ) ) {
	define( 'WPPIC_NAME', 'WP Plugin Info Card' ); 
}
if ( !defined('WPPIC_NAME_FULL' ) ) {
	define( 'WPPIC_NAME_FULL', 'WP Plugin Info Card by b*web' ); 
}
if ( !defined('WPPIC_ID' ) ) {
	define( 'WPPIC_ID', 'wp-plugin-info-card' ); 
}


/***************************************************************
 * Load plugin files
 ***************************************************************/
$wppicFiles = array( 'api','shortcode','admin','widget','ui', 'add-plugin', 'add-theme' );
foreach( $wppicFiles as $wppicFile ){
	require_once( WPPIC_PATH . 'wp-plugin-info-card-' . $wppicFile . '.php' );
}


/***************************************************************
 * Load plugin textdomain
 ***************************************************************/
function wppic_load_textdomain() {
	$path = dirname( plugin_basename( __FILE__ ) ) . '/langs/';
	$loaded = load_plugin_textdomain( 'wppic-translate', false, $path );
}
add_action( 'init', 'wppic_load_textdomain' );


/***************************************************************
 * Add settings link on plugin list page
 ***************************************************************/
function wppic_settings_link( $links ) { 
  $links[] = '<a href="' . admin_url( 'options-general.php?page=' . WPPIC_ID ) . '" title="'. __( 'WP Plugin Info Card Settings', 'wppic-translate' ) .'">' . __( 'Settings', 'wppic-translate' ) . '</a>'; 
  return $links; 
}
add_filter( 'plugin_action_links_' . WPPIC_BASE, 'wppic_settings_link' );


/***************************************************************
 * Add custom meta link on plugin list page
 ***************************************************************/
function wppic_meta_links( $links, $file ) {
	if ( strpos( $file, 'wp-plugin-info-card.php' ) !== false ) {
		$links[0] = '<a href="http://b-website.com/" target="_blank"><img src="' . WPPIC_URL . 'img/icon-bweb.svg" style="margin-bottom: -4px; width: 18px;" alt="b*web"/></a>&nbsp;&nbsp;'. $links[0];
		$links[] = '<a href="http://b-website.com/wp-plugin-info-card-for-wordpress" target="_blank" title="'. __( 'Documentation and examples', 'wppic-translate' ) .'"><strong style="color:#db3939">'. __( 'Documentation and examples', 'wppic-translate' ) .'</strong></a>';
		$links[] = '<a href="http://b-website.com/category/plugins" target="_blank" title="'. __( 'More plugins by b*web', 'wppic-translate' ) .'">'. __( 'More plugins by b*web', 'wppic-translate' ) .'</a>';
		$links[] = '<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=7Z6YVM63739Y8" target="_blank" title="'. __( 'Donate', 'wppic-translate' ) .'"><strong>'. __( 'Donate', 'wppic-translate' ) .'</strong></a>';
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'wppic_meta_links', 10, 2 );


/***************************************************************
 * Admin Panel Favico
 ***************************************************************/
function wppic_add_favicon() {
	$screen = get_current_screen();
	if ( $screen->id != 'toplevel_page_' . WPPIC_ID )
		return;
	
	$favicon_url = WPPIC_URL . 'img/wppic.svg';
	echo '<link rel="shortcut icon" href="' . $favicon_url . '" />';
}
add_action( 'admin_head', 'wppic_add_favicon' );


/***************************************************************
 * Purge all plugin transients function
 ***************************************************************/
function wppic_delete_transients(){
	global $wpdb;
	$array = array();
	$wppic_transients = $wpdb->get_results(
		"SELECT option_name AS name,
		option_value AS value FROM $wpdb->options 
		WHERE option_name LIKE '_transient_wppic_%'"
	);
	foreach($wppic_transients as $singleTransient){
		delete_transient( str_replace( "_transient_", "", $singleTransient->name ) );
	}
}


/***************************************************************
 * Cron to purge all plugin transients every weeks
 ***************************************************************/
function wppic_add_weekly( $schedules ) {
	$schedules[ 'wppic-weekly' ] = array(
		'interval' => 604800,
		'display' => __( 'Once Weekly' )
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'wppic_add_weekly' ); 

function wppic_cron_activation() {
	wp_schedule_event( current_time( 'timestamp' ), 'wppic-weekly', 'wppic_daily_cron' );
}
add_action( 'wppic_daily_cron', 'wppic_delete_transients' );


/***************************************************************
 * Remove Plugin settings from DB on uninstallation (= plugin deletion) 
 ***************************************************************/
function wppic_uninstall() {
	// Remove option from DB
	delete_option( 'wppic_settings' );
	//deactivate cron
	wp_clear_scheduled_hook( 'wppic_daily_cron' );
	//Purge transients
	wppic_delete_transients();
}

//Hooks for install
if (function_exists( 'register_uninstall_hook' ) ) {
	register_activation_hook( __FILE__, 'wppic_cron_activation' );
	register_uninstall_hook( __FILE__, 'wppic_cron_deactivation' );
	register_uninstall_hook( __FILE__, 'wppic_uninstall' );
}