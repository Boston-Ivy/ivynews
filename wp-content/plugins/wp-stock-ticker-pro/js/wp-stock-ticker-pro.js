
jQuery(function($) {
	function ticker_stock_show( ul_container_id, speed_field_id, show_chart_field_id, chart_type_id, chart_position_id, chart_inteface_id, chart_css ){
		if( $('#' +ul_container_id).length < 1 ){
			return;
		}
		var speed_val = $("#" + speed_field_id).val();
		if(speed_val == 0){
			$("#" + ul_container_id).simplyScroll( {speed:0, auto:false} );
		}else{
			speed_val_int = parseInt(speed_val) * 24;
			$("#" + ul_container_id).simplyScroll( {frameRate:speed_val_int} );
		}
		
		var show_chart = $("#" + show_chart_field_id).val();
		if ( show_chart != 'on' ){
			return;
		}
		var chart_type = $("#" + chart_type_id).val();
		var chart_position = $("#" + chart_position_id).val();
		var chart_interface = $("#" + chart_inteface_id).val();
		
		$('ul#' + ul_container_id + ' li').each(function(e){
													 
			var li_tag_id = $(this).attr('id');
			var stock_symbol = $("#s_ticker_"+li_tag_id+"_symbol_id").val();
			
			$(this).click(function(){
				var offset = $(this).offset();
				var left = offset.left;
				var top = offset.top;
				var iWidth = $(this).width();
				
				if(stock_symbol == 'NONE'){
					return;
				}
				
				scrollTop = $(window).scrollTop();
				scrollLeft = $(window).scrollLeft();
				if (chart_position == 'below'){
					top = top - scrollTop + 30;
				}else{
					top = top - scrollTop - 205 - 30;
				}
				left = left - scrollLeft;
				
				$('.' + chart_css).css('position', 'fixed');
				$('.' + chart_css).css('left',left + 'px');
				$('.' + chart_css).css('top',top + 'px');
				$('.' + chart_css).css('display','block');
				if (chart_interface == 'ichart'){
					//alert('http://ichart.yahoo.com/z?s='+stock_symbol+'&q='+chart_type+'&z=s" />');
					$('.' + chart_css).html('<img src="http://ichart.yahoo.com/z?s='+stock_symbol+'&q='+chart_type+'&z=s" />');
				}else{
					//alert('http://chart.finance.yahoo.com/t?s='+stock_symbol+'&q='+chart_type+'&width=345'+'&height=195'+'" />');
					$('.' + chart_css).html('<img src="http://chart.finance.yahoo.com/t?s='+stock_symbol+'&q='+chart_type+'&width=345'+'&height=195'+'" />');
				}
			});
			
			$(this).mouseleave(function(){
				$('.' + chart_css).css('display','none');
			});
		});
	}
	
	function stocks_ticker_by_widget(){
		if ($('.stocksTickerWidget').length > 0){
			var speed_val = $(".stocksTickerWidgetSpeed").val();
			if(speed_val == 0){
				$(".stocksTickerWidget").simplyScroll( {speed:0, auto:false} );
			}else{
				speed_val_int = parseInt(speed_val) * 24;
				$(".stocksTickerWidget").simplyScroll( {frameRate:speed_val_int} );
			}
		}
	}
	ticker_stock_show('stocks-by-shortcode', 's_ticker_SHORTCODE_speed_id', 's_ticker_SHORTCODE_showchart_id', 's_ticker_SHORTCODE_chart_type_id', 's_ticker_SHORTCODE_chart_position_id', 's_ticker_SHORTCODE_chart_interface_id', 'stocks-chart');
	stocks_ticker_by_widget();
	ticker_stock_show('stocks-by-interface', 's_ticker_INTERFACE_speed_id', 's_ticker_INTERFACE_showchart_id', 's_ticker_SHORTCODE_chart_type_id', 's_ticker_INTERFACE_chart_position_id', 's_ticker_INTERFACE_chart_interface_id', 'stocks-intf-chart');
	ticker_stock_show('stocks-by-advanced', 's_ticker_ADVANCED_speed_id', 's_ticker_ADVANCED_showchart_id', 's_ticker_ADVANCED_chart_type_id', 's_ticker_ADVANCED_chart_position_id', 's_ticker_ADVANCED_chart_interface_id', 'stocks-adv-chart');	
	
	//////////////////////////////
	//for static
	/////////////////////////////
	function static_stock_show( ul_container_id, show_chart_field_id, chart_type_id, chart_inteface_id, way ){
		if( $('#' + ul_container_id).length < 1 ){
			return;
		}
		var show_chart = $("#" + show_chart_field_id).val();
		if ( show_chart != 'on' ){
			return;
		}
		var chart_type = $("#" + chart_type_id).val();
		var chart_interface = $("#" + chart_inteface_id).val();
		
		$('ul#' + ul_container_id + ' li').each(function(e){
			var li_tag_id = $(this).attr('id');
			var stock_symbol = $("#s_"+li_tag_id+"_symbol_id").val();
			
			if(stock_symbol == 'NONE'){
				return;
			}
			$(this).click(function(){
				if (chart_interface == 'ichart'){
					//alert('http://ichart.yahoo.com/z?s='+sText+type);
					$('.static-stocks-chart-by-' + way).html('<img src="http://ichart.yahoo.com/z?s='+stock_symbol+'&q='+chart_type+'&z=s" />');
				}else{
					//alert('http://chart.finance.yahoo.com/t?s='+sText+type);
					$('.static-stocks-chart-by-' + way).html('<img src="http://chart.finance.yahoo.com/t?s='+stock_symbol+'&q='+chart_type+'&z=s&width=350&height=205" />');
				}
			});
		});
	}
	
	//call function
	static_stock_show('static-stocks-by-interface', 's_static_INTERFACE_showchart_id', 's_static_INTERFACE_chart_type_id', 's_static_INTERFACE_chart_interface_id', 'interface' );
	static_stock_show('static-stocks-by-shortcode', 's_static_SHORTCODE_showchart_id', 's_static_SHORTCODE_chart_type_id', 's_static_SHORTCODE_chart_interface_id', 'shortcode');
	static_stock_show('static-stocks-by-advanced', 's_static_ADVANCED_showchart_id', 's_static_ADVANCED_chart_type_id', 's_static_ADVANCED_chart_interface_id', 'advanced');
})