<?php

/*
Plugin Name: WP Stock Ticker Pro
Plugin URI: http://HelpForWP.com
Description: A jquery based stock ticker using data from Yahoo finance via YQL (Yahoo Query Language). The ticker will display stock prices, commodities as well as currency exchange rates.
Version: 3.2
Author: HelpForWP.com
Author URI: http://HelpForWP.com

------------------------------------------------------------------------

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, 
or any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA

*/

require_once(dirname( __FILE__ ) . '/WPStockTickerWidgetPro.php');

global $_wpstp_plugin_version, $_wpstp_plugin_author, $_wpstp_plugin_name, $_wpstp_home_url, $_wpstp_menu_url, $_wpstp_messager;

$_wpstp_plugin_version = '3.2';
$_wpstp_plugin_author = 'TheDMA';
$_wpstp_plugin_name = 'WP Stock Ticker Pro';
$_wpstp_home_url = 'http://helpforwp.com';
$_wpstp_menu_url = admin_url('options-general.php?page=s_ticker-options-pro');

if( !class_exists( 'EDD_SL_Plugin_Updater_4_WPStockTicker' ) ) {
	// load our custom updater
	require_once(dirname( __FILE__ ) . '/inc/EDD_SL_Plugin_Updater.php');
}

$_wpstp_license_key = trim( get_option( 's_ticker_license_key' ) );
// setup the updater
$_wpstp_updater = new EDD_SL_Plugin_Updater_4_WPStockTicker( $_wpstp_home_url, __FILE__, array( 
		'version' 	=> $_wpstp_plugin_version, 				// current version number
		'license' 	=> $_wpstp_license_key, 		// license key (used get_option above to retrieve from DB)
		'item_name' => $_wpstp_plugin_name, 	// name of this plugin
		'author' 	=> $_wpstp_plugin_author  // author of this plugin
	)
);

//for new version message and expiring version message shown on dashboard
if( !class_exists( 'EddSLUpdateExpiredMessagerV3forWPStockTickerPro' ) ) {
	// load our custom updater
	require_once(dirname( __FILE__ ) . '/inc/edd-sl-update-expired-messager.php');
}
$init_arg = array();
$init_arg['plugin_name'] = $_wpstp_plugin_name;
$init_arg['plugin_download_id'] = 1100;
$init_arg['plugin_folder'] = 'wp-stock-ticker-pro';
$init_arg['plugin_file'] = basename(__FILE__);
$init_arg['plugin_version'] = $_wpstp_plugin_version;
$init_arg['plugin_home_url'] = $_wpstp_home_url;
$init_arg['plugin_sell_page_url'] = 'http://helpforwp.com/plugins/wp-stock-ticker/';
$init_arg['plugin_author'] = $_wpstp_plugin_author;
$init_arg['plugin_setting_page_url'] = $_wpstp_menu_url;
$init_arg['plugin_license_key_opiton_name'] = 's_ticker_license_key';
$init_arg['plugin_license_status_option_name'] = 's_ticker_license_key_status';


$_wpstp_messager = new EddSLUpdateExpiredMessagerV3forWPStockTickerPro( $init_arg );

class WPStockTickerPro {
	
  public function __construct() {

    if ( is_admin() ) {
		add_action( 'wp_ajax_nopriv_stock-ticker', array(&$this, 's_ticker') );
		add_action( 'wp_ajax_stock-ticker', array(&$this, 's_ticker') );
		require 'inc/s_ticker-options-pro.php';
		
		add_action( 'admin_menu', array($this, 's_ticker_menu') );
		add_action( 'admin_init', array($this, 'register_s_ticker_settings') );
		
		add_action( 'admin_init', array($this, 's_ticker_activate_license') );
		add_action( 'admin_init', array($this, 's_ticker_deactivate_license') );
		
		add_action( 'admin_enqueue_scripts', array(&$this, 's_ticker_enqueue_scripts'));
    }

    if( !is_admin() ) {
      add_action('wp_enqueue_scripts', array(&$this, 's_ticker_enqueue_styles'));
      add_shortcode('s_ticker_display', array(&$this, 's_ticker_shortcode') );
	  add_shortcode('s_static_display', array(&$this, 's_static_shortcode') );
    }
	
	//for ticker
    add_action('update_option_s_ticker_stock_codes', array(&$this, 's_ticker_remove_transient_4_ticker'));
	add_action('update_option_s_ticker_currency_codes', array(&$this, 's_ticker_remove_transient_4_ticker'));
	//for static
	add_action('update_option_s_static_stock_codes', array(&$this, 's_ticker_remove_transient_4_static'));
	add_action('update_option_s_static_currency_codes', array(&$this, 's_ticker_remove_transient_4_static'));
	//for advanced
	add_action('update_option_s_specified_stock_codes', array(&$this, 's_ticker_remove_transient_4_specified'));
	add_action('update_option_s_specified_currency_codes', array(&$this, 's_ticker_remove_transient_4_specified'));

    add_action('wp_enqueue_scripts', array(&$this, 's_ticker_enqueue_scripts'));

	register_uninstall_hook( __FILE__, 'WPStockTickerPro::s_ticker_deinstall' );

	register_activation_hook( __FILE__, array( &$this, 's_ticker_activate' ) );
	register_deactivation_hook( __FILE__, array( &$this, 's_ticker_deactivate' ) );

	//Plugin update actions
  }

