/**
 * Plugin Name: WP Plugin Info Card by b*web
 * Author: Brice CAPOBIANCO - b*web
 */
(function() {
	tinymce.PluginManager.add('wppic_mce_button', function( editor, url ) {
		editor.addButton( 'wppic_mce_button', {
			icon: 'wppic-icon',
			onclick: function() {
				editor.windowManager.open( {
					title: 'Insert WP Plugin Info Card Shortcode',
					body: [
						{
							type: 'textbox',
							name: 'slug',
							label: 'The Slug',
							value: ''
						},
						{
							type: 'textbox',
							name: 'image',
							label: 'Custom logo URL',
							value: ''
						},
						{
							type: 'listbox',
							name: 'logo',
							label: 'Specify logo format',
							'values': [
								{text: 'Do not specify', value: ''},
								{text: 'No logo', value: 'no'},
								{text: 'svg', value: 'svg'},
								{text: '128×128.jpg', value: '128×128.jpg'},
								{text: '256×256.jpg', value: '256×256.jpg'},
								{text: '128×128.png', value: '128×128.png'},
								{text: '256×256.png', value: '256×256.png'}
							]
						},
						{
							type: 'listbox',
							name: 'banner',
							label: 'Specify banner format',
							'values': [
								{text: 'Do not specify', value: ''},
								{text: 'No banner', value: 'no'},
								{text: 'jpg', value: 'jpg'},
								{text: 'png', value: 'png'}
							]
						},
						{
							type: 'listbox',
							name: 'align',
							label: 'Card\'s align',
							'values': [
								{text: 'Do not specify', value: ''},
								{text: 'center', value: 'center'},
								{text: 'left', value: 'left'},
								{text: 'right', value: 'right'}
							]
						},
						{
							type: 'textbox',
							name: 'containerid',
							label: 'Custom container ID',
							value: ''
						},
						{
							type: 'textbox',
							name: 'margin',
							label: 'Custom container margin (15px 0)',
							value: ''
						},
						{
							type: 'listbox',
							name: 'clear',
							label: 'Clear container float',
							'values': [
								{text: 'Do not specify', value: ''},
								{text: 'before', value: 'before'},
								{text: 'after', value: 'after'}
							]
						},
						{
							type: 'textbox',
							name: 'expiration',
							label: 'Cache duration in minutes (num. only)',
							value: ''
						},
						{
							type: 'listbox',
							name: 'ajax',
							label: 'Load data async. with AJAX',
							'values': [
								{text: 'Do not specify', value: ''},
								{text: 'no', value: 'no'},
								{text: 'yes', value: 'yes'}
							]
						},
						{
							type: 'textbox',
							name: 'custom',
							label: 'Single value to output',
							value: ''
						}
					],
					onsubmit: function( e ) {
						if(e.data.slug != ''){
							e.data.slug = 'slug="' + e.data.slug + '" ';
						} else {
							e.data.slug = 'slug="wp-plugin-info-card" ';
						}
						if(e.data.image != ''){
							e.data.image = 'image="' + e.data.image + '" ';
						}
						if(e.data.logo != ''){
							e.data.logo = 'logo="' + e.data.logo + '" ';
						}
						if(e.data.banner != ''){
							e.data.banner = 'banner="' + e.data.banner + '" ';
						}
						if(e.data.align != ''){
							e.data.align = 'align="' + e.data.align + '" ';
						}
						if(e.data.containerid != ''){
							e.data.containerid = 'containerid="' + e.data.containerid + '" ';
						}
						if(e.data.margin != ''){
							e.data.margin = 'margin="' + e.data.margin + '" ';
						}
						if(e.data.clear != ''){
							e.data.clear = 'clear="' + e.data.clear + '" ';
						}
						if(e.data.expiration != ''){
							e.data.expiration = 'expiration="' + e.data.expiration + '" ';
						}
						if(e.data.ajax != ''){
							e.data.ajax = 'ajax="' + e.data.ajax + '" ';
						}
						if(e.data.custom != ''){
							e.data.custom = 'custom="' + e.data.custom + '" ';
						}
						editor.insertContent( 
							'[wp-pic '
								+ e.data.slug
								+ e.data.image
								+ e.data.logo
								+ e.data.banner
								+ e.data.align
								+ e.data.containerid
								+ e.data.margin
								+ e.data.clear
								+ e.data.expiration
								+ e.data.ajax
								+ e.data.custom
							+ ']'
						);
					}
				});
			}
		});
	});
})();