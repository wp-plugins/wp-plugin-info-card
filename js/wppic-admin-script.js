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
		console.log($(this));
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
	if ($('#wppic-dashboard-widget').length > 0){
		var data = {
			'action': 'wppic_ajax_widget',
			'wppic-list': $('.wp-pic-list .wp-pic-loading').data('list')
		};
		$.post(ajaxurl, data, function(response) {
			$('.wp-pic-list').html(response);
		});
	}
				
});