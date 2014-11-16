/**
 * Plugin Name: WP Plugin Info Card by b*web
 * Author: Brice CAPOBIANCO - b*web
 */
jQuery(document).ready(function($) {

	//Creat fields on the fly and reorder via drag & drop
	var wrapper         = $("#wppic-liste"); 		//Fields wrapper
	var add_button      = $(".wppic-add-fields"); 	//Add button ID
	var add_input     	= $(".wppic-add"); 			//Fields list
	var fields     		= $(".wppic-dd"); 			//Fields list
	var field_remove    = 'wppic-remove-field';		//Remove item


	add_button.click(function(e){ //add button
		e.preventDefault();
		wrapper.append('<li class="wppic-dd ui-state-default" draggable="true"><input type="text" name="wppic_settings[list][]" value="' + add_input.val() + '" /><span class="' + field_remove + '" title="remove"></span></li>'); //add input box
		add_input.val('').focus();
	});
	
	
	$('.' + field_remove).live("click", function(){ //remove field
		$(this).closest('li').remove(); 
	})
		

	$.fn.liveDraggable = function (opts) {
        this.live("mouseover", function() {
            if (!$(this).data("init")) {
                $(this).data("init", true).sortable(opts);
            }
        });
        return $();
    };
	wrapper.liveDraggable()
	
	//Widget ajax load
	if ($('#wppic-dashboard-widget').length > 0 && $('#wppic-dashboard-widget .wp-pic-loading').length > 0 && $('#wppic-dashboard-widget .wp-pic-widget-empty').length == 0){
		var pluginList = $('.wp-pic-list .wp-pic-loading').data('list');
		var listLength = $.map(pluginList, function(n, i) { return i; }).length;
		var count = 1;
		
		//prepare containers and keep order list
		$.each(pluginList, function( index, value ){
			$('<span class="wp-pic-prepare" id="' + value + '" data-index="' + index + '" style="display:none"></span>').insertBefore($('.wp-pic-list .wp-pic-loading'));
		});

		//ajax request and callback
		$.each(pluginList, function( index, value ){
			var data = {
				'action': 'wppic_ajax_widget',
				'async': false,
				'wppic-list': value
			};
			$.post(ajaxurl, data, function(response) {

				$(response).insertBefore($('.wp-pic-list  #' + value + '.wp-pic-prepare'));				
				$('.wp-pic-list .wp-pic-item.' + value).attr('data-index', index);

				//keep order during display
				for ( var i = 0; i < listLength; i++ ) {
					var $current = $('.wp-pic-list .wp-pic-item[data-index="' + i + '"]');
					if ($current.length > 0) {
						$current.fadeIn(500);
					} else {
						break;
					}
				}
				
				if(count == listLength){
					$('.wp-pic-list .wp-pic-loading, .wp-pic-list .wp-pic-prepare').remove();
					$('.wp-pic-list .wp-pic-item').fadeIn(500);
				}
				count++
				
			});
		});
		
	}
				
});