  function s_ticker_enqueue_scripts() {
	global $_wpstp_plugin_version;
	
  	if ( is_admin() ) {
		wp_enqueue_script( 'wp-stock-ticker-pro', plugin_dir_url( __FILE__ ) . 'js/wp-stock-ticker-admin-pro.js', array( 'jquery' ), $_wpstp_plugin_version );
	}else{
		wp_enqueue_script( 'jquery-simply-scroll', plugins_url('js/jquery.simplyscroll.min.js', __FILE__), array('jquery'), $_wpstp_plugin_version );
		wp_enqueue_script( 'wp-stock-ticker', plugins_url('js/wp-stock-ticker-pro.js', __FILE__), array('jquery', 'jquery-simply-scroll'), $_wpstp_plugin_version );
	}
  }

  function s_ticker_enqueue_styles() {
	global $_wpstp_plugin_version;
	
    wp_enqueue_style( 'stockticker_style', plugins_url('css/wp-stock-ticker.css', __FILE__), array(), $_wpstp_plugin_version );
  }
  

function s_ticker_activate() {
		$current_options =	get_option('s_ticker_stock_codes');
		if(!$current_options){
			$s_default_codes = array('^NDX','^FTSE','^AORD','^AXJO');
			update_option('s_ticker_stock_codes', $s_default_codes );
		}
		$current_options =	get_option('s_ticker_stock_names');
		if(!$current_options){
			$s_default_stock_names= array('','','','');
			update_option('s_ticker_stock_names', $s_default_stock_names );
		}
		
		//for currency
		$current_options =	get_option('s_ticker_currency_codes');
		if(!$current_options){
			$s_default_currency_codes = array('AUDUSD=X','USDJPY=X');
			update_option('s_ticker_currency_codes', $s_default_currency_codes );
		}
		$current_options =	get_option('s_ticker_currency_names');
		if(!$current_options){
			$s_default_currency_names= array('','');
			update_option('s_ticker_currency_names', $s_default_currency_names );
		}
		$this->s_ticker_remove_transient();
}


function s_ticker_deactivate(){
	$this->s_ticker_remove_transient();
}


	public static function s_ticker_deinstall() {
		global $wpdb;
		
		if (function_exists('is_multisite') && is_multisite()) {
			// check if it is a network activation - if so, run the activation function for each blog id
			if (isset($_GET['networkwide']) && ($_GET['networkwide'] == 1)) {
				$old_blog = $wpdb->blogid;
				// Get all blog ids
				$blogids = $wpdb->get_col($wpdb->prepare("SELECT blog_id FROM $wpdb->blogs"));
				foreach ($blogids as $blog_id) {
					switch_to_blog($blog_id);
					WPStockTickerPro::s_ticker_run_uninstall();
				}
				switch_to_blog($old_blog);
				return;
			} 
		}
		WPStockTickerPro::s_ticker_run_uninstall();
		return;
	}
	public static function s_ticker_run_uninstall() {
		delete_option('s_ticker_stock_codes');
		delete_option('s_ticker_stock_names');	
		delete_option('s_ticker_currency_codes');
		delete_option('s_ticker_currency_names');	
		delete_option('s_ticker_field_cache');
		delete_option('s_ticker_field_link');
		delete_option('wpstp_helpforwp_username');
		delete_option('wpstp_helpforwp_password');
		delete_option('s_ticker_display_chart');
		delete_option('s_ticker_display_chart_type');
		delete_option('s_ticker_display_chart_position');
		delete_option('s_ticker_display_chart_interface');
		delete_option('s_ticker_display_speed');		
		
		delete_option('s_static_stock_codes');
		delete_option('s_static_stock_names');	
		delete_option('s_static_currency_codes');
		delete_option('s_static_currency_names');
		delete_option('s_static_container_width');
		delete_option('s_static_display_chart');
		delete_option('s_static_display_chart_type');
		delete_option('s_static_display_chart_interface');
		
		delete_option('s_specified_stock_codes');
		delete_option('s_specified_stock_names');	
		delete_option('s_specified_currency_codes');
		delete_option('s_specified_currency_names');
		
		delete_option('s_ticker_license_key');
		delete_option('s_ticker_license_key_status');
		
		return;
	}
  	function register_s_ticker_settings() {
		register_setting( 's_ticker-settings', 's_ticker_stock_codes' );
		register_setting( 's_ticker-settings', 's_ticker_stock_names' );
		register_setting( 's_ticker-settings', 's_ticker_currency_codes' );
		register_setting( 's_ticker-settings', 's_ticker_currency_names' );
		register_setting( 's_ticker-settings', 's_ticker_field_cache' );
		register_setting( 's_ticker-settings', 's_ticker_field_link' );
		register_setting( 's_ticker-settings', 'wpstp_helpforwp_username' );
		register_setting( 's_ticker-settings', 'wpstp_helpforwp_password' );
		register_setting( 's_ticker-settings', 's_ticker_display_chart' );
		register_setting( 's_ticker-settings', 's_ticker_display_chart_type' );
		register_setting( 's_ticker-settings', 's_ticker_display_chart_position' );
		register_setting( 's_ticker-settings', 's_ticker_display_chart_interface' );
		register_setting( 's_ticker-settings', 's_ticker_display_speed' );
		
		register_setting( 's_ticker-settings', 's_static_stock_codes' );
		register_setting( 's_ticker-settings', 's_static_stock_names' );
		register_setting( 's_ticker-settings', 's_static_currency_codes' );
		register_setting( 's_ticker-settings', 's_static_currency_names' );
		register_setting( 's_ticker-settings', 's_static_container_width' );
		register_setting( 's_ticker-settings', 's_static_display_chart' );
		register_setting( 's_ticker-settings', 's_static_display_chart_type' );
		register_setting( 's_ticker-settings', 's_static_display_chart_interface' );
		
		register_setting( 's_ticker-settings', 's_specified_stock_codes' );
		register_setting( 's_ticker-settings', 's_specified_stock_names' );
		register_setting( 's_ticker-settings', 's_specified_currency_codes' );
		register_setting( 's_ticker-settings', 's_specified_currency_names' );
		
		//when it come to call the call the callback function of the follow. All above options saved alaready
		register_setting( 's_ticker-settings', 's_ticker_call_option_process_fun', array($this, 's_ticker_option_process') );	
	}

