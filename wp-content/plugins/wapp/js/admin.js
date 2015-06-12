(function( $ ) {

	$(function() {
	
		$('.cmb2-id-wapp-type li').click(function(){
			 
			 if( $(this).children('input').attr('id') == 'wapp_type4' || $(this).children('input').attr('id') == 'wapp_type5' ){
			 
				 if( $('#wapp_slider_post_box').is(":hidden") ){
					 $('#wapp_slider_post_box').slideDown();
				 }
			 
			 }else {
				 console.log('no wapp_type4 or wapp_type5');

				 if( $('#wapp_slider_post_box').is(":visible") ){
					 $('#wapp_slider_post_box').slideUp();
				 }
				 
			 }
			 
		});
		
		if($('#wapp_type4').is(":checked") || $('#wapp_type5').is(":checked")){
			$('#wapp_slider_post_box').show();			
		}
		
		/*
			WAPP IMAGE RADIO
			Adds images to wapp layout radio options
		*/
		$('.cmb2-id-wapp-type ul.cmb2-radio-list li').each(function(){
			var li_id = $('input', this).attr('id');
			$(this).prepend('<div class="wapp_radio_image '+li_id+'"></div>');
		});
		
		$('label[for="wapp_wapp_config_display_intro"]').parent().append('<div class="wapp_radio_image admin_area wapp_wapp_config_display_intro"></div>');
		$('label[for="wapp_wapp_config_display_slider"]').parent().append('<div class="wapp_radio_image admin_area wapp_wapp_config_display_slider"></div>');
		$('label[for="wapp_wapp_config_display_columns"]').parent().append('<div class="wapp_radio_image admin_area wapp_wapp_config_display_columns"></div>');
		$('label[for="wapp_wapp_config_display_full"]').parent().append('<div class="wapp_radio_image admin_area wapp_wapp_config_display_full"></div>');
		$('label[for="wapp_wapp_config_display_list"]').parent().append('<div class="wapp_radio_image admin_area wapp_wapp_config_display_list"></div>');
	});
	
})(jQuery);