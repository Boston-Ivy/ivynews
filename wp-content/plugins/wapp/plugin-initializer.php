<?php
/*
Plugin Name: Wapp
Plugin URI: http://wordpress.org/plugins/json-api/
Description: Wapp plugin
Version: 1.0.1
Author: Pumpún Dixital
Author URI: http://pumpun.com/
*/

define(JSON_API_VERSION, '1.1.1');
require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require_once dirname(__FILE__)."/titan-framework/titan-framework-embedder.php";
require_once dirname(__FILE__)."/cmb2/init.php";


function WappInit() {

    if (class_exists(JSON_API)) {
        deactivate_plugins(plugin_basename( __FILE__ ));
        add_action('admin_notices', 'WappWarning');
        return;
    }

    require_once dirname(__FILE__).'/json-api.php';
    add_filter('rewrite_rules_array', 'WappApiRewrites');
    json_api_init();

}

function WappWarning() {
    echo "<div id=\"json-api-warning\" class=\"error fade\"><p>WARNING: The plugin has conflicts with other plugins installed plugins. Please check if you already have activated JSON API, JSON REST API or another rest api plugin and deactivate it</p></div>";
}

function WappApiActivation() {
    update_option('json-api_base', 'api');

    global $wp_rewrite;
    add_filter('rewrite_rules_array', 'WappApiRewrites');
    $wp_rewrite->flush_rules();

    update_option('json_api_controllers', 'core,posts,respond,widgets,user,menus,wapp');
}


function WappApiDeactivation() {
    // Remove the rewrite rule on deactivation
    global $wp_rewrite;
    $wp_rewrite->flush_rules();
    delete_option('json_api_controllers');
}

function WappApiRewrites($wp_rules) {

    $base = get_option('json_api_base', 'api');

    if (empty($base)) {
        return $wp_rules;
    }
    $json_api_rules = array(
        "$base\$" => 'index.php?json=info',
        "$base/(.+)\$" => 'index.php?json=$matches[1]'
    );

    return array_merge($json_api_rules, $wp_rules);
}


// Hooking up our function to theme setup
function generateOptions() {
    include_once dirname(__FILE__) . '/addons/class-option-custom-table.php';
    include_once dirname(__FILE__) . '/addons/class-option-select-posts-custom.php';
    include_once dirname(__FILE__) . '/addons/class-option-map-coordinates.php';
    global $titan;
    $titan = TitanFramework::getInstance( 'wapp' );

    $panel = $titan->createAdminPanel( array(
        'name' => 'wapp···',
        'title' => '',
        'desc' => 'My wapp options'
    ));

	/* WAPP CONTENT */
    $wappTab = $panel->createTab(array(
        'name' => 'Wapp Content',
    ));

	/* Intro */
    $wappTab->createOption(array(
        'name' => __('Display Intro', 'wapp'),
        'id' => 'wapp_config_display_intro',
        'type' => 'select',
        'desc' => __('Select yes if you want to display a slider intro view', 'wapp'),
		'options' => array(
			'yes' => 'Yes',
			'no' => 'No',
		),
        'default' => 'yes'
    ));

	/* Slider */
    $wappTab->createOption(array(
        'name' => __('Display Slider', 'wapp'),
        'id' => 'wapp_config_display_slider',
        'type' => 'select',
        'desc' => __('Select yes if you want to display a slider block in home view', 'wapp'),
		'options' => array(
			'yes' => 'Yes',
			'no' => 'No',
		),
        'default' => 'no'
    ));
		
	/* 2 columns */
    $wappTab->createOption(array(
        'name' => __('Display 2 columns block', 'wapp'),
        'id' => 'wapp_config_display_columns',
        'type' => 'select',
        'desc' => __('Select yes if you want to display a 2 columns block in home view', 'wapp'),
		'options' => array(
			'yes' => 'Yes',
			'no' => 'No',
		),
        'default' => 'no'
    ));

    $wappTab->createOption(array(
        'name' => __('2 columns block posts', 'wapp'),
        'id' => 'wapp_config_column_posts',
        'type' => 'select',
        'desc' => __('Select number of posts you want to display in 2 column block', 'wapp'),
		'options' => array(
			'2' => '2',
			'4' => '4',
			'6' => '6',
			'8' => '8',
			'10' => '10',
			'12' => '12',
			'14' => '14',
			'16' => '16',
			'18' => '18',
			'20' => '20',
		),
        'default' => 'yes'
    ));

	/* Full width */
    $wappTab->createOption(array(
        'name' => __('Display Full width block', 'wapp'),
        'id' => 'wapp_config_display_full',
        'type' => 'select',
        'desc' => __('Select yes if you want to display a full width block in home view', 'wapp'),
		'options' => array(
			'yes' => 'Yes',
			'no' => 'No',
		),
        'default' => 'no'
    ));

    $wappTab->createOption(array(
        'name' => __('Full width block posts', 'wapp'),
        'id' => 'wapp_config_full_posts',
        'type' => 'select',
        'desc' => __('Select number of posts you want to display in full width block', 'wapp'),
		'options' => array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
			'7' => '7',
			'8' => '8',
			'9' => '9',
			'10' => '10',
		),
        'default' => 'yes'
    ));

	/* List block */
    $wappTab->createOption(array(
        'name' => __('Display list block', 'wapp'),
        'id' => 'wapp_config_display_list',
        'type' => 'select',
        'desc' => __('Select yes if you want to display a list of posts block in home view', 'wapp'),
		'options' => array(
			'yes' => 'Yes',
			'no' => 'No',
		),
        'default' => 'no'
    ));

    $wappTab->createOption(array(
        'name' => __('List block posts', 'wapp'),
        'id' => 'wapp_config_list_posts',
        'type' => 'select',
        'desc' => __('Select number of posts you want to display in list of posts block', 'wapp'),
		'options' => array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
			'6' => '6',
			'7' => '7',
			'8' => '8',
			'9' => '9',
			'10' => '10',
		),
        'default' => 'yes'
    ));