	function s_ticker_menu() {
		add_options_page('WP-Stock-Ticker-Pro', 'WP Stock Ticker Pro', 'manage_options', 's_ticker-options-pro', 's_ticker_options_pro');
	}

	
	function s_ticker_remove_transient() {
		$this->s_ticker_remove_transient_4_ticker();
		$this->s_ticker_remove_transient_4_static();
		$this->s_ticker_remove_transient_4_specified();
	}
	
	function s_ticker_remove_transient_4_ticker() {
		delete_option('_transient_s_ticker');
		delete_option('_transient_timeout_s_ticker');
		delete_option('_transient_s_ticker_week_long_data');
		delete_option('_transient_timeout_s_ticker_week_long_data');
	}
	
	function s_ticker_remove_transient_4_static() {
		delete_option('_transient_s_static');
		delete_option('_transient_timeout_s_static');
		delete_option('_transient_s_static_week_long_data');
		delete_option('_transient_timeout_s_static_week_long_data');
	}
	
	function s_ticker_remove_transient_4_specified() {
		delete_option('_transient_s_specified');
		delete_option('_transient_timeout_s_specified');
		delete_option('_transient_s_specified_week_long_data');
		delete_option('_transient_timeout_s_specified_week_long_data');
	}
	
	function s_ticker_display() {
		$html = $this->s_ticker_shortcode(NULL, 'INTERFACE');
		return $html;
	}
	
	function s_ticker_show_widget( $widgetID ) {
		$html = $this->s_ticker_widget(NULL, 'WIDGET:'.$widgetID );
		return $html;
	}
	
	function s_static_display() {
		$html = $this->s_static_shortcode(NULL, 'INTERFACE');
		return $html;
	}
	
	function s_static_show_widget( $widgetID ) {
		$html = $this->s_static_shortcode(NULL, 'WIDGET:'.$widgetID );
		return $html;
	}
  
  
  
	function s_ticker_option_process(){
		//save ticker stocks codes & names
		$saved_stocks = get_option('s_ticker_stock_codes');
		$saved_stock_names = get_option('s_ticker_stock_names');
		$saved_currency_codes = get_option('s_ticker_currency_codes');
		$saved_currency_names = get_option('s_ticker_currency_names');
		
		//remove null value
		if ($saved_stocks){
			$stockNameRelAry = array();
			foreach($saved_stocks as $key => $stock){
				if (!$stock){
					unset($saved_stocks[$key]);
					unset($saved_stock_names[$key]); //remove same index from names array
				}
				$stockNameRelAry[strtoupper($stock)] = $saved_stock_names[$key];
			}
			update_option('s_ticker_stock_codes', $saved_stocks);
			update_option('s_ticker_stock_names', $stockNameRelAry);
		}
		if ($saved_currency_codes){
			$currencyNameRelAry = array();
			foreach($saved_currency_codes as $key => $currency){
				if (!$currency){
					unset($saved_currency_codes[$key]);
					unset($saved_currency_names[$key]); //remove same index from names array
				}
				$currencyNameRelAry[strtoupper($currency)] = $saved_currency_names[$key];
			}
			update_option('s_ticker_currency_codes', $saved_currency_codes);
			update_option('s_ticker_currency_names', $currencyNameRelAry);
		}
		
		//save static stocks codes & names
		$saved_static_stocks = get_option('s_static_stock_codes');
		$saved_static_stocks_names = get_option('s_static_stock_names');
		$saved_static_currency_codes = get_option('s_static_currency_codes');
		$saved_static_currency_names = get_option('s_static_currency_names');
		
		//remove null value
		if ($saved_static_stocks){
			$stockNameRelAry = array();
			foreach($saved_static_stocks as $key => $stock){
				if (!$stock){
					unset($saved_static_stocks[$key]);
					unset($saved_static_stocks_names[$key]); //remove same index from names array
				}
				$stockNameRelAry[strtoupper($stock)] = $saved_static_stocks_names[$key];
			}
			update_option('s_static_stock_codes', $saved_static_stocks);
			update_option('s_static_stock_names', $stockNameRelAry);
		}
		if ($saved_static_currency_codes){
			$currencyNameRelAry = array();
			foreach($saved_static_currency_codes as $key => $currency){
				if (!$currency){
					unset($saved_static_currency_codes[$key]);
					unset($saved_static_currency_names[$key]); //remove same index from names array
				}
				$currencyNameRelAry[strtoupper($currency)] = $saved_static_currency_names[$key];
			}
			update_option('s_static_currency_codes', $saved_static_currency_codes);
			update_option('s_static_currency_names', $currencyNameRelAry);
		}
		
		//save spcified stocks codes & names
		$saved_specified_stocks = get_option('s_specified_stock_codes');
		$saved_specified_stocks_names = get_option('s_specified_stock_names');
		$saved_specified_currency_codes = get_option('s_specified_currency_codes');
		$saved_specified_currency_names = get_option('s_specified_currency_names');
		//remove null value
		if ($saved_specified_stocks){
			$stockNameRelAry = array();
			foreach($saved_specified_stocks as $key => $stock){
				if (!$stock){
					unset($saved_specified_stocks[$key]);
					unset($saved_specified_stocks_names[$key]); //remove same index from names array
				}
				$stockNameRelAry[strtoupper($stock)] = $saved_specified_stocks_names[$key];
			}
			update_option('s_specified_stock_codes', $saved_specified_stocks);
			update_option('s_specified_stock_names', $stockNameRelAry);
		}
		if ($saved_specified_currency_codes){
			$currencyNameRelAry = array();
			foreach($saved_specified_currency_codes as $key => $currency){
				if (!$currency){
					unset($saved_specified_currency_codes[$key]);
					unset($saved_specified_currency_names[$key]); //remove same index from names array
				}
				$currencyNameRelAry[strtoupper($currency)] = $saved_specified_currency_names[$key];
			}
			update_option('s_specified_currency_codes', $saved_specified_currency_codes);
			update_option('s_specified_currency_names', $currencyNameRelAry);
		}
    }

