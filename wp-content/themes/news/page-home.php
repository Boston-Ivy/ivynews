<?php
/* Template Name: Home Page */
?>

<?php get_header(); ?>

    <div id="content">

        <div id="inner-content" class="wrap cf">

            <main id="main" class="m-all cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

                <?php /* Begin first row – Hero and Recommended/Editor's Choice */ ?>
                <section class="m-all t-all d-all cf">

                    <div class="m-all t-all d-2of3">

                        <?php

                        $hero_post = get_option("front_page_hero_story");

                        if ($hero_post) {
                            $post = get_post($hero_post);
                            $hero_cat = get_the_category($hero_post);
                            $parent_cats = get_category_parents($hero_cat[0]->cat_ID);
                            $split_arr = split("/", $parent_cats);
                            $exclude_cat = get_cat_id($split_arr[0]);
                            $hero_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'bones-thumb-450' );
                            $hero_id = $post->ID;
                            $sub_hero_ids[] = $hero_id;
                        ?>

                        <div id="Hero">

                            <div class="m-all t2of2 d2of2 herotext">
                                <h1><a href="<?php echo get_permalink($post->ID); ?>">

                                        <?php

                                        $intro = rwmb_meta( 'rw_tagline');

                                        if (!empty($intro)) {
                                            _e('<span>'); echo $excerpt = $intro; _e(': </span>');
                                        }

                                        echo $post->post_title

                                        ?>

                                </a></h1>
                            </div>

                            <div class="m-all t-1of2 d-1of2 cf herotext">
                                <p class="excerpt"><a href="<?php echo get_permalink($post->ID); ?>"><?php echo rwmb_meta( 'rw_intro'); ?></a></p>
                                <p><a href="<?php echo get_permalink($post->ID); ?>"><?php _e('Read more &raquo;'); ?></a></p>

<!--                                --><?php
//
//                                $intro = rwmb_meta( 'rw_intro');
//
//                                if (!empty($intro)) {
//                                    $hero_excerpt = $intro;
//                                } else {
//                                    $hero_excerpt = $post->post_excerpt;
//                                }
//
//                                ?>