/*  
    $infoTab->createOption(array(
        'name' => 'Wapp Info',
        'id' => 'info',
        'type' => 'upload',
        'desc' => 'Upload your image',
        'default' => ''
    ));
*/

	/* STYLING */
    $colorsTab = $panel->createTab(array(
        'name' => __('Styling', 'wapp'),
    ));

	/* Color selector */
    $colorsTab->createOption(array(
        'name' => __('Theme', 'wapp'),
        'id' => 'theme',
        'type' => 'select',
        'desc' => __('Select a pre defined theme', 'wapp'),
		'options' => array(
			'default' => __('Boxed', 'wapp'),
			'light' => __('Light', 'wapp'),
			'dark' => __('Dark', 'wapp'),
		),
        'default' => 'default'
    ));

	/* MENU */
    $menuTab = $panel->createTab(array(
        'name' => __('Menu', 'wapp')
    ));

	/* Menu selector*/
    $menus = get_terms('nav_menu');
    $options = array(0 => '---');
    foreach ($menus as $menu) {
        $options[$menu->term_id] = $menu->name;
    }

    $menuTab->createOption(array(
        'name' => __('Menu', 'wapp'),
        'id' => 'menu',
        'type' => 'select',
        'desc' => __('Selec a menu previously created in the Appearance section', 'wapp'),
        'options' => $options,
        'default' => 0
    ));

	
	/* Developer */
    $advancedTab = $panel->createTab(array(
        'name' => __('Developer Options', 'wapp'),
    ));

    $desc = 'Base URL for your JSON API';

    $advancedTab->createOption(array(
        'name' => 'API End Point',
        'id' => 'base',
        'type' => 'customTable',
        'default' => 'api/',
        'desc' => $desc
    ));

	/*
    $colorsTab->createOption(array(
        'name' => 'My Color Picker Option',
        'id' => 'background_color',
        'type' => 'color',
        'desc' => 'Pick a color',
        'default' => '#dd3333'
    ));
*/

	/* GEOLOCATION */
    $geoTab = $panel->createTab(array(
        'name' => __('Geolocation Options', 'wapp'),
    ));

    $geoTab->createOption(array(
        'name' => 'Enable geolocation',
        'id' => 'geolocation_enabled',
        'type' => 'checkbox',
        'desc' => 'Enable a map view in Wapp',
        'default' => false
    ));
	
	$geoTab->createOption(array(
		'name'	=> 	'Latitude',
		'id'	=>	'lat',
		'type'	=>	'text',
		'desc'	=>	'Latitude coordinate (e.g. 42.238270)'
	));
		
	$geoTab->createOption(array(
		'name'	=> 	'Longitude',
		'id'	=>	'lon',
		'type'	=>	'text',
		'desc'	=>	'Longitude coordinate (e.g. -8.722209)'
	));

	$geoTab->createOption(array(
		'name'	=> 	__('Geocode coordinates', 'wapp'),
		'id'	=>	'map_coordinates',
		'type'	=>	'map-coordinates',
		'desc'	=>	'Write the address you want to geolocalize. Latitude and Longitude coordinates will be automatically filled'
	));	

    if (!$_GET['tab'] or $_GET['tab'] != 'advanced-options' ) {
        $panel->createOption(array(
            'type' => 'save',
            'use_reset' => false,
        ));
    }
}