	function s_ticker_shortcode($attrib, $calledBy){
	
		$s_ticker_license_key = trim(get_option('s_ticker_license_key'));
		$s_ticker_license_status = trim(get_option('s_ticker_license_key_status'));
		if (!$s_ticker_license_key || $s_ticker_license_status != 'valid'){
			$s_ticker_license_status = 'invalid';
			delete_option( 's_ticker_license_key_status' );
			
			return '';
		}
		
		$html = '';
		$attrib_a = shortcode_atts(array('codes' => ''), $attrib);
		$specified_codes_a = explode(',', $attrib_a['codes']);
		$specified_codes_a_symbol_as_key = array();
		foreach($specified_codes_a as $key => $code ){
			if( trim($code) == "" ){
				unset($specified_codes_a[$key]);
			}
			$specified_codes_a_symbol_as_key[$code] = $code;
		}
		if( $attrib && is_array($attrib_a) && count($attrib_a) > 0 && count($specified_codes_a) > 0 ){
			//specified
			$saved_stocks = get_option('s_specified_stock_codes');
			$saved_stock_names_rel = get_option('s_specified_stock_names');
			$saved_currency_codes = get_option('s_specified_currency_codes');
			$saved_currency_names_rel = get_option('s_specified_currency_names');
			
			//merge two array
			if ($saved_stock_names_rel && $saved_currency_names_rel){
				$mergedNames = array_merge($saved_stock_names_rel, $saved_currency_names_rel);
			}else{
				$mergedNames = $saved_stock_names_rel ? $saved_stock_names_rel : $saved_currency_names_rel;
			}
			$specified_codes_str = '';
			$specified_codes_str = strtoupper(implode(',', $specified_codes_a_symbol_as_key));
			$specified_res = get_transient('s_specified');
			if ($specified_res === false){
				$specified_res = $this->get_live_data($saved_stocks, $saved_currency_codes, 's_specified', 's_specified_week_long_data');
			}
			$html = $this->s_output_ticker_html( $specified_res, $mergedNames, $saved_currency_codes, 'ADVANCED', $specified_codes_str );
		}else{
			$saved_stocks = get_option('s_ticker_stock_codes');
			$saved_stock_names_rel = get_option('s_ticker_stock_names');
			$saved_currency_codes = get_option('s_ticker_currency_codes');
			$saved_currency_names_rel = get_option('s_ticker_currency_names');
			
			//merge two array
			if ($saved_stock_names_rel && $saved_currency_names_rel){
				$mergedNames = array_merge($saved_stock_names_rel, $saved_currency_names_rel);
			}else{
				$mergedNames = $saved_stock_names_rel ? $saved_stock_names_rel : $saved_currency_names_rel;
			}
			
			$ticker_res = get_transient('s_ticker');
			if ($ticker_res === false){
				$ticker_res = $this->get_live_data($saved_stocks, $saved_currency_codes);
			}
			//exit;
			$html = $this->s_output_ticker_html( $ticker_res, $mergedNames, $saved_currency_codes, $calledBy, '' );
		}

		return $html;
	}
	
