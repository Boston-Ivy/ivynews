<?php get_header(); ?>

<div id="content">
    <div id="inner-content" class="wrap cf">

        <main id="main" class="m-all t-2of3 d-5of7 cf" role="main">

            <header>
                <h1><?php _e('You searched for: &ldquo;'); the_search_query(); _e('&rdquo;'); ?></h1>
            </header>

            <section class="m-all t-all d-all">

            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>

                <article id="post-<?php the_ID(); ?>" <?php post_class( "m-all t-1of2 d-1of2 $lastCol cf grid" ); ?> role="article">

                    <inner>

                        <header class="entry-header article-header">

                            <?php $search_thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'bones-thumb-450' ); ?>

                            <a href="<?php the_permalink(); ?>" class="archive-thumb" rel="bookmark" style="background-image: url(<?php echo $search_thumb[0]; ?>);">
                                <?php the_title(); ?>
                            </a>

                            <h2 class="h3 margin entry-title">
                                <a href="<?php the_permalink(); ?>">
                                    <?php $tagline =  rwmb_meta('rw_tagline');

                                    if( !empty($tagline)) { ?>

                                        <span class="tagline"><?php echo $tagline ?></span>

                                    <?php } ?>

                                    <?php the_title(); ?>
                                </a>
                            </h2>
                        </header>

                        <section class="entry-content cf">
                            <p class="excerpt"><a href="<?php echo get_permalink($post->ID); ?>"><?php the_excerpt_rss(); ?></a></p>
                        </section>

                        <footer class="article-footer">
                            <p class="byline entry-meta vcard">
                                <?php printf( __( 'Posted %1$s by %2$s', 'bonestheme' ),
                                             /* the time the post was published */
                                '<time class="updated entry-time" datetime="' . get_the_time('Y-m-d') . '" itemprop="datePublished">' . get_the_time(get_option('date_format')) . '</time>',
                                                /* the author of the post */
                                ' <span class="entry-author author" itemprop="author" itemscope itemptype="http://schema.org/Person">' . get_the_author_link( get_the_author_meta( 'ID' ) ) . '</span>'
                                ); ?>
                            </p>
                        </footer>

                    </inner>

                </article>

            <?php endwhile; ?>

            </section>

            <?php bones_page_navi(); ?>

            <?php else : ?>

                <article id="post-not-found" class="hentry cf">
                    <header class="article-header">
                        <h1><?php _e( 'Sorry, No Results.', 'bonestheme' ); ?></h1>
                    </header>
                    <section class="entry-content">
                        <p><?php _e( 'Try your search again.', 'bonestheme' ); ?></p>
                    </section>
                    <footer class="article-footer">
                            <p><?php _e( 'This is the error message in the search.php template.', 'bonestheme' ); ?></p>
                    </footer>
                </article>

            <?php endif; ?>

        </main>

        <?php get_sidebar(); ?>

    </div>

</div>

<?php get_footer(); ?>
