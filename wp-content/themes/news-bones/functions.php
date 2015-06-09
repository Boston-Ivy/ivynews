<?php
/*
Author: Eddie Machado
URL: http://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, ect.
*/

// LOAD BONES CORE (if you remove this, the theme will break)
require_once( 'library/bones.php' );

//require_once( 'library/ig/ticker.php' );

//wp_enqueue_script( 'login_with_php', get_template_directory_uri() . '/library/ig/js/login_with_php.js' , array( 'jquery' ), '1.0', true);

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
require_once( 'library/admin.php' );

/*********************
LAUNCH BONES
Let's get everything up and running.
*********************/

function bones_ahoy() {

  //Allow editor style.
  add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

  // let's get language support going, if you need it
  load_theme_textdomain( 'bonestheme', get_template_directory() . '/library/translation' );

  // USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
  require_once( 'library/custom-post-type.php' );

  // launching operation cleanup
  add_action( 'init', 'bones_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'bones_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'bones_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  bones_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'bones_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'bones_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'bones_excerpt_more' );

} /* end bones ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'bones_ahoy' );

function bones_customise_register( $wp_customize ) {

/*******************************************
Color scheme
********************************************/
 
// add the section to contain the settings
$wp_customize->add_section( 'textcolors' , array(
    'title' =>  'Color Scheme',
) );

// main color ( site title, h1, h2, h4. h6, widget headings, nav links, footer headings )
$txtcolors[] = array(
    'slug'=>'color_scheme_1', 
    'default' => '#4DAE36',
    'label' => 'Main (menu, headlines, footer)'
);
 


// add the settings and controls for each color
foreach( $txtcolors as $txtcolor ) {
 
    // SETTINGS
    $wp_customize->add_setting(
        $txtcolor['slug'], array(
            'default' => $txtcolor['default'],
            'type' => 'option', 
            'capability' => 
            'edit_theme_options'
        )
    );
    // CONTROLS
    $wp_customize->add_control(
        new WP_Customize_Color_Control(
            $wp_customize,
            $txtcolor['slug'], 
            array('label' => $txtcolor['label'], 
            'section' => 'textcolors',
            'settings' => $txtcolor['slug'])
        )
    );
}

$wp_customize->add_setting( 'bones_logo' ); // Add setting for logo uploader
// Add control for logo uploader (actual uploader)
    $wp_customize->add_control( new WP_Customize_Image_Control( $wp_customize, 'bones_logo', array(
        'label'    => __( 'Upload Logo (replaces text)', 'bones' ),
        'section'  => 'title_tagline',
        'settings' => 'bones_logo',
    ) ) );

}
add_action( 'customize_register', 'bones_customise_register' );


// Adding the color customisation above
function bones_customise_colors() {

/**********************
text colors
**********************/
// main color
$color_scheme_1 = get_option( 'color_scheme_1' );
 


/****************************************
styling
****************************************/
?>
<style>
 
 
/* color scheme */
 
/* main color */

.excerpt-read-more, .tagline a, .NewsCats h3 span,  #Hero span, article header span.tagline, #breadcrumbs a, p a, article header span{
  color: <?php echo $color_scheme_1; ?> !important;

}

.NewsCats .more_stories, .subscribe-form, header .nav li.current-menu-item, header .nav li.current_page_item, header .nav li.current_page_ancestor, header .nav li:hover, header .nav li:focus, button, input#submit{
	background:	<?php echo $color_scheme_1; ?> !important;
}

</style>

<?php

}
add_action( 'wp_head', 'bones_customise_colors' );



/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 640;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-800', 800, 200, true );
add_image_size( 'bones-thumb-600', 680, 250, true );
add_image_size( 'bones-thumb-300', 300, 100, true );
add_image_size( 'category', 250, 200, true );
add_image_size( 'bones-thumb-450', 450, 300, true );


/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
	    'bones-thumb-800' => __('800px by 200px'),
        'bones-thumb-600' => __('680px by 250px'),
        'bones-thumb-300' => __('300px by 100px'),
		'bones-thumb-250' => __('250px by 200px'),
		'bones-thumb-450' => __('450px by 350px'),
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/

