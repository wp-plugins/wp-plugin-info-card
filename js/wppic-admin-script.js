/**
 * Plugin Name: WP Plugin Info Card by b*web
 * Plugin URI: http://b-website.com/
 * Author: Brice CAPOBIANCO - b*web
 */	
jQuery(document).ready(function($) {

	//Color scheme preview
	$('select#wppic-color-scheme').on('change', function() {
		$('.wp-pic').attr('class', 'wp-pic ' + $(this).val() );
	});
  
	//Creat fields on the fly and reorder via drag & drop
	var wrapper         = '.wppic-list';	 		//Fields wrapper
	var add_button      = $(".wppic-add-fields"); 	//Add button ID
	var add_input     	= '.wppic-add'; 			//Fields list
	var field_remove    = 'wppic-remove-field';		//Remove item


	add_button.click(function(e){ //add button
		e.preventDefault();
		currentWrapper = $(this).parents('td').find(wrapper);
		currentInput = $(this).parents('td').find(add_input);

		if(currentWrapper.hasClass('themes')){
			list = 'theme-list';
		} else {
			list = 'list';
		}

		$(this).parents('td').find(wrapper).append('<li class="wppic-dd ui-state-default" draggable="true"><input type="text" name="wppic_settings[' + list + '][]" value="' + currentInput.val() + '" /><span class="' + field_remove + '" title="remove"></span></li>'); //add input box
		currentInput.val('').focus();
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
	$(wrapper).liveDraggable()
	
	
	//Widget ajax load
	if ($('#wppic-dashboard-widget').length > 0 && $('#wppic-dashboard-widget .wp-pic-loading').length > 0 && $('#wppic-dashboard-widget .wp-pic-widget-empty').length == 0){

		$('.wp-pic-list .wp-pic-loading').each(function(){

			var elem = $(this);
			var dataType = elem.data('type');
			var dataList = elem.data('list');
			var listLength = $.map(dataList, function(n, i) { return i; }).length;
			var count = 1;
			
			//prepare containers and keep order list
			$.each(dataList, function( index, value ){
				$('<span class="wp-pic-prepare" id="' + value + '" data-index="' + index + '" style="display:none"></span>').insertBefore(elem);
			});
	
			//ajax request and callback
			$.each(dataList, function( index, value ){
				var data = {
					'action': 'wppic_ajax_widget',
					'async': false,
					'wppic-type': dataType,
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
						elem.parents('.wp-pic-list').find('wp-pic-prepare').remove();
						elem.remove();
						$('.wp-pic-list .wp-pic-item').fadeIn(500);
					}
					count++
					
				});
			});
		
		});
		
	}
				
});