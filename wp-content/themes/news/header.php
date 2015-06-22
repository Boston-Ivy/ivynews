<!doctype html>

<!--[if lt IE 7]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if (IE 7)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9 lt-ie8"><![endif]-->
<!--[if (IE 8)&!(IEMobile)]><html <?php language_attributes(); ?> class="no-js lt-ie9"><![endif]-->
<!--[if gt IE 8]><!--> <html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->

	<head>
		<meta charset="utf-8">

		<?php // force Internet Explorer to use the latest rendering engine available ?>
		<meta http-equiv="X-UA-Compatible" content="IE=edge">

		<title><?php wp_title(''); ?></title>

		<?php // mobile meta (hooray!) ?>
		<meta name="HandheldFriendly" content="True">
		<meta name="MobileOptimized" content="320">
		<!--<meta name="viewport" content="width=device-width, initial-scale=1"/>-->
		<meta name='viewport' content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' />

        <!-- Standard browser icon -->
        <link href="<?php bloginfo('template_directory');?>/library/images/icon-browser-favicon_32x32.png" rel="shortcut icon" sizes="32x32">

        <!-- iOS 2.0+ and Android 2.1+ -->
        <link href="<?php bloginfo('template_directory');?>/library/images/icon-browser-iphone_152x152.png" rel="apple-touch-icon-precomposed" />

        <!-- IE 10 -->
        <meta name="msapplication-TileColor" content="#FFFFFF">
        <meta name="msapplication-TileImage" content="<?php bloginfo('template_directory');?>/library/images/icon-browser-ie_144x144.png">

        <!-- For iPad with high-resolution Retina display running iOS ≥ 7: -->
        <link href="<?php bloginfo('template_directory');?>/library/images/icon-browser-ipad_152x152.png" rel="apple-touch-icon-precomposed" sizes="152x152">

        <!-- For iPad with high-resolution Retina display running iOS ≤ 6: -->
        <link href="<?php bloginfo('template_directory');?>/library/images/icon-browser-ipad3_144x144.png" rel="apple-touch-icon-precomposed" sizes="144x144">

        <!-- For iPhone with high-resolution Retina display running iOS ≥ 7: -->
        <link href="<?php bloginfo('template_directory');?>/library/images/icon-browser-iphone_120x120.png" rel="apple-touch-icon-precomposed" sizes="120x120"">

        <!-- For iPhone with high-resolution Retina display running iOS ≤ 6: -->
        <link href="<?php bloginfo('template_directory');?>/library/images/icon-browser-iphone4_114x114.png" rel="apple-touch-icon-precomposed" sizes="114x114">

        <!-- For first- and second-generation iPad: -->
        <link href="<?php bloginfo('template_directory');?>/library/images/icon-browser-ipad_72x72.png" rel="apple-touch-icon-precomposed" sizes="72x72">

        <!-- For non-Retina iPhone, iPod Touch, and Android 2.1+ devices: -->
        <link href="<?php bloginfo('template_directory');?>/library/images/icon-browser-iphone_57x57.png" rel="apple-touch-icon-precomposed">

        <?php
			$metaTagline =  rwmb_meta( 'rw_tagline');
		if (!empty($metaTagline)){ ?>
		<meta property="article:subtitle" content="<?php echo $metaTagline; ?>">
		<?php } ?>

        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">

		<?php // wordpress head functions ?>
		<?php wp_head(); ?>
		<?php // end of wordpress head ?>

		<?php // drop Google Analytics Here ?>
		<?php // end analytics ?>

	</head>

	<body <?php body_class(); ?> itemscope itemtype="http://schema.org/WebPage">

		<div id="container">

			<header class="header" role="banner" itemscope itemtype="http://schema.org/WPHeader">

				<div id="inner-header" class="wrap cf">

					<div class="m-2of3 t-1of3 d-2of7 cf">
                        <a href="<?php echo home_url(); ?>" rel="nofollow" class="logo" ><?php bloginfo('name'); ?></a>
                        <a href="<?php echo home_url(); ?>" rel="nofollow" class="mob-logo" ><?php bloginfo('name'); ?></a>
                    </div>
                    
                    <div class="m-all t-2of3 d-5of7 last-col cf header-right">
                    	<?php if ( is_active_sidebar( 'header-right' ) ) : ?>
							<?php dynamic_sidebar( 'header-right' ); ?>
						<?php endif; ?>
                    </div>

					<div class="m-all t-all d-all">
                    	<?php echo do_shortcode('[s_ticker_display]'); ?>
                    </div>
					
				</div>

                <nav id="main-nav" role="navigation" class="m-all cf" itemscope itemtype="http://schema.org/SiteNavigationElement">
                    <?php wp_nav_menu(array(
                        'container' => false,                           // remove nav container
                        'container_class' => 'menu cf',                 // class of container (should you choose to use it)
                        'menu' => __( 'The Main Menu', 'bonestheme' ),  // nav name
                        'menu_class' => 'nav top-nav wrap cf',          // adding custom nav class
                        'theme_location' => 'main-nav',                 // where it's located in the theme
                        'before' => '',                                 // before the menu
                        'after' => '',                                  // after the menu
                        'link_before' => '',                            // before each link
                        'link_after' => '',                             // after each link
                        'depth' => 0,                                   // limit the depth of the nav
                        'fallback_cb' => ''                             // fallback function (if there is one)
                    )); ?>
                </nav>

			</header>
            
            <?php
            if (!is_front_page()){

                if ( function_exists('yoast_breadcrumb') ) {
                    yoast_breadcrumb('<div id="breadcrumbs" class="wrap cf">','</div>');
                }
            }
            ?>