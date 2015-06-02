<?php
/*
 * This is the default post format.
 *
 * So basically this is a regular post. if you don't want to use post formats,
 * you can just copy ths stuff in here and replace the post format thing in
 * single.php.
 *
 * The other formats are SUPER basic so you can style them as you like.
 *
 * Again, If you want to remove post formats, just delete the post-formats
 * folder and replace the function below with the contents of the "format.php" file.
*/
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('cf'); ?> role="article" itemscope itemprop="blogPost" itemtype="http://schema.org/BlogPosting">

    <header class="entry-header article-header cf">
        <div class="m-all t-1of2 d-1of2 cf herotext">
            <span class="tagline"> <?php echo rwmb_meta( 'rw_tagline'); ?></span>
            <h1 class="entry-title single"  itemprop="headline" rel="bookmark">
                <?php the_title(); ?>
           </h1>
           <div class="byline entry-meta vcard">
           <p class="tags"><?php printf( __( 'Posted by: %1$s', 'bonestheme' ),
           /* the author of the post */
           '<span class="entry-author author" itemprop="author" itemscope itemptype="http://schema.org/Person">' . get_the_author_link( get_the_author_meta( 'ID' ) ) . '</span>'); ?></p>
           <p class="tags">
           <time class="updated entry-time" datetime="<?php echo get_the_time('Y-m-d')?>" itemprop="datePublished"><?php echo get_the_time(get_option('date_format'))?></time>
           </p>
           <p class="tags"><?php printf(__( 'Filed under: %1$s', 'bonestheme' ), get_the_category_list(', ') ); ?></p>

           <?php the_tags( '<p class="tags">' . __( 'Tags: ', 'bonestheme' ), ', ', '</p>' ); ?>
           </div>
         </div>
          <div class="m-all t-1of2 d-1of2 last-col cf">
                    <?php the_post_thumbnail( 'medium' ); ?>
          </div>
    </header>

    <section class="entry-content cf" itemprop="articleBody">
        <?php
        // the content (pretty self explanatory huh)
        the_content();

        /*
        * Link Pages is used in case you have posts that are set to break into
        * multiple pages. You can remove this if you don't plan on doing that.
        *
        * Also, breaking content up into multiple pages is a horrible experience,
        * so don't do it. While there are SOME edge cases where this is useful, it's
        * mostly used for people to get more ad views. It's up to you but if you want
        * to do it, you're wrong and I hate you. (Ok, I still love you but just not as much)
        *
        * http://gizmodo.com/5841121/google-wants-to-help-you-avoid-stupid-annoying-multiple-page-articles
        *
        */
        wp_link_pages( array(
        'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'bonestheme' ) . '</span>',
        'after'       => '</div>',
        'link_before' => '<span>',
        'link_after'  => '</span>',
        ) );
        ?>
    </section>

<footer class="article-footer"></footer>

</article>

<?php comments_template(); ?>