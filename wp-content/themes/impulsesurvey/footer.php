<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Listify
 */
?>

	</div><!-- #content -->

</div><!-- #page -->

<div class="footer-wrapper">

	

		<?php get_template_part( 'content', 'aso' ); ?>

		<?php if ( is_active_sidebar( 'widget-area-footer-1' ) || is_active_sidebar( 'widget-area-footer-2' ) || is_active_sidebar( 'widget-area-footer-3' ) ) : ?>

			<footer class="site-footer-widgets">
				<div class="container">
					<div class="row">

						<div class="footer-widget-column col-xs-12 col-sm-12 col-lg-5">
							<?php dynamic_sidebar( 'widget-area-footer-1' ); ?>
						</div>

						<div class="footer-widget-column col-xs-12 col-sm-6 col-lg-3 col-lg-offset-1">
							<?php dynamic_sidebar( 'widget-area-footer-2' ); ?>
						</div>

						<div class="footer-widget-column col-xs-12 col-sm-6 col-lg-3">
							<?php dynamic_sidebar( 'widget-area-footer-3' ); ?>
						</div>

					</div>
				</div>
			</footer>

		<?php endif; ?>

	

	<footer id="colophon" class="site-footer">
		<div class="container">

			<div class="site-info">
				<?php echo listify_partial_copyright_text(); ?>
			</div><!-- .site-info -->

			<div class="site-social">
				<?php wp_nav_menu( array(
					'theme_location' => 'social',
					'menu_class' => 'nav-menu-social',
					'fallback_cb' => '',
					'depth' => 1
				) ); ?>
			</div>

		</div>
	</footer><!-- #colophon -->

</div>

<div id="ajax-response"></div>

<?php wp_footer(); ?>


<script type="text/javascript">

jQuery(document).ready(function($){
	$('body').addClass(logClass);
	//if(!$('body').is('.loggedin')){
		//$('#listify_call_to_action-1 .button').addClass('triggerLogin');		
	//}else{
		//$('#listify_call_to_action-1').hide();
	//}
	//$('.triggerLogin').attr('href','#');
	//$('body').on('click', '.triggerLogin', function(e){		
		//$('a[data-toggle="ml-modal"]').trigger('click');
		//e.preventDefault();
	//});

if($('.claimed-ribbon').length > 0){
$('.claimed-ribbon').insertAfter($('.listing-rating--single')).css('display','block');
}

var listingDetails;

if($('.job_listing-url').length > 0){
listingDetails += $('.job_listing-url')[0].outerHTML;
}

if($('.listing-email').length > 0){
listingDetails += $('.listing-email')[0].outerHTML;
}

if($('.job_listing-phone').length > 0){
listingDetails += $('.job_listing-phone')[0].outerHTML;
}

$(listingDetails).insertAfter($('#main .job_listing-location'));

if($('.listify_widget_panel_listing_content').length > 0 && $('.capabilities-label').length > 0){
  $('.listify_widget_panel_listing_content').insertBefore($('.capabilities-label'));
}

if($('.listify_widget_panel_listing_content').length > 0 ){
  $('.listify_widget_panel_listing_content').css('display','block');
}

if (!$.trim($('.content-single-job_listing-title-category').html()).length) {
    $('.capabilities-label').hide();
}

});

	</script>

</body>

</html>
