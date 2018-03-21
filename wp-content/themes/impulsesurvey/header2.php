<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Listify
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<script>var currentDomain = window.location.hostname;</script>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" type="image/png" href="img/favicon-32x32.png" sizes="32x32" />
	<link rel="icon" type="image/png" href="img/favicon-16x16.png" sizes="16x16" />

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">


	<?php wp_head(); ?>


		<?php
			if (!is_user_logged_in() ) {
				//$logvar = "notloggedin";
$logvar = "loggedin";
			}else{
				$logvar = "loggedin";
			}
		?>
		<script>
			var logClass = "<?php echo $logvar?>";
		</script>
	<script src="https://use.typekit.net/car2oxm.js"></script>
	<script>try{Typekit.load({ async: true });}catch(e){}</script>

<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<script src="https://use.fontawesome.com/addcdb7b71.js"></script>

<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<script>
  (adsbygoogle = window.adsbygoogle || []).push({
    google_ad_client: "ca-pub-3156098093524272",
    enable_page_level_ads: true
  });
</script>

</head>

<body <?php body_class(); ?>>
	<?php
		//echo $buttonHTML;
	?>
<div id="page" class="hfeed site">

	<header id="masthead" class="site-header<?php if ( is_front_page() ) :?> site-header--<?php echo get_theme_mod( 'home-header-style', 'default' ); ?><?php endif; ?>">
		<div class="primary-header">
			<div class="container">
				<div class="primary-header-inner">
					<div class="site-branding">
<a href="/">
						<img width="385" class="pull-left" src="https://www.impulsesurvey.com/img/impulsesurvey.png"><!-- <?php //echo listify_partial_site_branding(); ?> -->
</a>
					</div>

					<div class="primary nav-menu">
						<?php
							wp_nav_menu( array(
								'theme_location' => 'primary',
								'container_class' => 'nav-menu-container'
							) );
						?>
					</div>
				</div>

				<?php if ( get_theme_mod( 'nav-search', true ) ) : ?>
				<div id="search-header" class="search-overlay">
					<div class="container">
						<?php locate_template( array( 'searchform-header.php', 'searchform.php' ), true, false ); ?>
						<a href="#search-header" data-toggle="#search-header" class="ion-close search-overlay-toggle"></a>
					</div>
				</div>
                <?php endif; ?>
			</div>
		</div>

		<nav id="site-navigation" class="main-navigation<?php if ( is_front_page() ) : ?> main-navigation--<?php echo get_theme_mod( 'home-header-style', 'default' ); ?><?php endif; ?>">
			<div class="container">
				<a href="#" class="navigation-bar-toggle">
					<i class="ion-navicon-round"></i>
					<span class="mobile-nav-menu-label"><?php //echo listify_get_theme_menu_name( 'primary' ); ?></span>
				</a>

				<div class="navigation-bar-wrapper">
					<?php
						wp_nav_menu( array(
							'theme_location' => 'primary',
							'container_class' => 'primary nav-menu',
							'menu_class' => 'primary nav-menu'
						) );

                        if ( listify_theme_mod( 'nav-secondary', true ) ) {
                            wp_nav_menu( array(
                                'theme_location' => 'secondary',
                                'container_class' => 'secondary nav-menu',
                                'menu_class' => 'secondary nav-menu'
                            ) );
                        }
					?>
				</div>

				<?php if ( get_theme_mod( 'nav-search', true ) ) : ?>
					<a href="#search-navigation" data-toggle="#search-navigation" class="ion-search search-overlay-toggle"></a>

					<div id="search-navigation" class="search-overlay">
						<?php locate_template( array( 'searchform-header.php', 'searchform.php' ), true, false ); ?>

						<a href="#search-navigation" data-toggle="#search-navigation" class="ion-close search-overlay-toggle"></a>
					</div>
				<?php endif; ?>
			</div>
		</nav><!-- #site-navigation -->
	</header><!-- #masthead -->

	<?php do_action( 'listify_content_before' ); ?>

	<div id="content" class="site-content">