// Our custom post type function
function create_posttype() {

    register_post_type('wapp',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Wapp Content' ),
                'singular_name' => __( 'Wapp Content' )
            ),
            'public' => true,
            'has_archive' => false,
            'supports' => array('title','editor','thumbnail'),
            'capability_type' => 'page'
        )
    );
}

function generate_metaboxes( array $meta_boxes ) {


    $meta_boxes['type'] = array(
        'id'            => 'type',
        'title'         => __( 'Wapp Layout', 'wapp' ),
        'object_types'  => array( 'wapp', ), // Post type
        'context'       => 'side',
        'priority'      => 'core',
        'show_names'    => true, // Show field names on the left
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // true to keep the metabox closed by default
        'fields'        => array(

            array(

                'desc'    => __( 'Layout post type wapp description', 'wapp' ),
                'id'      => 'wapp_type',
                'type'    => 'radio',
                'options' => array(
                    'option1' => __( 'Two columns block', 'wapp' ),
                    'option2' => __( 'Full width block', 'wapp' ),
                    'option3' => __( 'List block', 'wapp' ),
                    'slider' => __( 'Slider', 'wapp' ),
					'intro' => __( 'Intro', 'wapp' )
                ),
            ),
        ),
    );

    /**
     * Repeatable Field Groups
     */
     
     $pages = array();
     $args = array( 
     	'post_type' => 'page',
     );
     	
     $query = new WP_Query( $args );		
     
     if($query->have_posts()) : 
     	while($query->have_posts()) : 
     		$query->the_post();
     		
     		$pages[get_the_id()] = get_the_title();
     		
     	endwhile;
     endif;
     wp_reset_query();
     
    $meta_boxes['slider'] = array(
        'id'           => 'wapp_slider_post_box',
        'title'        => __( 'Slider', 'wapp' ),
        'object_types' => array( 'wapp'),
        'fields'       => array(
            array(
                'id'          => 'wapp_slider',
                'type'        => 'group',
                'description' => '',
                'options'     => array(
                    'group_title'   => __( 'Image {#}', 'wapp' ), // {#} gets replaced by row number
                    'add_button'    => __( 'Add Another Entry', 'wapp' ),
                    'remove_button' => __( 'Remove Entry', 'wapp' ),
                    'sortable'      => true, // beta
                ),
                // Fields array works the same, except id's only need to be unique for this group. Prefix is not needed.
                'fields'      => array(
                    array(
                        'name' => 'Entry Image',
                        'id'   => 'image',
                        'type' => 'file',
                    ),
                    array(
                        'name' => 'Image Caption',
                        'id'   => 'image_caption',
                        'type' => 'text',
                    ),
					array(
						'name' => 'Image Description',
						'id'   => 'image_description',
						'type' => 'text'
					),
					array(
					    'name' => __('Add permalink to image', 'wapp'),
					    'desc' => __('By checking "add permalink", you will be able to attach a page permalink to the slide by using the page selector bellow', 'wapp'),
					    'id' => 'add_permalink_to_slide',
					    'type' => 'checkbox'
					),
					array(
					    'name'             => __('Select permalink page', 'wapp'),
					    'desc'             => __('The selected page will be the permalink for the slide', 'wapp'),
					    'id'               => 'slide_permalink',
					    'type'             => 'select',
					    'show_option_none' => true,
					    'options'          => $pages,
					    'default' => 'none',
					),					
                ),
            ),
        ),
    );

    return $meta_boxes;
}


