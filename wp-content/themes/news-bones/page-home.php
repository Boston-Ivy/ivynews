<?php
/* Template Name: Home Page */
?>

<?php get_header(); ?>

    <div id="content">

        <div id="inner-content" class="wrap cf">

            <main id="main" class="m-all cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

                <section id="slider" class="m-all t-all d-2of3 cf">
                    <?php
                        $hero_post = get_option("front_page_hero_story");
                        if($hero_post){
                        $post = get_post($hero_post);
                        $hero_cat = get_the_category($hero_post);
                        $parent_cats = get_category_parents($hero_cat[0]->cat_ID);
                        $split_arr = split("/", $parent_cats);
                        $exclude_cat = get_cat_id($split_arr[0]);
                        $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'bones-thumb-450' );
                    ?>

                    <div id="Hero">

                        <div class="m-all t-2of2 d-2of2 cf herotext">
                            <a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title ?>">
                                <span> <?php echo rwmb_meta( 'rw_tagline'); _e(': '); ?></span>
                                <h1><?php echo $post->post_title ?></h1>
                            </a>
                        </div>

                        <div class="m-all t-1of2 d-1of2 cf herotext">
                            <p><?php echo rwmb_meta( 'rw_intro'); ?></p>
                            <p><a href="<?php echo get_permalink($post->ID); ?>"><?php _e('Read more &raquo;'); ?></a></p>
                        </div>

                        <div class="m-all t-1of2 d-1of2 last-col cf heroimg">
                            <a href="<?php echo get_permalink($post->ID); ?>" title="<?php echo $post->post_title ?>" style="background:url(<?php echo $feat_image[0] ?>) no-repeat center center;"></a>
                        </div>

                    </div>

                    <div class="secondary-posts">

                        <?php $args = array( 'posts_per_page' => 2, 'offset'=> 1 ); ?>
                        <?php $loop = new WP_Query($args); while ( $loop->have_posts() ) : $loop->the_post(); ?>
                        <?php $do_not_duplicate[] = $post->ID; ?>

                            <div class="m-all t-1of2 d-1of2 entry">
                                <h3><span><?php _e('Tagline: '); ?></span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
                                <p><?php echo rwmb_meta( 'rw_intro'); ?></p>
                            </div>

                        <?php endwhile; ?>

                    </div>

                    <?php }

                    $small_banner_url = get_option("front_page_small_banner");

                    if(isset($small_banner_url)){

                    $small_banner_link_url = get_option("front_page_small_banner_link_url");
                    if(empty($small_banner_link_url)){
                        $small_banner_link_url = "#";
                    } ?>

                    <?php } ?>

                </section>

                <script type="text/javascript">
                    var _ipc = {
                        api_key: '02F55TD5SZYNPCN7BONR',
                        complete: function(){
                            jQuery(".idio__p").each(function(){
                                _this = jQuery(this);
                                _this.text(_this.text().substring(0, 118) + "...");
                            });
                            jQuery("#idio .title").css({
                                "background-image": "url(" + jQuery(".idio__entry[data-image]").first().data('image') + ")",
                                "background-repeat": "no-repeat",
                                "background-size": "cover"
                            });
                        }
                    };
                </script>

                <script type="text/javascript" src="//s.idio.co/ip.js"></script>

                <section class="m-all t-1of2 d-1of3 last-col cf NewsCats" id='idio'>
                    <div class="cat">
                        <div class='stories'>
                            <script class="idio-recommendations" type="text/x-mustache" data-rpp="4" data-fallback-section="1">
                                {{#content}}
                                    <div class="entry idio__entry" {{#main_image_url}}data-image="{{main_image_url}}?w=300&amp;h=100" {{/main_image_url}}>
                                        <h3><span>Recommended:</span> <a href="{{link_url}}" rel="bookmark" title="{{title}}">{{title}}</a></h3>
                                        <p><font class="idio__p">{{abstract}}</font></p>
                                    </div>
                                {{/content}}
                            </script>
                        </div>
                    </div>
                    <?php

                    $newsletter_shortcode = get_option("front_page_newsletter_shortcode");

                    if($newsletter_shortcode) {

                        ?>

                        <div class="subscribe-form">
                            <div class="inner">
                                <?php echo do_shortcode($newsletter_shortcode); ?>
                            </div>
                        </div>

                    <?php } ?>
                </section>

                <script>idio.render()</script>

                <?php
                $cats = get_option("theme_name_front_page_elements");
                wp_reset_query();
                $args = array(
                     'orderby' => 'name',
                     'parent' => 0
                    );
                // $cats = get_categories($args);

                $i = 1;
                foreach ($cats as $cat_ID) :
                $cat_name = get_the_category_by_ID( $cat_ID );

                $lastCol = '';
                if($i==0 || $i== 3 || $i==6){
                $lastCol = "last-col";
                }
                $banner_text = get_option("banner_text");
                $banner_text_url = get_option("front_page_banner_text_link_url");
                $banner_text_target = get_option("front_page_banner_text_link_target");

                if($i==4 && !empty($banner_text)){
                    if(empty($banner_text_url)){ $banner_text_url = "#";}
                    if(empty($banner_text_target)){ $banner_text_target = "_self";}
                ?>
                    <div class="clearfix"></div>
                    <a href="<?php echo $banner_text_url ?>" target="<?php echo $banner_text_target ?>" title="<?php echo $banner_text ?>">
                        <div class="mid-home-banner m-all cf">
                            <h2><?php echo $banner_text ?></h2>
                        </div>
                    </a>
                <?php }

                $args = array('posts_per_page' => 1,'cat' => $cat_ID );

                if($cat_ID == $exclude_cat){
                    $args['post__not_in'] = array($hero_post);
                }

                query_posts($args);
                if (have_posts()) :
                    while (have_posts()) : the_post();

                    $feat_image = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'bones-thumb-300' );

                    endwhile;
                endif;
                wp_reset_query(); ?>

                <section id="<?php echo $cat_name ?>" class="m-all t-1of2 d-1of3 <?php echo $lastCol?> cf NewsCats">

                    <div class="cat">

                    <?php
                    $args = array( 'posts_per_page' => 4, 'cat' => $cat_ID );
                    if($cat_ID == $exclude_cat){
                        $args['post__not_in'] = array($hero_post);
                    }
                    query_posts($args);

                    if (have_posts()) : ?>

                        <div class="title" style='background: url(<?php echo $feat_image[0]?>) no-repeat; background-size: cover; '>
                            <span><?php echo $cat_name;?></span>
                        </div>

                        <div class='stories'>

                        <?php
                        while (have_posts()) : the_post(); ?>

                            <?php if ( in_array( $post->ID, $do_not_duplicate ) ) continue; ?>

                            <div class="entry">

                                <h3>
                                    <a href="<?php echo the_permalink()?>" rel="bookmark" title="<?php echo the_title()?>">
                                    <?php
                                    $tagline =  rwmb_meta('rw_tagline');

                                    if( !empty($tagline)) { ?>

                                        <span><?php echo $tagline ?> / </span>

                                    <?php } ?>
                                    <?php echo the_title()?>
                                    </a>
                                </h3>

                                <?php
                                $intro = rwmb_meta( 'rw_intro');

                                if(!empty($intro)){
                                    $excerpt = $intro;
                                }else{
                                    $excerpt = get_the_excerpt();
                                }
                                ?>

                                <p><?php  echo string_limit_words($excerpt,18);  ?> <a class="excerpt-read-more" href="<?php echo get_permalink(get_the_ID()); ?>">Read&nbsp;more&nbsp;&raquo;</a></p>

                            </div>

                            <?php endwhile;

                            else :
                                echo '<h2>No Posts for '.$cat_name.' Category</h2>';
                            endif;

                            wp_reset_query; ?>

                            <?php echo category_description( $cat_ID); ?>

                            <?php $category_link = get_category_link( $cat_ID ); ?>

                        </div>

                        <a class="more_stories" href="<?php echo $category_link ?>" rel="bookmark" title="View all stories in <?php echo $cat_name;?>">More stories &raquo;</a>

                    </div>

                </section>

                <?php
                $i++;

                if($i==7){
                    break;
                }
                endforeach; ?>

            </main>

        </div>

    </div>

<?php get_footer(); ?>
