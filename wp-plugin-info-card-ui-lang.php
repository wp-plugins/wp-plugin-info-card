<?php
/***************************************************************
 * Translation for TinyMCE
 ***************************************************************/ 

if ( ! class_exists( '_WP_Editors' ) )
    require( ABSPATH . WPINC . '/class-wp-editor.php' );

function wppic_tinymce_translation() {
    $strings = array(
		'title'			=> __('Insert WP Plugin Info Card Shortcode', 'wppic-translate'),
		'slug' 			=> __('The Slug', 'wppic-translate'),
		'image' 		=> __('Custom logo URL', 'wppic-translate'),
		'logo' 			=> __('Specify logo format', 'wppic-translate'),
		'banner' 		=> __('Specify banner format', 'wppic-translate'),
		'align' 		=> __('Card\'s align', 'wppic-translate'),
		'containerid' 	=> __('Custom container ID', 'wppic-translate'),
		'margin' 		=> __('Custom container margin (15px 0)', 'wppic-translate'),
		'clear' 		=> __('Clear container float', 'wppic-translate'),
		'expiration' 	=> __('Cache duration in minutes (num. only)', 'wppic-translate'),
		'ajax' 			=> __('Load data async. with AJAX', 'wppic-translate'),
		'custom' 		=> __('Single value to output', 'wppic-translate'),
		'default' 		=> __('Do not specify', 'wppic-translate'),
		'yes' 			=> __('yes', 'wppic-translate'),
		'no' 			=> __('no', 'wppic-translate'),
		'no_logo' 		=> __('No logo', 'wppic-translate'),
		'no_banner'		=> __('No banner', 'wppic-translate'),
		'center'		=> __('center', 'wppic-translate'),
		'left'			=> __('left', 'wppic-translate'),
		'right'			=> __('right', 'wppic-translate'),
		'before'		=> __('before', 'wppic-translate'),
		'after'			=> __('after', 'wppic-translate')
    );
    $locale = _WP_Editors::$mce_locale;
    $translated = 'tinyMCE.addI18n("' . $locale . '.wppic_tinymce_plugin", ' . json_encode( $strings ) . ");\n";

     return $translated;
}

$strings = wppic_tinymce_translation();