	function s_output_ticker_html($res, $merged_names, $currency_codes, $calledBy, $specified_codes_str){
	
		$output = '';
		$className = 'stocks';
		$displayChart = get_option('s_ticker_display_chart', 'off');
		$chartType = get_option('s_ticker_display_chart_type', 'l');
		$chartClass = 'stocks-chart';
		$li_pointer = 'style="cursor:pointer;"';
		
		if( is_array($res) ){
			$idStr = '';
			if( $calledBy == 'INTERFACE' ){
				$idStr = 'stocks-by-interface';
				$chartClass = 'stocks-intf-chart';
			}else if( $calledBy == 'ADVANCED' ){
				$idStr = 'stocks-by-advanced';
				$chartClass = 'stocks-adv-chart';
			}else{
				$calledBy = 'SHORTCODE';
				$idStr = 'stocks-by-shortcode';
			}
			
			if ($displayChart == 'off'){
				$li_pointer = '';
			}
			
			$hidden_symbols = array();
			$item_index = 0;
			$output .= '<div class="ticker-stocks-container">';
			$output .= '<ul id="'.$idStr.'"  class="'.$className.'">';
			
			//convert all currency codes to upper
			if( $currency_codes && is_array($currency_codes) && count($currency_codes) > 0 ){
				foreach( $currency_codes as $key => $val ){
					$currency_codes[$key] = strtoupper($val);
				}
			}

			foreach($res as $key => $r) {
				if( $calledBy == 'ADVANCED' && strlen($specified_codes_str) > 1 && strpos($specified_codes_str, trim($r->symbol)) === false ){
					continue;
				}
				$li_tag_id = 'li_'.$calledBy.'_'.$item_index;
				if (isset($merged_names[$r->symbol]) && strlen($merged_names[$r->symbol]) > 0){
					$output .= '<li id="'.$li_tag_id.'" '.$li_pointer.'><strong>' . $merged_names[$r->symbol] . '</strong>';
				}else{
					$output .= '<li id="'.$li_tag_id.'" '.$li_pointer.'><strong>' . $r->symbol . '</strong>';
				}
				$symbol_upper = strtoupper( $r->symbol );
				if( $currency_codes && is_array($currency_codes) && count($currency_codes) > 0 && in_array($symbol_upper, $currency_codes) ){
					$output .= '<span class="currency">';
					$output .= $r->ask . '</span></li>';
				}else{
					if ( preg_match("/\-/",strval($r->change))  ){
						$output .= '<span class="stockdown">';
					}else{
						$output .= '<span class="stockup">';
					}
					$output .= $r->ask . '</span>';
				
					if ( preg_match("/\-/",strval($r->change))  ){
						$output .= '<span class="stockdown">';
					}else{
						$output .= '<span class="stockup">';
					}
					$output .=  $r->change . '</span></li>';  
				}
				
				$hidden_symbols[$li_tag_id] = $r->symbol;
				$item_index++;
			}
			// only show the link to helpforwordpress.com if it's not disabled
			if (get_option('s_ticker_field_link') !='disable'){
				$li_tag_id = 'li_'.$calledBy.'_'.$item_index;
				$output .= '<li id="'.$li_tag_id.'">WP Stock Ticker</li>';
				$hidden_symbols[$li_tag_id] = 'NONE';
				$item_index++;
			}
			
			$output .= '</ul>';
			
			if($displayChart == 'on'){
				$output .= '<div class="'.$chartClass.'">To show stock chart</div>';
			}
			
			//hidden values to control ticker. rel is not a supported attribute in w3c 
			$speed = get_option('s_ticker_display_speed', 1);
			$output .= '<input type="hidden" name="s_ticker_'.$calledBy.'_speed" id="s_ticker_'.$calledBy.'_speed_id" value="'.$speed.'" />';
			$output .= '<input type="hidden" name="s_ticker_'.$calledBy.'_showchart" id="s_ticker_'.$calledBy.'_showchart_id" value="'.$displayChart.'" />';
			if($displayChart == 'on'){
				$chartPostion = get_option('s_ticker_display_chart_position', 'top');
				$chartInterface = get_option('s_ticker_display_chart_interface', 'ichart');
				$output .= '<input type="hidden" name="s_ticker_'.$calledBy.'_chart_type" id="s_ticker_'.$calledBy.'_chart_type_id" value="'.$chartType.'" />';
				$output .= '<input type="hidden" name="s_ticker_'.$calledBy.'_chart_position" id="s_ticker_'.$calledBy.'_chart_position_id" value="'.$chartPostion.'" />';
				$output .= '<input type="hidden" name="s_ticker_'.$calledBy.'_chart_interface" id="s_ticker_'.$calledBy.'_chart_interface_id" value="'.$chartInterface.'" />';
				if(count($hidden_symbols) > 0){
					foreach($hidden_symbols as $key => $symbol){
						$output .= '<input type="hidden" name="s_ticker_'.$key.'_symbol" id="s_ticker_'.$key.'_symbol_id" value="'.$symbol.'" />';
					}
				}
			}
			$output .= '</div>';
		}else{
			$output .= $res;
		}
		return $output;
	}
	