/* 
  A good tutorial for creating your own Sections, Controls and Settings:
  http://code.tutsplus.com/series/a-guide-to-the-wordpress-theme-customizer--wp-33722
  
  Good articles on modifying the default options:
  http://natko.com/changing-default-wordpress-theme-customization-api-sections/
  http://code.tutsplus.com/tutorials/digging-into-the-theme-customizer-components--wp-27162
  
  To do:
  - Create a js for the postmessage transport method
  - Create some sanitize functions to sanitize inputs
  - Create some boilerplate Sections, Controls and Settings
*/

function bones_theme_customizer($wp_customize) {
  // $wp_customize calls go here.
  //
  // Uncomment the below lines to remove the default customize sections 

  // $wp_customize->remove_section('title_tagline');
  // $wp_customize->remove_section('colors');
  // $wp_customize->remove_section('background_image');
  // $wp_customize->remove_section('static_front_page');
  // $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');
  
  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
}

add_action( 'customize_register', 'bones_theme_customizer' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'bonestheme' ),
		'description' => __( 'The first (primary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:
*/
	register_sidebar(array(
		'id' => 'header-right',
		'name' => __( 'Header Right', 'bonestheme' ),
		'description' => __( 'Top Right Header Widgets.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
/*
	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'bonestheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'bonestheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your scss files
and be up and running in seconds.
*/
function bones_fonts() {
 // wp_enqueue_style('googleFonts', 'http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
  wp_enqueue_style('googleFonts', 'http://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400');
}

add_action('wp_enqueue_scripts', 'bones_fonts');

// Enable support for HTML5 markup.
	add_theme_support( 'html5', array(
		'comment-list',
		'search-form',
		'comment-form'
	) );

function string_limit_words($string, $word_limit)
{
  $words = explode(' ', $string, ($word_limit + 1));
  if(count($words) > $word_limit)
  array_pop($words);
  return implode(' ', $words);
}


function setup_theme_admin_menus() {
    // We will write the function contents very soon.
	 add_submenu_page('themes.php',
        'Front Page Elements', 'Front Page', 'manage_options',
        'front-page-elements', 'theme_front_page_settings'); 
}

function theme_front_page_settings() {
	wp_enqueue_media();
	$cats = get_categories();
	$posts = get_posts(array(
	'posts_per_page'   => 15,
	'offset'           => 0,
	'orderby'          => 'post_date',
	'order'            => 'DESC',
	'post_type'        => 'post',
	'post_status'      => 'publish',
	'suppress_filters' => true ));
	$front_page_elements = get_option("theme_name_front_page_elements");
    $front_page_editors_choice = get_option("front_page_editors_choice");
	$small_banner = get_option("front_page_small_banner");
	$small_banner_link_url = get_option("front_page_small_banner_link_url");
	$small_banner_link_target = get_option("front_page_small_banner_link_target");
	$newsletter_shortcode = trim(get_option("front_page_newsletter_shortcode"));
	$banner_text = get_option("banner_text");
	
	$banner_text_link_url = get_option("front_page_banner_text_link_url");
	$banner_text_link_target = get_option("front_page_banner_text_link_target");
	$ideo_tracking = get_option("ideo_tracking");
	
	
	if (isset($_POST["update_settings"])) {
    // Do the saving
		
		$front_page_elements = array();
         
		$max_id = esc_attr($_POST["element-max-id"]);
			for ($i = 0; $i < $max_id; $i ++) {
		    $field_name = "element-page-id-" . $i;
			    if (isset($_POST[$field_name])) {
			        $front_page_elements[] = esc_attr($_POST[$field_name]);
			    }
			}
         
		update_option("theme_name_front_page_elements", $front_page_elements);
		
		
		if(isset($_POST['hero_story'])){
	
			update_option("front_page_hero_story",$_POST['hero_story']);
		}

        if(isset($_POST['editors_choice'])){

            update_option("front_page_editors_choice",$_POST['editors_choice']);
        }
		
		if(isset($_POST['newsletter_shortcode'])){
	
			update_option("front_page_newsletter_shortcode",$_POST['newsletter_shortcode']);
		}
		
		if(isset($_POST['small_banner'])){
	
			update_option("front_page_small_banner",$_POST['small_banner']);
		}
		if(isset($_POST['small_banner_link_url'])){

			update_option("front_page_small_banner_link_url",$_POST['small_banner_link_url']);
		}
		
		if(isset($_POST['small_banner_link_target'])){
			
			update_option("front_page_small_banner_link_target", $_POST['small_banner_link_target']);
		}
		
		if(isset($_POST['banner_text'])){
			
			update_option("banner_text", $_POST['banner_text']);
		}
		
		if(isset($_POST['banner_text_link_url'])){

			update_option("front_page_banner_text_link_url",$_POST['banner_text_link_url']);
		}
		
		
		if(isset($_POST['banner_text_link_target'])){
			
			update_option("front_page_banner_text_link_target", $_POST['banner_text_link_target']);
		}
		
		
		if(isset($_POST['ideo_tracking'])){
			
			update_option("ideo_tracking", $_POST['ideo_tracking']);
		}
		
		?>

        <div id="message" class="updated">Settings saved</div>

        <?php }
	
?>

<style type="text/css">
	.box{
        background: none repeat scroll 0 0 #ffffff;
        border: 1px solid #e5e5e5;
        box-shadow: 0 1px 1px rgba(0, 0, 0, 0.04);
        position: relative;
        padding:20px;
	}
</style>
     
     

<div class="wrap box">
    <?php screen_icon('themes'); ?> <h2>Home Settings</h2>
 
    <form method="POST" action="">

        <div style="margin-top:30px;">
            <h3>Hero Story</h3>
            <table>
                <tr>
                    <td>
                        <label for="hero_story">Select Post:</label>
                    </td>
                    <td>
                        <select name="hero_story">
                            <?php foreach ($posts as $post) :
                                $post_cat =	get_the_category( $post->ID );
                            ?>
                            <?php $selected = ($post->ID == $hero_story) ? "selected" : ""; ?>
                            <option value="<?php echo $post->ID; ?>" <?php echo $selected; ?>>
                            <?php echo $post_cat[0]->name ?> - <?php echo  $post->post_title; ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>

            <h3>Editor's Choice</h3>
            <table>
                <tr>
                    <td>
                        <label for="editors_choice">Select Post:</label>
                    </td>
                    <td>
                        <select name="editors_choice">
                            <?php foreach ($posts as $post) :
                                $post_cat =	get_the_category( $post->ID );
                                ?>
                                <?php $selected = ($post->ID == $front_page_editors_choice) ? "selected" : ""; ?>
                                <option value="<?php echo $post->ID; ?>" <?php echo $selected; ?>>
                                    <?php echo $post_cat[0]->name ?> - <?php echo  $post->post_title; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>
       
        <div id="featuredCats">
 
        <h3>Featured Categories on Home</h3>

        <ul id="featured-posts-list">
        		
        <li class="front-page-element" id="front-page-element-placeholder" style="display:none">
            <label for="element-page-id">Featured Category:</label>
            <select name="element-page-id">
            <?php foreach ($cats as $cat) : ?>
            <option value="<?php echo $cat->cat_ID; ?>">
            <?php echo $cat->name; ?>
            </option>
            <?php endforeach; ?>
            </select>
            <a href="#">Remove</a>
        </li>
                
        <?php $element_counter = 0;
        foreach ($front_page_elements as $element) : ?>
        <li class="front-page-element" id="front-page-element-<?php echo $element_counter; ?>">
            <label for="element-page-id-<?php echo $element_counter; ?>">Featured Cateogry:</label>
            <select name="element-page-id-<?php echo $element_counter; ?>">
                <?php foreach ($cats as $cat) : ?>
                <?php $selected = ($cat->cat_ID == $element) ? "selected" : ""; ?>
                <option value="<?php echo $cat->cat_ID; ?>" <?php echo $selected; ?>>
                <?php echo $cat->name; ?>
                </option>
                <?php endforeach; ?>
            </select>
            <a href="#" onclick="removeElement(jQuery(this).closest('.front-page-element'));">Remove</a>
        </li>
		      
		<?php

        $element_counter++;

		endforeach; ?>

        </ul>

        <a href="#" id="add-featured-post">Add featured Category</a>

        </div>
        
         <div style="margin-top:30px;">
         <h3>Shortcode for Newsletter Form</h3>
    		<table>
            	<tr>
                	<td>
			            <label for="newsletter_shortcode">Shortcode:</label>
                    </td>
                    <td>
			   			<input type="text" name="newsletter_shortcode" value ="<?php echo $newsletter_shortcode?>  "id="newsletter_shortcode" class="regular-text">
                       
                    </td>
                 </tr>
               </table>
           </div>
         <hr>
        
         <div style="margin-top:30px;">
         <h3>Home Page Banners</h3>
    		<table>
            	<tr>
                	<td>
			            <label for="image_url">Small Banner For Home Page:</label>
                    </td>
                    <td>
			   			<input type="text" name="small_banner" value ="<?php echo $small_banner?>  "id="image_url" class="regular-text">
                        <input type="button" name="upload-btn" id="upload-btn" class="button-secondary" value="Upload Image">
                    </td>
                 </tr>
		    
				<tr>
                	<td>
		    	        <label for="small_banner_link_url">Small Banner Link:</label>
        	       </td>
                   <td>
   					<input type="text" name="small_banner_link_url" value ="<?php echo $small_banner_link_url?>" id="small_banner_link_url" class="regular-text">
                    </td>
                 </tr>
		    
				<tr>
                	<td>
		    	        <label for="small_banner_link_target">Small Banner Link Target:</label>
        	       </td>
                   <td>
		    			<select name="small_banner_link_target">
                        	<option value="">Select Target</option>
        					<option <?php if($small_banner_link_target =='_self'){ echo "selected";} ?> value="_self">Self</option>
                    		<option <?php if($small_banner_link_target =='_blank'){ echo "selected";} ?> value="_blank">New Tab</option>
             			</select>
                       </td>
                    </tr>
                   </table>
		</div>
        
        
         <hr>
        
         <div style="margin-top:30px;">
         <h3>Text On Pink Banner</h3>
    		<table>
            	<tr>
                	<td>
			            <label for="banner_text">Text:</label>
                    </td>
                    <td>
			   			<input type="text" name="banner_text" value ="<?php echo $banner_text?> " style="width:700px;"id="banner_text" class="regular-text">
                       
                    </td>
                 </tr>
               <tr>
                	<td>
		    	        <label for="banner_text_link_url">Text Banner Link:</label>
        	       </td>
                   <td>
   					<input type="text" name="banner_text_link_url" value ="<?php echo $banner_text_link_url?>" id="banner_text_link_url" class="regular-text">
                    </td>
                 </tr>
		    
				<tr>
                	<td>
		    	        <label for="banner_text_link_target">Text Banner Link Target:</label>
        	       </td>
                   <td>
		    			<select name="banner_text_link_target">
                        	<option value="">Select Target</option>
        					<option <?php if($banner_text_link_target =='_self'){ echo "selected";} ?> value="_self">Self</option>
                    		<option <?php if($banner_text_link_target =='_blank'){ echo "selected";} ?> value="_blank">New Tab</option>
             			</select>
                       </td>
                    </tr>
                   </table>
           </div>
        
      
        
        
         <hr>
         
         
                  <div style="margin-top:30px;">
         <h3>Ideo Tracking</h3>
    		<table>
            	<tr>
                	<td>
			            <label for="ideo_tracking">Toggle ON/OFF</label>
                    </td>
                    <td><?php print $ideo_tracking ?>
			   			<select name="ideo_tracking">
                        	<option value="0">Select Status</option>
        					<option <?php if($ideo_tracking ==0){ echo "selected";} ?> value="0">Off</option>
                    		<option <?php if($ideo_tracking ==1){ echo "selected";} ?> value="1">On</option>
             			</select>
                       
                    </td>
                 </tr>
               </table>
           </div>
        
        
         <hr>
        
       	<input type="hidden" name="update_settings" value="Y" />
        <input type="hidden" name="element-max-id" value="<?php echo $element_counter; ?>" />
 
         
        <p>
    		<input type="submit" value="Save settings" class="button-primary"/>
		</p>
    </form>
     
   
 

 
</div>
     <script type="text/javascript">
    	var elementCounter = jQuery("input[name=element-max-id]").val();
		
		function setElementId(element, id) {
    		var newId = "front-page-element-" + id;   
                      
    		jQuery(element).attr("id", newId);              
                
   			 var inputField = jQuery("select", element);
   			 inputField.attr("name", "element-page-id-" + id);
                 
   			 var labelField = jQuery("label", element);
		    labelField.attr("for", "element-page-id-" + id);
	}
	function removeElement(element) {
    			jQuery(element).remove();
			}
	
    jQuery(document).ready(function() { 
		jQuery("#featured-posts-list").sortable( {
    			stop: function(event, ui) {
        		var i = 0;
 
        		jQuery("li:not(:first)", this).each(function() {
           			 setElementId(this, i);                   
           			 i++;
		        });
                    
        	elementCounter = i;
        	jQuery("input[name=element-max-id]").val(elementCounter);
    	}
	});       
	         
        jQuery("#add-featured-post").click(function() {
            var elementRow = jQuery("#front-page-element-placeholder").clone();
            var newId = "front-page-element-" + elementCounter;
                
            elementRow.attr("id", newId);
            elementRow.show();
                
            var inputField = jQuery("select", elementRow);
            inputField.attr("name", "element-page-id-" + elementCounter);
                 
            var labelField = jQuery("label", elementRow);
            labelField.attr("for", "element-page-id-" + elementCounter);
 			var removeLink = jQuery("a", elementRow).click(function() {
    			removeElement(elementRow); 
    			return false;
			});
			
            elementCounter++;
            jQuery("input[name=element-max-id]").val(elementCounter);
                 
            jQuery("#featured-posts-list").append(elementRow);
                
            return false;
			
			
			
        }); 
		
		jQuery('#upload-btn').click(function(e) {
        e.preventDefault();
        var image = wp.media({ 
            title: 'Upload Image',
            // mutiple: true if you want to upload multiple files at once
            multiple: false
        }).open()
        .on('select', function(e){
            // This will return the selected image from the Media Uploader, the result is an object
            var uploaded_image = image.state().get('selection').first();
            // We convert uploaded_image to a JSON object to make accessing it easier
            // Output to the console uploaded_image
            console.log(uploaded_image);
            var image_url = uploaded_image.toJSON().url;
            // Let's assign the url value to the input field
            jQuery('#image_url').val(image_url);
        });
    });
		
		
   });
</script>
<?php

}



/* ADD IDEO SCRIPT TO FOOTER */
function ideo_tracking() {
    echo "<!-- idio Analytics Tracking Code --><script type='text/javascript'>_iaq = [['client', 'bostonivy'],['delivery', 244],['track', 'consume']];!function(d,s){var ia=d.createElement(s);ia.async=1,s=d.getElementsByTagName(s)[0],ia.src='//s.idio.co/ia.js',s.parentNode.insertBefore(ia,s)}(document,'script');</script><!-- / idio Analytics Tracking Code -->";
}

if(get_option("ideo_tracking") ==1){
add_action('wp_footer', 'ideo_tracking', 100);

}
 
// This tells WordPress to call the function named "setup_theme_admin_menus"
// when it's time to create the menu pages.
add_action("admin_menu", "setup_theme_admin_menus");


if (is_admin()) {
    wp_enqueue_script('jquery-ui-sortable');
}


add_theme_support( 'infinite-transporter', array( 'container' => 'scroll' ));



function SearchFilter($query) {
if ($query->is_search) {
$query->set('post_type', 'post');
}
return $query;
}

add_filter('pre_get_posts','SearchFilter');

add_filter('widget_text', 'do_shortcode');




add_filter( 'rwmb_meta_boxes', 'news_register_meta_boxes' );

function news_register_meta_boxes( $meta_boxes )
{
    $prefix = 'rw_';

    // 1st meta box
    $meta_boxes[] = array(
        'id'       => 'tagline',
        'title'    => 'Tag Line',
        'pages'    => array( 'post', 'page' ),
        'context'  => 'normal',
        'priority' => 'high',

        'fields' => array(
            array(
                'name'  => 'tagline',
                'id'    => $prefix . 'tagline',
                'type'  => 'text',
				'class' => 'large-text'
            ),
        )
    );
	
	 $meta_boxes[] = array(
        'id'       => 'intro',
        'title'    => 'Intro Text',
        'pages'    => array( 'post', 'page' ),
        'context'  => 'normal',
        'priority' => 'high',

        'fields' => array(
            array(
                'name'  => 'intro',
                'id'    => $prefix . 'intro',
                'type'  => 'textarea',
				'class' => 'large-text'
            ),
        )
    );



    return $meta_boxes;
}


/*  Add responsive container to embeds
/* ------------------------------------ */ 
function alx_embed_html( $html ) {
    return '<div class="video-container">' . $html . '</div>';
}
 
add_filter( 'embed_oembed_html', 'alx_embed_html', 10, 3 );
add_filter( 'video_embed_html', 'alx_embed_html' ); // Jetpack

// enqueue custom scripts
function theme_js(){

    wp_register_script( 'retina', get_template_directory_uri() . '/library/js/libs/retina.js', array('jquery'), '1.0.0' );
    wp_enqueue_script( 'retina' );

}
add_action( 'wp_enqueue_scripts', 'theme_js' );




/* DON'T DELETE THIS CLOSING TAG */ ?>
