jQuery(document).ready( function($) {
	
	var move_up_img = $("#s_ticker_move_up_icon_url_id").val();
	var move_down_img = $("#s_ticker_move_down_icon_url_id").val();
	var trash_img = $("#s_ticker_trash_icon_url_id").val();
	
	//for ticker stock
	$('#add_admin_stock_field').click(function() {
		$('#st_options_body').append(
			'<tr>' + 
			'<td style="padding:0px;"><input type="text" name="s_ticker_stock_codes[]" value="" /></td>' + 
			'<td style="padding:0px;"><input type="text" name="s_ticker_stock_names[]" value="" /></td>' +
			'<td style="padding:0px;">' +
			'<a href="javascript:void(0);" title="Move Up" class="s_ticker_up"><img src=' + move_up_img + ' /></a>' +
			'<a href="javascript:void(0);" title="Move Down" class="s_ticker_down"><img src=' + move_down_img + ' /></a>' +
			'</td>' +
			'<td style="padding:0px;"><a href="javascript:void(0);" title="Delete" class="s_ticker_delete_stock"><img src=' + trash_img +' /></a></td>' +
			'</tr>'
		);
		return false;
	});
  
	$('#add_admin_currency_field').click(function() {
		$('#st_currency_options_body').append(
			'<tr>' + 
			'<td style="padding:0px;"><input type="text" name="s_ticker_currency_codes[]" value="" /></td>' + 
			'<td style="padding:0px;"><input type="text" name="s_ticker_currency_names[]" value="" /></td>' +
			'<td style="padding:0px;">' +
			'<a href="javascript:void(0);" title="Move Up" class="s_ticker_up"><img src=' + move_up_img + ' /></a>' +
			'<a href="javascript:void(0);" title="Move Down" class="s_ticker_down"><img src=' + move_down_img + ' /></a>' +
			'</td>' +
			'<td style="padding:0px;"><a href="javascript:void(0);" title="Delete" class="s_ticker_delete_currency"><img src=' + trash_img +' /></a></td>' +
			'</tr>'
		);
		return false;											
	});
  	
	$(".s_ticker_up,.s_ticker_down").click(function(){
		var row = $(this).parents("tr:first");
		if ($(this).is(".s_ticker_up")) {
			row.insertBefore(row.prev());
		} else if ($(this).is(".s_ticker_down")) {
			row.insertAfter(row.next());
		}
	});
	
	$('.s_ticker_delete_stock, .s_ticker_delete_currency').live('click', function() {
		$(this).parents('tr').remove();
		return false;
	});
  
	$('[name="s_ticker_display_chart"]').change(function(){
		var showChart = $(this).val();
		if (showChart == 'on'){
			$("#chart_disp_type_id").css('display', 'block');
			$("#chart_disp_position_id").css('display', 'block');
			$("#chart_disp_interface_id").css('display', 'block');
		}else{
			$("#chart_disp_type_id").css('display', 'none');
			$("#chart_disp_position_id").css('display', 'none');
			$("#chart_disp_interface_id").css('display', 'none');
		}
	});

  
	//for static stock
	$('#add_admin_static_stock_field').click(function() {
		$('#static_st_options_body').append(
			'<tr>' + 
			'<td style="padding:0px;"><input type="text" name="s_static_stock_codes[]" value="" /></td>' + 
			'<td style="padding:0px;"><input type="text" name="s_static_stock_names[]" value="" /></td>' +
			'<td style="padding:0px;">' +
			'<a href="javascript:void(0);" title="Move Up" class="s_ticker_up"><img src=' + move_up_img + ' /></a>' +
			'<a href="javascript:void(0);" title="Move Down" class="s_ticker_down"><img src=' + move_down_img + ' /></a>' +
			'</td>' +
			'<td style="padding:0px;"><a href="javascript:void(0);" title="Delete" class="s_ticker_delete_stock"><img src=' + trash_img +' /></a></td>' +
			'</tr>'
		);
		return false;
	});
  
	$('#add_admin_static_currency_field').click(function() {
		$('#st_static_currency_options_body').append(
			'<tr>' + 
			'<td style="padding:0px;"><input type="text" name="s_static_currency_codes[]" value="" /></td>' + 
			'<td style="padding:0px;"><input type="text" name="s_static_currency_names[]" value="" /></td>' +
			'<td style="padding:0px;">' +
			'<a href="javascript:void(0);" title="Move Up" class="s_ticker_up"><img src=' + move_up_img + ' /></a>' +
			'<a href="javascript:void(0);" title="Move Down" class="s_ticker_down"><img src=' + move_down_img + ' /></a>' +
			'</td>' +
			'<td style="padding:0px;"><a href="javascript:void(0);" title="Delete" class="s_ticker_delete_currency"><img src=' + trash_img +' /></a></td>' +
			'</tr>'
		);
		return false;											
	});
  
	$('[name="s_static_display_chart"]').change(function(){
		var showChart = $(this).val();
		if (showChart == 'on'){
			$("#static_chart_disp_type_id").css('display', 'block');
			$("#static_chart_disp_interface_id").css('display', 'block');
		}else{
			$("#static_chart_disp_type_id").css('display', 'none');
			$("#static_chart_disp_interface_id").css('display', 'none');
		}
	});

	
	//for specified stotck
	$('#add_admin_specified_stock_field').click(function() {
		$('#specified_stock_value_table').append(
			'<tr>' + 
			'<td style="padding:0px;"><input type="text" name="s_specified_stock_codes[]" value="" /></td>' + 
			'<td style="padding:0px;"><input type="text" name="s_specified_stock_names[]" value="" /></td>' +
			'<td style="padding:0px;">' +
			'<a href="javascript:void(0);" title="Move Up" class="s_ticker_up"><img src=' + move_up_img + ' /></a>' +
			'<a href="javascript:void(0);" title="Move Down" class="s_ticker_down"><img src=' + move_down_img + ' /></a>' +
			'</td>' +
			'<td style="padding:0px;"><a href="javascript:void(0);" title="Delete" class="s_ticker_delete_stock"><img src=' + trash_img +' /></a></td>' +
			'</tr>'
		);
		return false;
	});
  
	$('#add_admin_specified_currency_field').click(function() {
		$('#st_specified_currency_options_body').append(
			'<tr>' + 
			'<td style="padding:0px;"><input type="text" name="s_specified_currency_codes[]" value="" /></td>' + 
			'<td style="padding:0px;"><input type="text" name="s_specified_currency_names[]" value="" /></td>' +
			'<td style="padding:0px;">' +
			'<a href="javascript:void(0);" title="Move Up" class="s_ticker_up"><img src=' + move_up_img + ' /></a>' +
			'<a href="javascript:void(0);" title="Move Down" class="s_ticker_down"><img src=' + move_down_img + ' /></a>' +
			'</td>' +
			'<td style="padding:0px;"><a href="javascript:void(0);" title="Delete" class="s_ticker_delete_currency"><img src=' + trash_img +' /></a></td>' +
			'</tr>'
		);
		return false;											
	});
  
})