	function s_ticker_widget($attrib, $calledBy){
	
		$s_ticker_license_key = trim(get_option('s_ticker_license_key'));
		$s_ticker_license_status = trim(get_option('s_ticker_license_key_status'));
		if (!$s_ticker_license_key || $s_ticker_license_status != 'valid'){
			$s_ticker_license_status = 'invalid';
			delete_option( 's_ticker_license_key_status' );
			
			return '';
		}
		$saved_stocks = get_option('s_ticker_stock_codes');
		$saved_stock_names_rel = get_option('s_ticker_stock_names');
		$saved_currency_codes = get_option('s_ticker_currency_codes');
		$saved_currency_names_rel = get_option('s_ticker_currency_names');
	
		//merge two array
		if ($saved_stock_names_rel && $saved_currency_names_rel){
			$mergedNames = array_merge($saved_stock_names_rel, $saved_currency_names_rel);
		}else{
			$mergedNames = $saved_stock_names_rel ? $saved_stock_names_rel : $saved_currency_names_rel;
		}
		
		//convert all currency codes to upper
		if( $saved_currency_codes && is_array($saved_currency_codes) && count($saved_currency_codes) > 0 ){
			foreach( $saved_currency_codes as $key => $val ){
				$saved_currency_codes[$key] = strtoupper($val);
			}
		}
		
		$res = get_transient('s_ticker');
		if ($res === false){
			$res = $this->get_live_data($saved_stocks, $saved_currency_codes);
		}
		
	
		$output = '';
		$li_pointer = 'style="cursor:pointer;"';
	
		if(is_array($res)) {
		
			$widgetAttsArray = explode(':', $calledBy);
			$idStr = 'stocks-by-widget-'.$widgetAttsArray[1];
			$className = 'stocksTickerWidget';
			$displayChart = 'off';
			
			$output .= '<div class="ticker-stocks-container">';
			$output .= '<ul id="'.$idStr.'"  class="'.$className.'">';
			foreach($res as $key => $r) {
				if (isset($mergedNames[$r->symbol]) && strlen($mergedNames[$r->symbol]) > 0){
					$output .= '<li><strong>' . $mergedNames[$r->symbol] . '</strong>';
				}else{
					$output .= '<li><strong>' . $r->symbol . '</strong>';
				}
			
				$symbol_upper = strtoupper( $r->symbol );
				if( $saved_currency_codes && is_array($saved_currency_codes) && count($saved_currency_codes) > 0 && in_array($symbol_upper, $saved_currency_codes) ){
					$output .= '<span class="currency">';
					$output .= $r->ask . '</span></li>';
				}else{
					if ( preg_match("/\+/",strval($r->change))  ){
						$output .= '<span class="stockup">';
					}else{
						$output .= '<span class="stockdown">';
					}
					$output .= $r->ask . '</span>';
					
					if ( preg_match("/\+/",strval($r->change))  ){
						$output .= '<span class="stockup">';
					}else{
						$output .= '<span class="stockdown">';
					}
					
					$output .=  $r->change . '</span></li>';  
				}
			}
			// only show the link to helpforwordpress.com if it's not disabled
			if (get_option('s_ticker_field_link') !='disable'){
				$output .= '<li>WP Stock Ticker</li>';
			}
			
			$output .= '</ul>';

			//hidden values to control ticker. rel is not a supported attribute in w3c 
			$speed = get_option('s_ticker_display_speed', 1);
			$output .= '<input type="hidden" name="s_ticker_'.$idStr.'_speed" id="s_ticker_'.$idStr.'_speed_id" value="'.$speed.'" class="stocksTickerWidgetSpeed" />';
			
			$output .= '</div>';
			
			//output scroller activate codes here.
			return trim($output);
		}else {
			$output .= $res;
			return $output;
		}
	}
	
	function s_static_shortcode($attrib, $calledBy){
		$s_ticker_license_key = trim(get_option('s_ticker_license_key'));
		$s_ticker_license_status = trim(get_option('s_ticker_license_key_status'));
		if (!$s_ticker_license_key || $s_ticker_license_status != 'valid'){
			$s_ticker_license_status = 'invalid';
			delete_option( 's_ticker_license_key_status' );
			
			return '';
		}
		
		$html = '';
		$attrib_a = shortcode_atts(array('codes' => ''), $attrib);
		$specified_codes_a = explode(',', $attrib_a['codes']);
		$specified_codes_a_symbol_as_key = array();
		foreach($specified_codes_a as $key => $code ){
			if( trim($code) == "" ){
				unset($specified_codes_a[$key]);
			}
			$specified_codes_a_symbol_as_key[$code] = $code;
		}
		if( $attrib && is_array($attrib_a) && count($attrib_a) > 0 && count($specified_codes_a) > 0 ){
			//specified
			$saved_stocks = get_option('s_specified_stock_codes');
			$saved_stock_names_rel = get_option('s_specified_stock_names');
			$saved_currency_codes = get_option('s_specified_currency_codes');
			$saved_currency_names_rel = get_option('s_specified_currency_names');
			
			//merge two array
			if ($saved_stock_names_rel && $saved_currency_names_rel){
				$mergedNames = array_merge($saved_stock_names_rel, $saved_currency_names_rel);
			}else{
				$mergedNames = $saved_stock_names_rel ? $saved_stock_names_rel : $saved_currency_names_rel;
			}
			
			$specified_res = get_transient('s_specified');
			if ($specified_res === false){
				$specified_res = $this->get_live_data($saved_stocks, $saved_currency_codes, 's_specified', 's_specified_week_long_data');
			}
			$specified_codes_str = '';
			$specified_codes_str = strtoupper(implode(',', $specified_codes_a_symbol_as_key));
			$html = $this->s_output_static_html( $specified_res, $mergedNames, $saved_currency_codes, 'ADVANCED', $specified_codes_str );
		}else{
			$saved_stocks = get_option('s_static_stock_codes');
			$saved_stock_names_rel = get_option('s_static_stock_names');
			$saved_currency_codes = get_option('s_static_currency_codes');
			$saved_currency_names_rel = get_option('s_static_currency_names');
		
			//merge two array
			if ($saved_stock_names_rel && $saved_currency_names_rel){
				$mergedNames = array_merge($saved_stock_names_rel, $saved_currency_names_rel);
			}else{
				$mergedNames = $saved_stock_names_rel ? $saved_stock_names_rel : $saved_currency_names_rel;
			}
			
			$static_res = get_transient('s_static');
			if ($static_res === false){
				$static_res = $this->get_live_data($saved_stocks, $saved_currency_codes, 's_static', 's_static_week_long_data');
			}
			$html = $this->s_output_static_html( $static_res,  $mergedNames, $saved_currency_codes, $calledBy, '' );
		}
		
		return $html;		
	}