<!--                                <p class="excerpt"><a href="--><?php //echo get_permalink($post->ID); ?><!--">--><?php //$string = $hero_excerpt; $trimmedText = shorten_string($string, 24); echo $trimmedText ?><!--</a> <a href="--><?php //echo get_permalink(get_the_ID()); ?><!--" class="read-more">--><?php //_e('Read more &raquo;'); ?><!--</a></p>-->

                            </div>

                            <div class="m-all t-1of2 d-1of2 last-col cf heroimg">
                                <a href="<?php echo get_permalink($post->ID); ?>" class="hero-thumb" title="<?php echo $post->post_title ?>" style="background-image: url(<?php echo $hero_image[0]; ?>);"></a>
                            </div>

                        </div>

                    </div>

                    <div class="m-all t-all d-1of3 last-col">

                        <script type="text/javascript">
                            var _ipc = {
                                api_key: '02F55TD5SZYNPCN7BONR',
                                complete: function(){

                                    jQuery(".idio__p").each(function(){

                                        var $ = jQuery;
                                        var $string = $(this);
                                        var maxLength = 118;
                                        var trimmedString = $string.text().substr(0, maxLength);

                                        trimmedString = trimmedString.substr(0, Math.min(trimmedString.length, trimmedString.lastIndexOf(" "))) + "…";

                                        $string.text(trimmedString);

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

                        <section id="idio" class="m-all t-all d-all cf NewsCats">

                            <div class="cat">

                                <div class="stories">

                                    <script class="idio-recommendations" type="text/x-mustache" data-rpp="2" data-fallback-section="1">
                                    {{#content}}
                                        <div class="entry idio__entry" {{#main_image_url}}data-image="{{main_image_url}}?w=300&amp;h=100" {{/main_image_url}}>
                                            <h3><span>Recommended:</span> <a href="{{link_url}}" rel="bookmark" title="{{title}}">{{title}}</a></h3>
                                            <p><font class="idio__p">{{abstract}}</font></p>
                                        </div>
                                    {{/content}}
                                    </script>

                                    <?php

                                    $editors_choice = get_option("front_page_editors_choice");

                                    if ($editors_choice) {

                                        $post = get_post($editors_choice); ?>

                                        <div class="entry editors-choice">

                                            <h3><span><?php _e("Editor's choice: "); ?></span><a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title ?></a></h3>
                                            <p class="excerpt"><a href="<?php echo get_permalink($post->ID); ?>"><?php $string = rwmb_meta( 'rw_intro'); $trimmedText = shorten_string($string, 18); echo $trimmedText ?></a></p>

                                        </div>

                                    <?php } ?>

                                </div>

                            </div>

                        </section>

                        <script>idio.render()</script>

                    </div>

                </section>

                <?php /* Begin second row – 2 x Sub-Hero / Newsletter subscribe form */ ?>
                <section class="m-all t-all d-all">

                    <?php $args = array( 'showposts' => 2, 'post__not_in' => array( $hero_id ) ); ?>

                    <?php $loop = new WP_Query($args); while ( $loop->have_posts() ) : $loop->the_post(); ?>

                        <?php $sub_hero_ids[] = get_the_ID(); ?>

                        <div class="m-all t-1of2 d-1of3 secondary-post entry">

                            <h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>

                            <?php

                            $intro = rwmb_meta( 'rw_intro');

                            if (!empty($intro)) {
                                $excerpt = $intro;
                            } else {
                                $excerpt = get_the_excerpt();
                            }

                            ?>

                            <p class="excerpt"><a href="<?php echo get_permalink($post->ID); ?>"><?php $string = $excerpt; $trimmedText = shorten_string($string, 24); echo $trimmedText ?></a> <a href="<?php echo get_permalink(get_the_ID()); ?>" class="read-more"><?php _e('Read more &raquo;'); ?></a></p>

                        </div>

                    <?php endwhile; ?>

                    <?php }

                    $small_banner_url = get_option("front_page_small_banner");

                    if(isset($small_banner_url)) {

                        $small_banner_link_url = get_option("front_page_small_banner_link_url");

                        if (empty($small_banner_link_url)) {
                            $small_banner_link_url = "#";
                        }

                    } ?>

                    <div class="m-all t-all d-1of3 last-col">

                        <?php

                        $newsletter_shortcode = get_option("front_page_newsletter_shortcode");

                        if ($newsletter_shortcode) {

                            ?>

                            <div class="subscribe-form">
                                <div class="inner">
                                    <?php echo do_shortcode($newsletter_shortcode); ?>
                                </div>
                            </div>

                        <?php } ?>

                    </div>

                </section>

                <?php /* Begin third row – All categories with three posts from each displayed */ ?>
                <section class="m-all t-all d-all">

                    <?php

                    $cats = get_option("theme_name_front_page_elements");

                    wp_reset_query();

                    $args = array(
                        'orderby' => 'name',
                        'parent' => 0
                    );

                    $i = 1;

                    foreach ($cats as $cat_ID) :

                        $cat_name = get_the_category_by_ID( $cat_ID );
                        $cat_link = get_category_link( $cat_ID );

                        $lastCol = '';

                        if ($i==0 || $i== 3 || $i==6) {
                            $lastCol = "last-col";
                        }

//                        $args = array( 'posts_per_page' => 1, 'cat' => $cat_ID );

//                        if ($cat_ID == $exclude_cat) {
//                            $args['post__not_in'] = array($hero_post);
//                        }

                    ?>

                    <?php /* News categories */ ?>
                    <section class="m-all t-1of3 d-1of3 cf NewsCats <?php echo $lastCol?>" id="<?php echo $cat_name ?>">

                        <div class="cat">

                        <?php

                        $comma_separated = implode(", ", $sub_hero_ids );

                        $args = array(
                            'posts_per_page' => 3,
                            'cat' => $cat_ID,
                            'post__not_in' => $sub_hero_ids
                        );

                        //print_r($args);

//                        if ($cat_ID == $exclude_cat) {
//                            $args['post__not_in'] = array($hero_post);
//                        }

                        query_posts($args);
                        //echo "<p>REQUEST:$wp_query->request</p>";
                        if (have_posts()) : ?>

                            <h3 class="cat-title"><a href="<?php echo $cat_link; ?>"><?php echo $cat_name; ?></a></h3>

                            <div class='stories'>

                            <?php

                            $count = 0;

                            /* Loop through categories */
                            while (have_posts()) : the_post();

                                $thumb = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'bones-thumb-450' ); ?>

                                <div id="<?php echo get_the_ID(); ?>" class="entry">

                                    <?php $count++; if ( $count % 4 == 1 ) { /* Only show featured image for first post in category */ ?>

                                        <a href="<?php echo the_permalink(); ?>" class="cat-thumb" style="background-image: url(<?php echo $thumb[0]; ?>);"><?php echo the_title()?></a>

                                    <?php } /* and for all sequential posts do not show featured image… */ ?>

                                    <h3>
                                        <a href="<?php echo the_permalink()?>" rel="bookmark" title="<?php echo the_title()?>">
                                            <?php

                                            $tagline = rwmb_meta('rw_tagline');

                                            if ( !empty($tagline)) { ?>
                                                <span><?php echo $tagline; _e(': '); ?> </span>
                                            <?php }

                                            echo the_title();

                                            ?>
                                        </a>
                                    </h3>

                                    <?php

                                    $intro = rwmb_meta( 'rw_intro');

                                    if (!empty($intro)) {
                                        $excerpt = $intro;
                                    } else {
                                        $excerpt = get_the_excerpt();
                                    }

                                    ?>

                                    <p class="excerpt"><a href="<?php echo get_permalink($post->ID); ?>"><?php $string = $excerpt; $trimmedText = shorten_string($string, 14); echo $trimmedText ?></a> <a class="read-more" href="<?php echo get_permalink(get_the_ID()); ?>"><?php _e('Read more &raquo;'); ?></a></p>

                                </div>

                            <?php endwhile; ?>

                            </div>

                            <a href="<?php echo $category_link ?>" class="more_stories" rel="bookmark" title="View all stories in <?php echo $cat_name;?>">More stories &raquo;</a>

                            <?php

                            endif;
                            wp_reset_query();
                            echo category_description( $cat_ID);
                            $category_link = get_category_link( $cat_ID );

                            ?>

                        </div>

                    </section>

                    <?php

                    $i++;

                    if ($i==7) {
                        break;
                    }

                    endforeach;

                    ?>

                </section>

                <?php /* Begin fourth row – Pink banner */ ?>
                <section class="m-all t-all d-all">

                    <?php

                    $banner_text = get_option("banner_text");
                    $banner_text_url = get_option("front_page_banner_text_link_url");
                    $banner_text_target = get_option("front_page_banner_text_link_target");

                    if (!empty($banner_text)) {

                        if (empty($banner_text_url)) {
                            $banner_text_url = "#";
                        }

                        if (empty($banner_text_target)) {
                            $banner_text_target = "_self";
                        } ?>

                        <a href="<?php echo $banner_text_url ?>" target="<?php echo $banner_text_target ?>" title="<?php echo $banner_text ?>">
                            <div class="mid-home-banner m-all cf">
                                <h2><?php echo $banner_text ?></h2>
                            </div>
                        </a>

                    <?php } ?>

                </section>

            </main>

        </div>

    </div>

<?php get_footer(); ?>
