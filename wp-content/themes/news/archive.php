<?php get_header(); ?>

    <div id="content">

        <div id="inner-content" class="wrap cf">

            <main id="main" class="m-all t-2of3 d-5of7 cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

                <?php if (have_posts()) :
                    $i = 0;
                    while (have_posts()) : the_post();

                    $lastCol = '';
                    if ($i % 2 == 0){
                    $lastCol="last-col";
                    }

                    if($i<1): ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class("cf top "); ?> role="article">

                        <header class="entry-header article-header cf">
                            <div class="m-all t-all d-all cf herotext">
                                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                                    <h1 class="entry-title single" itemprop="headline" rel="bookmark">
                                        <?php $tagline =  rwmb_meta('rw_tagline');

                                        if( !empty($tagline)) { ?>

                                            <span class="tagline"><?php echo $tagline; _e(': '); ?></span>

                                        <?php } ?>

                                        <?php the_title(); ?>
                                    </h1>
                                </a>
                            </div>
                            <div class="m-all t-all d-1of2">
                                <section class="entry-content first cf">
                                    <?php the_excerpt(); ?>
                                </section>
                            </div>
                            <div class="m-all t-all d-1of2 last-col cf">
                                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail( 'news-bones-450' ); ?></a>
                            </div>
                        </header>

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

                    </article>

                    <?php else: ?>

                    <article id="post-<?php the_ID(); ?>" <?php post_class( "m-all t-1of2 d-1of2 $lastCol cf grid" ); ?> role="article">

                        <inner>

                            <header class="entry-header article-header">
                                <a href="<?php the_permalink() ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
                                    <?php the_post_thumbnail( 'bones-thumb-450' ); ?>
                                </a>

                                <h2 class="h3 margin entry-title">
                                    <a href="<?php the_permalink(); ?>">
                                        <?php $tagline =  rwmb_meta('rw_tagline');

                                        if( !empty($tagline)) { ?>

                                            <span class="tagline"><?php echo $tagline; _e(': '); ?></span>

                                        <?php } ?>

                                        <?php the_title(); ?>
                                    </a>
                                </h2>
                            </header>

                            <section class="entry-content cf">

                                <?php

                                $intro = rwmb_meta( 'rw_intro');

                                if (!empty($intro)) {
                                    $excerpt = $intro;
                                } else {
                                    $excerpt = get_the_excerpt();
                                }

                                ?>

                                <p><?php $string = $excerpt; $trimmedText = shorten_string($string, 24); echo $trimmedText ?> <a class="excerpt-read-more" href="<?php echo get_permalink(get_the_ID()); ?>"><?php _e('Read more &raquo;'); ?></a></p>
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

                    <?php
                    endif;
                    $i++;
                    endwhile; ?>

                    <?php bones_page_navi(); ?>

                    <?php else : ?>

                        <article id="post-not-found" class="hentry cf">
                            <header class="article-header">
                                <h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
                            </header>
                            <section class="entry-content">
                                <p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
                            </section>
                            <footer class="article-footer">
                                    <p><?php _e( 'This is the error message in the archive.php template.', 'bonestheme' ); ?></p>
                            </footer>
                        </article>

                    <?php endif; ?>

            </main>

            <?php get_sidebar(); ?>

        </div>

    </div>

<?php get_footer(); ?>