	function s_output_static_html( $res, $merged_names, $currency_codes, $calledBy, $specified_codes_str ){
		$output = '';
		$className = 'static-stocks';
		$displayChart = get_option('s_static_display_chart', 'off');
		$chartType = get_option('s_static_display_chart_type', 'l');
		$chartClass = 'static-stocks-chart-by-shortcode';
		$idStr = 'static-stocks-by-shortcode';
		$static_container_width = intval(get_option( 's_static_container_width', '620' ));
		$width_style = ' style="width:'.$static_container_width.'px;"';
		$li_pointer = 'style="cursor:pointer;"';
		
		//convert all currency codes to upper
		if( $currency_codes && is_array($currency_codes) && count($currency_codes) > 0 ){
			foreach( $currency_codes as $key => $val ){
				$currency_codes[$key] = strtoupper($val);
			}
		}
		
		if(is_array($res)) {
		
			if( strpos($calledBy, 'WIDGET') !== false ){
				$widgetAttsArray = explode(':', $calledBy);
				$idStr = 'static-stocks-by-widget-'.$widgetAttsArray[1];
				$displayChart = 'off';
				$width_style = '';
			}else if( $calledBy == 'INTERFACE' ){
				$idStr = 'static-stocks-by-interface';
				$chartClass = 'static-stocks-chart-by-interface';
			}else if( $calledBy == 'ADVANCED' ){
				$idStr = 'static-stocks-by-advanced';
				$chartClass = 'static-stocks-chart-by-advanced';
			}else{
				$calledBy = 'SHORTCODE';
			}
			if ($displayChart == 'off'){
				$li_pointer = '';
			}
			
			$output .= "\n";
			$output .= '<div class="static-stocks-container"'.$width_style.'>
							<ul id="'.$idStr.'"  class="'.$className.'" >'."\n";

			$hidden_symbols = array();
			$item_index = 0;
			$first_symobl = '';
			foreach($res as $key => $r) {
				if( $calledBy == 'ADVANCED' && strlen($specified_codes_str) > 1 && strpos($specified_codes_str, trim($r->symbol)) === false ){
					continue;
				}
				$li_tag_id = 'static_li_'.$calledBy.'_'.$item_index;
				if (isset($merged_names[$r->symbol]) && strlen($merged_names[$r->symbol]) > 0){
					$output .= '<li id="'.$li_tag_id.'" '.$li_pointer.'><strong>' . $merged_names[$r->symbol] . '</strong>';
				}else{
					$output .= '<li id="'.$li_tag_id.'" '.$li_pointer.'><strong>' . $r->symbol . '</strong>';
				}
			
				$symbol_upper = strtoupper( $r->symbol );
				if( $currency_codes && is_array($currency_codes) && count($currency_codes) > 0 && in_array($symbol_upper, $currency_codes) ){
					$output .= '<span class="currency">';
					$output .= $r->ask . '</span></li>';
				}else{
					if ( preg_match("/\+/",strval($r->change))  ){
						$output .= '<span class="stockup">';
					}else{
						$output .= '<span class="stockdown">';
					}
					$output .= $r->ask . '</span>';
				
					if ( preg_match("/\+/",strval($r->change))  ){
						$output .= '<span class="stockup">';
					}else{
						$output .= '<span class="stockdown">';
					}
				
					$output .=  $r->change . '</span></li>'."\n";  
				}
				if ( $first_symobl == '' ){
					$first_symobl = $r->symbol;
				}
				$hidden_symbols[$li_tag_id] = $r->symbol;
				$item_index++;
			}
			// only show the link to helpforwordpress.com if it's not disabled
			if (get_option('s_ticker_field_link') !='disable'){
				$li_tag_id = 'static_li_'.$calledBy.'_'.$item_index;
				$output .= '<li id="'.$li_tag_id.'">WP Stock Ticker</li>';
				$hidden_symbols[$li_tag_id] = 'NONE';
				$item_index++;
			}
			
			$output .= '</ul>';
			
			$output .= '<input type="hidden" name="s_static_'.$calledBy.'_showchart" id="s_static_'.$calledBy.'_showchart_id" value="'.$displayChart.'" />';
			if ($displayChart == 'on'){
				$chartInterface = get_option('s_static_display_chart_interface', 'ichart');
				
				$output .= '<div class="'.$chartClass.'">';
				if ( $first_symobl ){
					if ( $chartInterface == 'ichart' ){
							$output .= '<img src="http://ichart.yahoo.com/z?s='.$first_symobl.'&amp;q='.$chartType.'&amp;z=s" alt="Chart of '.$first_symobl.'">';
					}else{
							$output .= '<img src="http://chart.finance.yahoo.com/t?s='.$first_symobl.'&amp;q='.$chartType.'&amp;z=s&amp;width=350&amp;height=205" alt="Chart of '.$first_symobl.'">';
					}
				}
				$output .= '</div>'
				;
				$output .= '<input type="hidden" name="s_static_'.$calledBy.'_chart_type" id="s_static_'.$calledBy.'_chart_type_id" value="'.$chartType.'" />';
				$output .= '<input type="hidden" name="s_static_'.$calledBy.'_chart_interface" id="s_static_'.$calledBy.'_chart_interface_id" value="'.$chartInterface.'" />';
				if(count($hidden_symbols) > 0){
					foreach($hidden_symbols as $key => $symbol){
						$output .= '<input type="hidden" name="s_'.$key.'_symbol" id="s_'.$key.'_symbol_id" value="'.$symbol.'" />';
					}
				}
			}
			
			$output .= '	<div style="clear:both;"></div>
						</div>';

			return trim($output);
		}
		
		$output .= $res;
		return $output;
	}
	
