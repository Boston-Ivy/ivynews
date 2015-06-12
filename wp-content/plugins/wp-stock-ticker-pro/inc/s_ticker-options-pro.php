<?php

// ********** OPTIONS PAGE **********
// **********************************

function s_ticker_options_pro() {
  
	if (!current_user_can('manage_options'))  {
		wp_die( __('You do not have sufficient permissions to access this page.') );
	}
	$s_ticker_license_key = trim(get_option('s_ticker_license_key'));
	$s_ticker_license_status = trim(get_option('s_ticker_license_key_status'));
	if (!$s_ticker_license_key || $s_ticker_license_status != 'valid'){
		$s_ticker_license_status = 'invalid';
		delete_option( 's_ticker_license_key_status' );
	}
  
	$readOnlyStr = ''; 
	$action = $_SERVER["REQUEST_URI"];
	if ( $s_ticker_license_status !== false && $s_ticker_license_status == 'valid' ) {
		$readOnlyStr = 'readonly';
		$action = 'options.php';
	}
?>

  <div class="wrap">
	<img src="<?PHP echo plugins_url(); ?>/wp-stock-ticker-pro/images/help-for-wordpress-small.png" align="left"/>
    <h2>WP Stock Ticker Pro</h2>
    <p>Use the settings below to manage the stock market codes and indices.</p> 
	<p>The plugin pulls data from Yahoo! Finance so any correctly formatted code from there will work with this plugin.</p>
    
    <form action="<?php echo $action; ?>" method="POST" id="stock_ticker">
      <?php 
	  	if ( $s_ticker_license_status !== false && $s_ticker_license_status == 'valid' ) {
	  		settings_fields( 's_ticker-settings' );
		}
	  ?>
      <h3>Plugin License</h3>
      <table>
          <tr valign="top">
            <td>Please enter your your license key</td>
            <td>
            <input id="s_ticker_license_key_id" name="s_ticker_license_key" type="text" value="<?php echo $s_ticker_license_key; ?>" size="50" <?php echo $readOnlyStr; ?> />
                <?php
				if( $s_ticker_license_status !== false && $s_ticker_license_status == 'valid' ) {
					echo '<span style="color:green;">Active</span>';
            		echo '<input type="submit" class="button-secondary" name="s_ticker_license_deactivate" value="Deactivate License" style="margin-left:20px;" />';
				}else{
					if ($s_ticker_license_key !== false && strlen($s_ticker_license_key) > 0) { 
						echo '<span style="color:red;">Inactive</span>'; 
					}
					echo '<input type="submit" class="button-secondary" name="s_ticker_license_activate" value="Activate License" style="margin-left:20px;" />';
				}
				wp_nonce_field( 's_ticker_license_key_nonce', 's_ticker_license_key_nonce' ); 
				?>
            </td>
          </tr>
      </table>
      <?php 
	  	if ( $s_ticker_license_status !== false && $s_ticker_license_status == 'valid' ) { 
	  ?>
	  <hr>
      <h3>Settings for Ticker Display</h3>
      <p>Use the shortcode [s_ticker_display] to display your ticker, or visit the documentation for other options</p>
      <p><a target="_blank" href="http://helpforwp.com/plugins/wp-stock-ticker/wp-stock-ticker-documentation/#ticker">Learn more about configuring the stock ticker</a></p>
      <h4>Stocks</h4>
      <p>Click <a href="javascript:void(0);" class="label success" id="add_admin_stock_field">add Stock</a> to add another field for a new code.</p>
      <table class="form-table" id="stock_value_table" style="width:800px; text-align:left;">
        <thead>
          <tr>
            <th style="font-size:12px; width:200px;">Ticker Symbol</th>
            <th style="font-size:12px; width:200px;">Ticker Name</th>
            <th style="width:60px;">Order</th>
            <th style="width:200px;"></th>
          </tr>
        </thead>         

        <tbody id="st_options_body">

          <?php
		  
		  $move_up_icon_url = plugins_url().'/wp-stock-ticker-pro/images/move-up-small.png';
		  $move_down_icon_url = plugins_url().'/wp-stock-ticker-pro/images/move-down-small.png';
		  $trash_icon_url = plugins_url().'/wp-stock-ticker-pro/images/trash.png';

          if($values = get_option('s_ticker_stock_codes')) {
            //print '<pre>'.print_r($values, true).'<pre>'; die();
			$stockNames = get_option('s_ticker_stock_names');
			$i = 0;
            foreach($values as $val) {
			  if ($stockNames && is_array($stockNames) && count($stockNames) > 0){
		  	  	$stockName = isset( $stockNames[strtoupper($val)] ) ? $stockNames[strtoupper($val)] : '';
			  }
			  $i++;
              echo trim('
                <tr valign="top">
                  <td style="padding:0;"><input type="text" name="s_ticker_stock_codes[]" value="'. $val .'" /></td>
				  <td style="padding:0;"><input type="text" name="s_ticker_stock_names[]" value="'. $stockName .'" /></td>
				  <td style="padding:0;">
				  	<a href="javascript:void(0);" title="Move Up" class="s_ticker_up"><img src='.$move_up_icon_url.' /></a>
					<a href="javascript:void(0);" title="Move Down" class="s_ticker_down"><img src='.$move_down_icon_url.' /></a>
				  </td>
                  <td style="padding:0;"><a href="javascript:void(0);" title="Delete" class="s_ticker_delete_stock"><img src='.$trash_icon_url.' /></a></td>
                </tr>
              ');
            }
          }
          ?> 
                 
        </tbody>

      </table>
      
	  <h4>Currency Settings</h4>
      <p>Click <a href="javascript:void(0);" class="label success" id="add_admin_currency_field">add Currency</a> to add another field for a new code.</p>
      <table class="form-table" id="stock_value_table" style="width:800px; text-align:left;">
        <thead>
          <tr>
            <th style="font-size:12px; width:200px;">Currency Symbol</th>
            <th style="font-size:12px; width:200px;">Currency Name</th>
            <th style="width:60px;">Order</th>
            <th style="width:200px;"></th>
          </tr>
        </thead>
        <tbody id="st_currency_options_body">
		<?php
        if($values = get_option('s_ticker_currency_codes')) {
			//print '<pre>'.print_r($values, true).'<pre>'; die();
			$currencyNames = get_option('s_ticker_currency_names');
			$i = 0;
			foreach($values as $val) {
				if ($currencyNames && is_array($currencyNames) && count($currencyNames) > 0){
					$currencyName = isset( $currencyNames[strtoupper($val)] ) ? $currencyNames[strtoupper($val)] : '';
				}
				$i++;
				echo trim('
				<tr valign="top" class="stock_ticker_row">
					<td style="padding:0;"><input type="text" name="s_ticker_currency_codes[]" value="'. $val .'" /></td>
					<td style="padding:0;"><input type="text" name="s_ticker_currency_names[]" value="'. $currencyName .'" /></td>
					<td style="padding:0;">
						<a href="javascript:void(0);" title="Move Up" class="s_ticker_up"><img src='.$move_up_icon_url.' /></a>
						<a href="javascript:void(0);" title="Move Down" class="s_ticker_down"><img src='.$move_down_icon_url.' /></a>
					</td>
					<td style="padding:0;"><a href="javascript:void(0);" title="Delete" class="s_ticker_delete_currency"><img src='.$trash_icon_url.' /></a></td>
				</tr>
				');
			}
        }
        ?> 
        </tbody>

      </table>
      <h4 style="margin-top: 30px;">Chart Display Settings (for ticker)</h4>
      <p>If enabled, when a user clicks an individual ticker symbol a chart will be displayed.</p>
      <p>You can also choose which of the two Yahoo! chart services to use, some charts are not available on both. </p>
      <?php
            $displayChart = get_option('s_ticker_display_chart', 'off');
            $chartType = get_option('s_ticker_display_chart_type', 'l');
            $chartPostion = get_option('s_ticker_display_chart_position', 'top');
            $chartInterface = get_option('s_ticker_display_chart_interface', 'ichart');
      ?>
      <table cellpadding="2">
          <tr style="display:block;">
              <td style="width:130px;">Enabled chart display:<td>
              <td>
                  <input type="radio" id="s_ticker_display_chart_on_id" name="s_ticker_display_chart" value="on" <?php if ( $displayChart == 'on' ) echo 'checked'; ?> /> On&nbsp;&nbsp;&nbsp;
                  <input type="radio" id="s_ticker_display_chart_off_id" name="s_ticker_display_chart" value="off"  <?php if ( $displayChart == 'off' ) echo 'checked'; ?> /> Off
              </td>
          </tr>
          <tr id="chart_disp_type_id" style="display:<?php if ($displayChart == 'on') { echo 'block'; } else{ echo 'none'; } ?>">
              <td style="width:130px;">Chart type:<td>
              <td>
                  <input type="radio" id="s_ticker_display_chart_line_id" name="s_ticker_display_chart_type" value="l" <?php if ( $chartType == 'l' ) echo 'checked'; ?> /> Line
                  <input type="radio" id="s_ticker_display_chart_bar_id" name="s_ticker_display_chart_type" value="b" <?php if ( $chartType == 'b' ) echo 'checked'; ?> /> Bar
                  <input type="radio" id="s_ticker_display_chart_candel_id" name="s_ticker_display_chart_type" value="c" <?php if ( $chartType == 'c' ) echo 'checked'; ?> /> Candle
              </td>
          </tr>
          <tr id="chart_disp_position_id" style="display:<?php if ($displayChart == 'on') { echo 'block'; } else{ echo 'none'; } ?>">
              <td style="width:130px;">Chart position:<td>
              <td>
                  <input type="radio" id="s_ticker_display_chart_position_top_id" name="s_ticker_display_chart_position" value="top" <?php if ( $chartPostion == 'top') echo 'checked'; ?> /> Top
                  <input type="radio" id="s_ticker_display_chart_position_below_id" name="s_ticker_display_chart_position" value="below" <?php if ( $chartPostion == 'below' ) echo 'checked'; ?> /> Below
              </td>
          </tr>
          <tr id="chart_disp_interface_id" style="display:<?php if ($displayChart == 'on') { echo 'block'; } else{ echo 'none'; } ?>">
              <td style="width:130px;">Chart interface:<td>
              <td>
                  <input type="radio" id="s_ticker_display_chart_interface_ichart_id" name="s_ticker_display_chart_interface" value="ichart" <?php if ( $chartInterface == 'ichart') echo 'checked'; ?> /> Use ichart.yahoo.com 
                  <input type="radio" id="s_ticker_display_chart_interface_chartf_id" name="s_ticker_display_chart_interface" value="chartf" <?php if ( $chartInterface == 'chartf' ) echo 'checked'; ?> /> Use chart.finance.yahoo.com 
              </td>
          </tr>
      </table>
      <h4>Ticker Speed Option</h4>
      <?php
		$speed = get_option('s_ticker_display_speed', 1);
	  ?>
      <select name="s_ticker_display_speed" id="s_ticker_display_speed_id">
      	<option value="0"<?php if($speed == 0) echo ' selected="selected"'; ?>>stop</option>
        <option value="1"<?php if($speed == 1) echo ' selected="selected"'; ?>>slow</option>
        <option value="4"<?php if($speed == 4) echo  'selected="selected"'; ?>>medium</option>
        <option value="7"<?php if($speed == 7) echo ' selected="selected"'; ?>>fast</option>
      </select>
      <hr>
      <h3>Settings for Static Display</h3>
       <p>Use the shortcode [s_static_display] to use the static display, or visit the documentation for other options</p>
      <p><a target="_blank" href="http://helpforwp.com/plugins/wp-stock-ticker/wp-stock-ticker-documentation/#static1">Learn more about configuring the static display</a></p>
      <h4>Stocks (Static Display)</h4>
      <p>Click <a href="javascript:void(0);" class="label success" id="add_admin_static_stock_field">add Stock</a> to add another field for a new code.</p>
	  <table class="form-table" id="static_stock_value_table" style="width:800px; text-align:left;">
        <thead>
          <tr>
            <th style="font-size:12px; width:200px;">Ticker Symbol</th>
            <th style="font-size:12px; width:200px;">Ticker Name</th>
            <th style="width:60px;">Order</th>
            <th style="width:200px;"></th>
          </tr>
        </thead>
        <tbody id="static_st_options_body">
		<?php
        if($values = get_option('s_static_stock_codes')) {
			//print '<pre>'.print_r($values, true).'<pre>'; die();
			$stockNames = get_option('s_static_stock_names');
			$i = 0;
			foreach($values as $val) {
				if ($stockNames && is_array($stockNames) && count($stockNames) > 0){
					$stockName = isset( $stockNames[strtoupper($val)] ) ? $stockNames[strtoupper($val)] : '';
				}
				$i++;
				echo trim('
				<tr>
				<td style="padding:0px;"><input type="text" name="s_static_stock_codes[]" value="'. $val .'" /></td>
				<td style="padding:0px;"><input type="text" name="s_static_stock_names[]" value="'. $stockName .'" /></td>
				<td style="padding:0px;">
					<a href="javascript:void(0);" title="Move Up" class="s_ticker_up"><img src='.$move_up_icon_url.' /></a>
					<a href="javascript:void(0);" title="Move Down" class="s_ticker_down"><img src='.$move_down_icon_url.' /></a>
				</td>
				<td style="padding:0;"><a href="javascript:void(0);" title="Delete" class="s_ticker_delete_stock"><img src='.$trash_icon_url.' /></a></td>
				</tr>
				');
			}
        }
        ?> 
        </tbody>
      </table>
      
	  <h4>Currency Settings (Static Display)</h4>
      <p>Click <a href="javascript:void(0);" class="label success" id="add_admin_static_currency_field">add Currency</a> to add another field for a new code.</p>
	  <table class="form-table" id="static_currency_value_table" style="width:800px; text-align:left;">
        <thead>
          <tr>
            <th style="font-size:12px; width:200px;">Currency Symbol</th>
            <th style="font-size:12px; width:200px;">Currency Name</th>
            <th style="width:60px;">Order</th>
            <th style="width:200px;"></th>
          </tr>
        <tbody id="st_static_currency_options_body">
          <?php
          if($values = get_option('s_static_currency_codes')) {
            //print '<pre>'.print_r($values, true).'<pre>'; die();
			$currencyNames = get_option('s_static_currency_names');
			$i = 0;
            foreach($values as $val) {
			  if ($currencyNames && is_array($currencyNames) && count($currencyNames) > 0){
		  	  	$currencyName = isset( $currencyNames[strtoupper($val)] ) ? $currencyNames[strtoupper($val)] : '';
			  }
			  $i++;
              echo trim('
                <tr>
                  <td style="padding:0px;"><input type="text" name="s_static_currency_codes[]" value="'. $val .'" /></td>
				  <td style="padding:0px;"><input type="text" name="s_static_currency_names[]" value="'. $currencyName .'" /></td>
				  <td style="padding:0px;">
					<a href="javascript:void(0);" title="Move Up" class="s_ticker_up"><img src='.$move_up_icon_url.' /></a>
					<a href="javascript:void(0);" title="Move Down" class="s_ticker_down"><img src='.$move_down_icon_url.' /></a>
				  </td>
				  <td style="padding:0;"><a href="javascript:void(0);" title="Delete" class="s_ticker_delete_currency"><img src='.$trash_icon_url.' /></a></td>
                </tr>
              ');
            }
          }
          ?> 
                 
        </tbody>
      </table>
    <h4 style="margin-top: 30px;">Container Settings</h4>
    <p>Here you may set the container width of sotcks and chart</p>
  	<table>
        <tr>
          <td>width<td>
          <td>
          	<input type="text" id="s_static_container_width_id" name="s_static_container_width" value="<?php echo get_option('s_static_container_width', '620'); ?>" size="4" /> px
          </td>
        </tr>
  	</table>
    
    <h4 style="margin-top: 30px;">Chart Display Settings</h4>
    <p>If enabled, when a user clicks an individual ticker symbol a chart will be displayed.</p>
    <p>You can also choose which of the two Yahoo! chart services to use, some charts are not available on both. </p>
    <?php
		$displayChart = get_option('s_static_display_chart', 'off');
		$chartType = get_option('s_static_display_chart_type', 'l');
		$chartInterface = get_option('s_static_display_chart_interface', 'ichart');
	?>
    <table cellpadding="2">
    	<tr style="display:block;">
    		<td style="width:130px;">Enabled chart display:<td>
    		<td>
                <input type="radio" id="s_static_display_chart_on_id" name="s_static_display_chart" value="on" <?php if ( $displayChart == 'on' ) echo 'checked'; ?> /> On&nbsp;&nbsp;&nbsp;
                <input type="radio" id="s_static_display_chart_off_id" name="s_static_display_chart" value="off"  <?php if ( $displayChart == 'off' ) echo 'checked'; ?> /> Off
            </td>
    	</tr>
        <tr id="static_chart_disp_type_id" style="display:<?php if ($displayChart == 'on') { echo 'block'; } else{ echo 'none'; } ?>">
    		<td style="width:130px;">Chart type:<td>
    		<td>
                <input type="radio" id="s_static_display_chart_line_id" name="s_static_display_chart_type" value="l" <?php if ( $chartType == 'l' ) echo 'checked'; ?> /> Line
                <input type="radio" id="s_static_display_chart_bar_id" name="s_static_display_chart_type" value="b" <?php if ( $chartType == 'b' ) echo 'checked'; ?> /> Bar
                <input type="radio" id="s_static_display_chart_candel_id" name="s_static_display_chart_type" value="c" <?php if ( $chartType == 'c' ) echo 'checked'; ?> /> Candle
            </td>
    	</tr>
        <tr id="static_chart_disp_interface_id" style="display:<?php if ($displayChart == 'on') { echo 'block'; } else{ echo 'none'; } ?>">
    		<td style="width:130px;">Chart interface:<td>
    		<td>
                <input type="radio" id="s_static_display_chart_interface_ichart_id" name="s_static_display_chart_interface" value="ichart" <?php if ( $chartInterface == 'ichart') echo 'checked'; ?> /> Use ichart.yahoo.com 
                <input type="radio" id="s_static_display_chart_interface_chartf_id" name="s_static_display_chart_interface" value="chartf" <?php if ( $chartInterface == 'chartf' ) echo 'checked'; ?> /> Use chart.finance.yahoo.com 
            </td>
    	</tr>
  	</table>
	<hr>
    <h3>Advanced Ticker / Static Display</h3>
    <p>Use the shortcode [s_static_display codes="AAPL,GOOG"] or [s_ticker_display codes="AAPL,GOOG"] for advanced display, visit the documentation to learn more</p>
    <p><a target="_blank" href="http://helpforwp.com/plugins/wp-stock-ticker/wp-stock-ticker-documentation/#static2">Learn more about advanced display</a></p>
    <h4>Stocks - (advanced display)</h4>
    <p>Click <a href="javascript:void(0);" class="label success" id="add_admin_specified_stock_field">add Stock</a> to add another field for a new code.</p>
    <table class="form-table" id="specified_stock_value_table" style="width:800px; text-align:left;">
        <thead>
          <tr>
            <th style="font-size:12px; width:200px;">Ticker Symbol</th>
            <th style="font-size:12px; width:200px;">Ticker Name</th>
            <th style="width:60px;">Order</th>
            <th style="width:200px;"></th>
          </tr>
        </thead>
        <tbody id="specified_st_options_body">
        <?php
        if($values = get_option('s_specified_stock_codes')) {
            //print '<pre>'.print_r($values, true).'<pre>'; die();
            $stockNames = get_option('s_specified_stock_names');
            $i = 0;
            foreach($values as $val) {
                if ($stockNames && is_array($stockNames) && count($stockNames) > 0){
                    $stockName = isset( $stockNames[strtoupper($val)] ) ? $stockNames[strtoupper($val)] : '';
                }
                $i++;
                echo '<tr>
                        <td style="padding:0px;"><input type="text" name="s_specified_stock_codes[]" value="'. $val .'" /></td>
                        <td style="padding:0px;"><input type="text" name="s_specified_stock_names[]" value="'. $stockName .'" /></td>
						<td style="padding:0px;">
							<a href="javascript:void(0);" title="Move Up" class="s_ticker_up"><img src='.$move_up_icon_url.' /></a>
							<a href="javascript:void(0);" title="Move Down" class="s_ticker_down"><img src='.$move_down_icon_url.' /></a>
					  	</td>
					  	<td style="padding:0px;"><a href="javascript:void(0);" title="Delete" class="s_ticker_delete_stock"><img src='.$trash_icon_url.' /></a></td>
                     </tr>';
            }
        }
        ?> 
        </tbody>
    </table>
    
    <h4>Currency Settings - (advanced display)</h4>
    <p>Click <a href="javascript:void(0);" class="label success" id="add_admin_specified_currency_field">add Currency</a> to add another field for a new code.</p>
    <table class="form-table" id="specified_currency_value_table" style="width:800px; text-align:left;">
        <thead>
          <tr>
            <th style="font-size:12px; width:200px;">Ticker Symbol</th>
            <th style="font-size:12px; width:200px;">Ticker Name</th>
            <th style="width:60px;">Order</th>
            <th style="width:200px;"></th>
          </tr>
        </thead>         
        <tbody id="st_specified_currency_options_body">
        <?php
        if($values = get_option('s_specified_currency_codes')) {
            //print '<pre>'.print_r($values, true).'<pre>'; die();
            $currencyNames = get_option('s_specified_currency_names');
            $i = 0;
            foreach($values as $val) {
                if ($currencyNames && is_array($currencyNames) && count($currencyNames) > 0){
                    $currencyName = isset( $currencyNames[strtoupper($val)] ) ? $currencyNames[strtoupper($val)] : '';
                }
                $i++;
                echo '<tr>
                        <td style="padding:0px;"><input type="text" name="s_specified_currency_codes[]" value="'. $val .'" /></td>
                        <td style="padding:0px;"><input type="text" name="s_specified_currency_names[]" value="'. $currencyName .'" /></td>
						<td style="padding:0px;">
							<a href="javascript:void(0);" title="Move Up" class="s_ticker_up"><img src='.$move_up_icon_url.' /></a>
							<a href="javascript:void(0);" title="Move Down" class="s_ticker_down"><img src='.$move_down_icon_url.' /></a>
					  	</td>
					  	<td style="padding:0px;"><a href="javascript:void(0);" title="Delete" class="s_ticker_delete_currency"><img src='.$trash_icon_url.' /></a></td>
                      </tr>';
            }
        }
        ?> 
        </tbody>
    </table>
    <hr>
    <h3 style="margin-top: 30px;">Cache Settings</h3>
	<p>This setting is the amount of time that a set of prices will be cached in WordPress, in minutes.</p>
	<p>Setting it to 5 for example will force WordPress retrieve new data every 5 minutes (assuming that a user is accessing the front end of the site)</p>
	<p><strong>This setting will affect both the static and the ticker display.</strong></p>
      <table>
        <tr>
          <td>Duration in minutes<td>
          <td><input type="text" id="s_ticker_field_cache" name="s_ticker_field_cache" value="<?php echo get_option('s_ticker_field_cache', '1'); ?>" /></td>
        </tr>
      </table>
	<h3 style="margin-top: 30px;">Link to HelpForWP.com</h3>
	<p>By default there is a link to WP Stock Ticker at the end of the ticker display, if you would like disable this link, enter the word 'disable' in the field below.</p>
	<table>
        <tr>
          <td>Disable link in ticker<td>
          <td><input type="text" id="s_ticker_field_link" name="s_ticker_field_link" value="<?php echo get_option('s_ticker_field_link', '1'); ?>" /></td>
        </tr>
      </table>


      <p style="margin-top: 20px"><button class="button-primary" type="submit" id="admin_stock_submit">Save Settings</button></p>
      <p id="admin-st-success"></p>
      <?php } //end of if ( $s_ticker_license_status !== false && $s_ticker_license_status == 'valid' ) { ?>
    </form>
	<br />
   	<h3>Plugin Support Centre</h3>
    <ul>
	    <li><a href="http://helpforwp.com/forum/" target="_blank">Visit the Support Centre</a> if you have a question on using this plugin</li>
    	<li>We also provide <a href="http://helpforwp.com/forum/" target="_blank">Priority Support</a> for a small fee, which includes installation, configuration and detailed troubleshooting</li>
    </ul>
	<br />
    <?php
		global $_wpstp_messager;
		
		$_wpstp_messager->eddslum_plugin_option_page_update_center();
	?>
    <input type="hidden" id="s_ticker_move_up_icon_url_id" value="<?php echo $move_up_icon_url; ?>" />
    <input type="hidden" id="s_ticker_move_down_icon_url_id" value="<?php echo $move_down_icon_url; ?>" />
    <input type="hidden" id="s_ticker_trash_icon_url_id" value="<?php echo $trash_icon_url; ?>" />
</div>


<?php
require_once('h4wp_info.php');
}

