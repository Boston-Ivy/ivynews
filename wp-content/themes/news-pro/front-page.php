<?php
/**
 * This file adds the Home Page to the News Pro Theme.
 *
 * @author StudioPress
 * @package News Pro
 * @subpackage Customizations
 */

add_action( 'genesis_meta', 'news_home_genesis_meta' );
/**
 * Add widget support for homepage. If no widgets active, display the default loop.
 *
 */
function news_home_genesis_meta() {

	if ( is_active_sidebar( 'home-top' ) || is_active_sidebar( 'home-middle' ) || is_active_sidebar( 'home-middle-left' ) || is_active_sidebar( 'home-middle-right' ) || is_active_sidebar( 'home-bottom' ) ) {

		// Force content-sidebar layout setting
		add_filter( 'genesis_site_layout', '__genesis_return_content_sidebar' );
		
		// Add news-home body class
		add_filter( 'body_class', 'news_body_class' );
	
	}

	if ( is_active_sidebar( 'home-top' ) ) {
	
		// Add excerpt length filter
		add_action( 'genesis_before_loop', 'news_top_excerpt_length' );
	
		// Add homepage widgets
		add_action( 'genesis_before_loop', 'news_homepage_top_widget' );		
		
		// Remove excerpt length filter
		add_action( 'genesis_before_loop', 'news_remove_top_excerpt_length' );

		
	}


	if ( is_active_sidebar( 'home-middle-left' ) || is_active_sidebar( 'home-middle-right' ) || is_active_sidebar( 'home-bottom' ) ) {

		// Remove the default Genesis loop
		remove_action( 'genesis_loop', 'genesis_do_loop' );

		// Add homepage widgets
		add_action( 'genesis_loop', 'news_homepage_widgets' );

	}
}

function news_body_class( $classes ) {

	$classes[] = 'news-pro-home';
	return $classes;
	
}

function news_excerpt_length( $length ) {
	return 25; // pull first 50 words
}

function news_top_excerpt_length() {

	add_filter( 'excerpt_length', 'news_excerpt_length' );

}

function news_remove_top_excerpt_length() {

	remove_filter( 'excerpt_length', 'news_excerpt_length' );

}

function news_homepage_top_widget() {

	genesis_widget_area( 'home-top', array(
		'before' => '<div class="home-top widget-area">',
		'after'  => '</div>',
	) );

}

function news_homepage_widgets() {
	
	if ( is_active_sidebar( 'home-middle-left' ) || is_active_sidebar( 'home-middle-right' ) || is_active_sidebar( 'home-middle' ) ) {

		echo '<section class="home-middle">';
                
                
                echo '<div id="NewsCats">';
                wp_reset_query();
                $cats = get_categories('');
                foreach ($cats as $cat) :
                    $args = array('posts_per_page' => 1,'cat' => $cat->term_id );
                    query_posts($args);   
                    if (have_posts()) :
                        while (have_posts()) : the_post(); 
                        $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'medium' ); 
                        
                        endwhile;
                    endif;
                    wp_reset_query;
                    echo "<div id='$cat->name' class='cat' style='background: url($feat_image[0]) no-repeat; background-size: cover;'>";

                    $args = array('posts_per_page' => 3,'cat' => $cat->term_id ); 
                    query_posts($args); 

                    if (have_posts()) :
                            echo "<div class='title'><span>Latest Stories in ".$cat->name."</span></div>";
                            echo "<div class='stories'>";
                            while (have_posts()) : the_post(); ?>
                              <div class="entry">
                               <h3><a href="<?php echo the_permalink()?>" rel="bookmark" title="<?php echo the_title()?>"><?php echo the_title()?></a></h3>
                                <?php $excerpt = get_the_excerpt(); ?>
                               <p><?php  echo string_limit_words($excerpt,15);  ?></P>
                              </div>
                      <?php
                              

                            endwhile;
                    else : 
                            echo '<h2>No Posts for '.$cat->name.' Category</h2>';
                    endif; 
                wp_reset_query;
                    ?>
                    <a class="more_stories" href="<?php echo $category_link ?>" rel="bookmark" title="View all stories in <?php echo $cat->name;?>">More Stories</a>
                    <?php
                    echo "</div>";
                    
                    $cat_id = get_cat_ID($cat->name);
                    $category_link = get_category_link( $cat_id );
                    
                    
                echo "</div>";
            endforeach; 
          echo "</div>";
        echo "</section>"; 
                

		genesis_widget_area( 'home-middle-left', array(
			'before' => '<div class="home-middle-left widget-area">',
			'after'  => '</div>',
		) );

		genesis_widget_area( 'home-middle-right', array(
			'before' => '<div class="home-middle-right widget-area">',
			'after'  => '</div>',
		) );

	
                
                genesis_widget_area( 'home-middle', array(
			'before' => '<div class="home-middle widget-area">',
			'after'  => '</div>',
		) );

		
	
	}

	genesis_widget_area( 'home-bottom', array(
		'before' => '<div class="home-bottom widget-area">',
		'after'  => '</div>',
	) );

}

genesis();