	function get_live_data($stock_array, $currency_array, $transient_cache_data_name = 's_ticker', $transient_week_long_data_name = 's_ticker_week_long_data' ) {
		$stock_array_str = $stock_array ? implode('","', $stock_array) : '';
		$currency_array_str = $currency_array ? implode('","', $currency_array) : '';
		
		if ($stock_array_str && $currency_array_str){
			$strMerged = $stock_array_str .'","'. $currency_array_str;
		}else{
			$strMerged = $stock_array_str ? $stock_array_str : $currency_array_str;
		}
		if (!$strMerged ) return array();

		$sql = 'select * from yahoo.finance.quotes where symbol in ("' . $strMerged .'")';
		$url = 'http://query.yahooapis.com/v1/public/yql?q=' . urlencode($sql) . '&format=json&env=store%3A%2F%2Fdatatables.org%2Falltableswithkeys';

    	$objarr = array();

		$dat = wp_remote_get($url);
		if ( is_wp_error($dat) ) {
			//get week long transient data
			$objarr = get_transient( $transient_week_long_data_name );
			if (!$objarr || !is_array($objarr)){
				return "Yahoo finance API is not available right now, please try again soon...";
			}
			
			return $objarr;
		}else{
			$data = json_decode(wp_remote_retrieve_body($dat));
		}

		if ( !is_null($data) && isset($data->error) ){
			if ( $data->error ) return "Yahoo finance API is not available right now, please try again soon...";
		}

		if (!is_null($data->query->results)){
			if(is_array($data->query->results->quote)) { //more than one symbol
				foreach($data->query->results->quote as $q) {
					$obj = new stdClass;
					$obj->symbol = $q->symbol;
					$obj->ask = $q->LastTradePriceOnly;
					$obj->change = str_replace(' - ', ' ', $q->Change_PercentChange);
					
					$objarr[$obj->symbol] = $obj;
				}
			}else{
				$obj = new stdClass;
				$obj->symbol = $data->query->results->quote->symbol;
				$obj->ask = $data->query->results->quote->LastTradePriceOnly;
				$obj->change = str_replace(' - ', ' ', $data->query->results->quote->Change_PercentChange);
				
				$objarr[$obj->symbol] = $obj;
			}
			delete_transient( $transient_week_long_data_name );
			set_transient( $transient_week_long_data_name, $objarr, 7*60*60*24);
			if( (int) get_option('s_ticker_field_cache') > 0 ){
				set_transient( $transient_cache_data_name, $objarr, 60 * (int) get_option('s_ticker_field_cache', 1));
			}
		}else{
			$objarr = get_transient( $transient_week_long_data_name );
			if (!$objarr || !is_array($objarr)){
				return "Yahoo finance API is not available right now, please try again soon...";
			}
		}

	    return $objarr; 
	}
	
	function s_ticker_activate_license() {
		// listen for our activate button to be clicked
		if( isset( $_POST['s_ticker_license_activate'] ) ) {
			global $_wpstp_plugin_name, $_wpstp_home_url;

			// run a quick security check 
			if( ! check_admin_referer( 's_ticker_license_key_nonce', 's_ticker_license_key_nonce' ) ) 	{
				return; // get out if we didn't click the Activate button
			}

			$license = trim( $_POST['s_ticker_license_key'] );
			update_option( 's_ticker_license_key', $license );
			
			// data to send in our API request
			$api_params = array( 
				'edd_action'=> 'activate_license', 
				'license' 	=> $license, 
				'item_name' => urlencode( $_wpstp_plugin_name ) // the name of our product in EDD
			);
			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params, $_wpstp_home_url ), array( 'timeout' => 15, 'sslverify' => false ) );
			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;
	
			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );

			
			if( $license_data && isset($license_data->license) ){
				update_option( 's_ticker_license_key_status', $license_data->license );
			}
		}
	}
	
	function s_ticker_deactivate_license() {
		// listen for our activate button to be clicked
		if( isset( $_POST['s_ticker_license_deactivate'] ) ) {
			global $_wpstp_plugin_name, $_wpstp_home_url;
			
			// run a quick security check 
			if( ! check_admin_referer( 's_ticker_license_key_nonce', 's_ticker_license_key_nonce' ) ) 	
				return; // get out if we didn't click the Activate button
	
			// retrieve the license from the database
			$license = trim( get_option( 's_ticker_license_key' ) );
				
	
			// data to send in our API request
			$api_params = array( 
				'edd_action'=> 'deactivate_license', 
				'license' 	=> $license,
				'url'		=> get_option('home'),
				'item_name' => urlencode( $_wpstp_plugin_name ) // the name of our product in EDD
			);
			
			// Call the custom API.
			$response = wp_remote_get( add_query_arg( $api_params, $_wpstp_home_url ), array( 'timeout' => 15, 'sslverify' => false ) );
	
			// make sure the response came back okay
			if ( is_wp_error( $response ) )
				return false;
	
			// decode the license data
			$license_data = json_decode( wp_remote_retrieve_body( $response ) );
			
			// $license_data->license will be either "deactivated" or "failed"
			if( $license_data && isset($license_data->license) && $license_data->license == 'deactivated' )
				delete_option( 's_ticker_license_key_status' );
		}
	}
}
	
$wp_stock_ticker = new WPStockTickerPro();

// register widget
add_action( 'widgets_init', create_function( '', 'register_widget( "WPStockerWidgetPro" );' ) );
add_action( 'widgets_init', create_function( '', 'register_widget( "WPStockerStaticWidgetPro" );' ) );
