<?php
/**
 * Template Name: Page: Home
 *
 * @package Listify
 */

if ( ! listify_has_integration( 'wp-job-manager' ) ) {
	return locate_template( array( 'page.php' ), true );
}

get_header(); ?>
<style>

html, body, .dswrapper, svg {
  width: 100%;
  height: 100%;
/*  background-color: black;*/
} 
.dswrapper{overflow:hidden;}
circle {
  fill: #777;
}

line {
  stroke: #ddd;
  opacity: 0.5;
  stroke-width: 1px;
}
#mycanvas, .dswrapper{position:absolute;}
/*#mycanvas{display:block;position:absolute;}*/
</style>

	
	<?php while ( have_posts() ) : the_post(); ?>

		<?php $style = get_post()->hero_style; ?>

		<?php if ( 'none' !== $style ) : ?>

			<?php if ( in_array( $style, array( 'image', 'video' ) ) ) : ?>

			<div <?php echo apply_filters( 'listify_cover', 'homepage-cover page-cover entry-cover entry-cover--home entry-cover--' . get_theme_mod( 'home-hero-overlay-style', 'default' ), array( 'size' => 'full' ) ); ?>>
				<div class="cover-wrapper container">
					<?php
						the_widget(
							'Listify_Widget_Search_Listings',
							apply_filters( 'listify_widget_search_listings_default', array(
								'title' => get_the_title(),
								'description' => strip_shortcodes( get_the_content() )
							) ),
							array(
								'before_widget' => '<div class="listify_widget_search_listings">',
								'after_widget'  => '</div>',
								'before_title'  => '<div class="home-widget-section-title"><h1 class="home-widget-title">',
								'after_title'   => '</h1></div>',
								'widget_id'     => 'search-12391'
							)
						);
					?>
				</div>

				<?php if ( 'video' == $style && function_exists( 'the_custom_header_markup' ) ) : ?>
					<div class="custom-header-video">
						<div class="custom-header-media">
							<?php 
								add_filter( 'theme_mod_external_header_video', 'listify_header_video' );
								the_custom_header_markup(); 
								remove_filter( 'theme_mod_external_header_video', 'listify_header_video' );
							?>
						</div>
					</div>
				<?php endif; ?>

			</div>

			<?php else : ?>

				<div class="homepage-cover has-map">
					<?php
						do_action( 'listify_output_map' );

						if ( ! is_active_widget( false, false, 'listify_widget_map_listings', true ) ) {
							do_action( 'listify_output_results' );
						}
					?>
				</div>

			<?php endif; ?>

		<?php endif; ?>

		<?php do_action( 'listify_page_before' ); ?>

		<div class="container homepage-hero-style-<?php echo $style; ?>">

			<?php if ( listify_has_integration( 'woocommerce' ) ) : ?>
				<?php wc_print_notices(); ?>
			<?php endif; ?>

			<?php
				if ( is_active_sidebar( 'widget-area-home' ) ) :
					dynamic_sidebar( 'widget-area-home' );
				else :
					$defaults = array(
						'before_widget' => '<aside class="home-widget">',
						'after_widget'  => '</aside>',
						'before_title'  => '<div class="home-widget-section-title"><h3 class="home-widget-title">',
						'after_title'   => '</h3></div>',
						'widget_id'     => ''
					);

					the_Widget(
						'Listify_Widget_Recent_Listings',
						array(
							'title' => __( 'Recent  Listings', 'listify' ),
							'description' => __( 'Take a look at what\'s been recently added.', 'listify' ),
							'limit' => 6,
							'featured' => 0
						),
						$defaults
					);
				endif;
			?>

		</div>

	<?php endwhile; ?>
	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="/wp-content/themes/impulsesurvey/d3.min.js"></script>
	<script src="https://cdn.jsdelivr.net/lodash/4.17.4/lodash.min.js"></script>
	 <script>
			"use strict";
			var datastorm = datastorm || {};

			datastorm.canvas = (function(){
			  var my = {};

			  my.width = $(window).width();
			  my.height = $(window).height();
			   // my.height = 1200;

			  var canvas = document.getElementById('mycanvas');
			  my.ctx = canvas.getContext('2d');

			  my.ctx.fillStyle = "rgb(255,255,255)";
			  my.ctx.fillRect(0, 0, my.width, my.height);

			  my.drawCircle = function(x, y, r) {
			    my.ctx.beginPath();
			    my.ctx.arc(x, y, r, 0, 2 * Math.PI, false);
			    my.ctx.fill();
			  }

			  my.drawCircleOutline = function(x, y, r) {
			    my.ctx.beginPath();
			    my.ctx.arc(x, y, r, 0, 2 * Math.PI, false);
			    my.ctx.stroke();
			  }

			  my.drawLine = function(x0, y0, x1, y1) {
			    my.ctx.beginPath();
			    my.ctx.moveTo(x0, y0);
			    my.ctx.lineTo(x1, y1);
			    my.ctx.stroke();
			  }

			  my.clearCanvas = function() {
			    my.ctx.globalAlpha = 1;
			    // my.ctx.shadowBlur = 0;
			    my.ctx.fillStyle = "rgb(115, 232, 37)";
			    my.ctx.fillRect(0, 0, 1200, 800);
			  }

			  return my;
		  
			}());
	</script>


	<script src="/wp-content/themes/impulsesurvey/index.js"></script>

<?php get_footer(); ?>