/*
	WAPP POST-TYPE COLUMNS
	Adding columns to wapp post type in wordpress andmin post list view
*/
/* Add new column */
function wapp_columns_head($defaults) {

    $defaults['featured_image'] = __('Image', 'wapp');
    $defaults['type'] = __('Wapp Type', 'wapp');
    return $defaults;
}
 
/* Add new content */
function wapp_columns_content($column_name, $post_ID) {
    if ($column_name == 'featured_image') {
        if (has_post_thumbnail($post_ID)) {
            echo get_the_post_thumbnail($post_ID, 'wapp_thumbnail');
        }
    }else if($column_name == 'type'){
	    $field = get_post_meta( $post_ID, 'wapp_type', true );
	    
		if($field == 'option3'){
			$name = __('List block');
		}else if($field == 'option2'){
			$name = __('Full width block');			
		}else if($field == 'option1'){
			$name = __('Two columns block');						
		}else if($field == 'slider'){
			$name = __('Slider');						
		}else if($field == 'intro'){
			$name = __('Intro');						
		}
	    echo $name;
    }
}
add_filter('manage_wapp_posts_columns', 'wapp_columns_head');
add_action('manage_wapp_posts_custom_column', 'wapp_columns_content', 10, 2);


/* Preprocessing needed for the wapp_slider custom field */

add_filter('json_api_encode', 'my_encode_slider');

function my_encode_slider($response) {
	
  if (isset($response['posts'])) {
    foreach ($response['posts'] as $post) {
		parse_and_encode_slider($post);
    }
  } else if (isset($response['post'])) {	  
    parse_and_encode_slider($response['post']);
  }
  return $response;
}


function parse_and_encode_slider(&$post) {
	if ($post->type == 'wapp') {
        if ($post->custom_fields->wapp_slider) {
          $slides = unserialize($post->custom_fields->wapp_slider[0]);
          $post->custom_fields->wapp_slider = $slides;
/*          foreach ($slides as $value) {
              $img = wp_get_attachment_image_src($value['image_id'], 'medium');
              $post->custom_fields->wapp_slider[] = array(
                  'caption' => $value['image_caption'],
                  'image' => $img
              );
          }*/
        }
	}
}



add_action('init', 'WappInit');
add_action('init', 'create_posttype');
add_action('tf_create_options', 'generateOptions');
add_filter('cmb2_meta_boxes', 'generate_metaboxes');

register_activation_hook(__FILE__, 'WappApiActivation');
register_deactivation_hook(__FILE__, 'WappApiDeactivation');


/* 
	Generate image sizes for wapp plugin 
*/
add_action( 'after_setup_theme', 'wapp_setup_image_sizes' );

function wapp_setup_image_sizes() {

	add_image_size( 'wapp_horizontal_medium', 250, 185, true ); // 2 column block
	add_image_size( 'wapp_horizontal_large', 500, 400, true ); // Full width block
	add_image_size( 'wapp_horizontal_full', 500, 0, false ); // single post thumbnail
	add_image_size( 'wapp_horizontal_small', 320, 253, true );
	add_image_size( 'wapp_horizontal_slider', 500, 493, true ); // slider block
	add_image_size( 'wapp_vertical_full', 0, 700, false ); // intro block
	add_image_size( 'wapp_thumbnail', 36, 36, true ); // wordpress admin list of wapp post type
}

/*
	Load admin scripts
*/
function wapp_load_admin_scripts() {
	
	wp_register_style( 'wapp_admin_css', plugin_dir_url( __FILE__ ) . 'css/admin.css', false, '1.0.0' );
	wp_enqueue_style( 'wapp_admin_css' );
	
	wp_enqueue_script( 'wapp_admin_js', plugin_dir_url( __FILE__ ) . 'js/admin.js' );
}

add_action( 'admin_enqueue_scripts', 'wapp_load_admin_scripts' );
?>