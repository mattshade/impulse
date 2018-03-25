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

if($('.listing-rating--single').length > 0){
$('.listing-rating--single').insertAfter($('.job_listing-title')).css('display','block');
}
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

if($('.listing-rating--single').length > 0 ){
$('<h2>Impulse Score<sup>TM</sup></h2>').insertBefore('.listing-rating--single');
}

if($('#reply-title').length > 0 ){
$('#reply-title').text('Your Impulse Score');
}
	
if($('.claim-listing').length > 0 ){
	$('.claim-listing').addClass('button').addClass('button-secondary').css('padding','19px');
}

if($('.fieldset-job_type').length > 0 ){
	//setTimeout(function(){ $('.fieldset-job_type').text('Capabilities'); }, 2000);
//$('.fieldset-job_type').text('Capabilities');
}

if($('.no-impulse-score').length > 0 ){
	$('.no-impulse-score').insertAfter($('.job_listing-title'));
}

if($('.impul-wildcard#box-ad').length > 0 ){
	$('.impul-wildcard#box-ad').insertAfter($('.job_filters'));
}

if($('legend:contains("Biography")').length > 0 ){
	$('legend:contains("Biography")').parent().hide();
}
	
if($('.badge').length > 0 ){
	$('.job_listing-title').append($('.badge'));
	$('.badge').fadeIn('fast');
	if($('.badge').text() == ''){
		$('.badge').hide();
	}
}
	
if($('#wait_approval').length > 0 ){
   jQuery('.listing-rating').append(jQuery('#wait_approval'));
}
	
if($('input#account_password').length > 0 ){	
	jQuery('input#account_password').parent().find('label').append('<p>Your password must have 6–20 characters, with at least 2 letters and 1 number. To help make your password stronger, you may use most special characters and a combination of uppercase and lowercase letters.</p>');
}

if($('.page-title.cover-wrapper').length > 0){
	$('.page-title.cover-wrapper').fadeIn('fast');
}
	
if($('.page-title.cover-wrapper').length > 0 && $('.page-title.cover-wrapper').text() == "Memberships" ){
	$('.page-title.cover-wrapper').text("Subscriptions");
}
if($('.form-row.place-order').length > 0){
	//console.log("place is here");
	jQuery('#order_review').append('<p style="text-align:center;padding-top:10px;">Click Place Order to Enter Card Details and Complete The Transaction.</p>');
}

	

console.log("ratingsArray: " + ratingsArray);

if(ratingsArray.length){
console.log("ratingsArray: " + ratingsArray);
var filledStars = "<span class='listing-star listing-star--full'></span>";
 var emptyStars = "<span class='listing-star listing-star--empty'></span>";
 var ratingsMarkup = "<div class='sub-ratings'><ul>";
 var averageArray = [];

var avg = Array.from(ratingsArray.reduce(
        (acc, obj) => Object.keys(obj).reduce(
            (acc, key) => typeof obj[key] == "number"
                ? acc.set(key, (acc.get(key) || []).concat(obj[key]))
                : acc,
        acc),
    new Map()),
        ([name, values]) =>
            ({ name, average: values.reduce( (a,b) => a+b ) / values.length }),
    );

console.log(avg);


$.each(avg, function (key, value) {
  console.log(value.name + ":" + value.average);
  // $('.stars').append(value.name)
  averageArray.push(value.average);
  ratingsMarkup += "<li><span class='ratings-key'>" + value.name + "</span>";
  var found = false;
  for (i = 0; i < 5; i++) {
    if(i < value.average){
      ratingsMarkup += filledStars
    }else{
      ratingsMarkup += emptyStars
    }    
  }
	ratingsMarkup += "</li>"
});
ratingsMarkup += "</ul></div>";
console.log(averageArray);

var meanOfMeans = 0;
var starRating;
var impulseScoreMarkup= "";  
for(var i = 0; i < averageArray.length; i++) {
    meanOfMeans += averageArray[i];
}
starRating = meanOfMeans / averageArray.length;
console.log(starRating);
for (i = 0; i < 5; i++) {
  if(i < starRating){
    impulseScoreMarkup += filledStars
  }else{
    impulseScoreMarkup += emptyStars
  }  
}
console.log(impulseScoreMarkup)
  


console.log(ratingsMarkup);
jQuery('.listing-rating--single').eq(0).append(ratingsMarkup);


jQuery('.listing-rating .listing-stars').eq(0).html(impulseScoreMarkup);
jQuery('.listing-rating').fadeIn('fast');
} 
});

	</script>

</body>

</html>
