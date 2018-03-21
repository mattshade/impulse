<?php
/**
 * Display review field in comment form.
 *
 * @since 1.0.0
 *
 * @package Reviews
 * @category Core
 * @author Astoundify
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Review Rating Form Field.
 * Handles Comment Form modification.
 *
 * @since 1.0.0
 */
class WPJMR_Form {

	/**
	 * Constructor Class.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Display review field in comment.
		add_action( 'comment_form_top', array( $this, 'add_review_field' ), 1 );

		// Disable review for listing owner.
		add_action( 'wpjmr_rating_field_init', array( $this, 'rating_field_for_listing_owner' ), 10, 3 );

		// Disable review for listing owner.
		add_action( 'wpjmr_rating_field_init', array( $this, 'rating_submission_for_guests' ), 10, 3 );

		// Limit submission option.
		add_action( 'wpjmr_rating_field_init', array( $this, 'rating_submission_limit' ), 10, 3 );

		// Enable upload in comment form.
		add_filter( 'comment_form_submit_field', array( $this, 'enable_upload' ), 10, 2 );
	}

	/**
	 * Add Review Field in Comment
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function add_review_field() {
		if ( ! is_singular( 'job_listing' ) ) { // Only in job listing comment.
			return;
		}

		// Var.
		$post         = get_post();
		$current_user = wp_get_current_user();
		$is_author    = $current_user->ID && absint( $current_user->ID ) === absint( $post->post_author );

		// Before review fields hook.
		do_action( 'wpjmr_rating_field_init', $post, $current_user, $is_author );

		// Get review field and display it.
		echo apply_filters( 'wpjmr_rating_field', $this->review_field() );
	}

	/**
	 * Review Field.
	 *
	 * @since 2.0.0
	 *
	 * @return string
	 */
	public function review_field() {
		ob_start();
?>

<div id='wpjmr-submit-ratings' class='review-form-stars'>
	<div class='star-ratings ratings'>

		<?php foreach ( wpjmr_get_categories() as $index => $category ) : ?>
			<div class='rating-row'>
				<label for='<?php echo $index; ?>'><?php echo apply_filters( 'wpjmr_category_label', $category ); ?></label>
				<div class='stars choose-rating' data-rating-category='<?php echo $index; ?>'>
					<?php for ( $i = wpjmr_get_max_stars(); $i > 0 ; $i-- ) : ?>
							<span data-star-rating='<?php echo $i; ?>' class="star dashicons dashicons-star-empty"></span>
					<?php endfor; ?>
					<input type='hidden' class='required' name='star-rating-<?php echo $index; ?>' value=''>
				</div>
			</div><!-- .rating-row -->
		<?php endforeach; ?>

	</div><!-- .star-ratings.ratings -->
</div><!-- #wpjmr-submit-ratings -->

<?php if ( get_option( 'wpjmr_allow_images', false ) ) : ?>
<div id="wpjmr-submit-gallery" class="review-form-gallery">
	<p>
		<label for="wpjmr-gallery-input"><?php esc_html_e( 'Photo Gallery', 'wp-job-manager-reviews' ); ?></label>
		<input id="wpjmr-gallery-input" name="wpjmr-gallery[]" multiple="multiple" type="file">
	</p>
</div><!-- #wpjmr-submit-gallery -->
<?php endif; ?>

<?php
		return ob_get_clean();
	}

	/**
	 * Disable rating field for listing owner and notice explaining the reason.
	 *
	 * @since 2.0.0
	 *
	 * @param object $post         WP_Post object.
	 * @param object $current_user WP_User object.
	 * @param bool   $is_author    True if current user is listing author.  
	 * @return void
	 */
	public function rating_field_for_listing_owner( $post, $current_user, $is_author ) {
		if ( $is_author && ! get_option( 'wpjmr_allow_owner', '0' ) ) {
			echo sprintf( '<div id="wpjmr-restriction-messages" class="review-form-stars">%s</div>', wpautop( __( "You can't add a star rating to your own product.", 'wp-job-manager-reviews' ) ) );
			add_filter( 'wpjmr_rating_field', '__return_false' ); // Disable rating field.
		}
	}

	/**
	 * Disable comment and rating field for guests if guest review is disabled.
	 *
	 * @since 2.0.0
	 *
	 * @param object $post         WP_Post object.
	 * @param object $current_user WP_User object.
	 * @param bool   $is_author    True if current user is listing author.  
	 * @return void
	 */
	public function rating_submission_for_guests( $post, $current_user, $is_author ) {
		$allow_guests = get_option( 'wpjmr_allow_guests', true ) ? true : false;

		// If not guest is not allowed, and user is guest (not logged in), restrict.
		if ( ! $allow_guests && ! is_user_logged_in() ) {
?>
<div id="wpjmr-restriction-messages" class="review-form-stars">
	<?php echo sprintf( __( 'Guests are not allowed to post a review. Please <a href="%s">log in</a> to review.', 'wp-job-manager-reviews' ), esc_url( wp_login_url( get_permalink() ) ) ); ?>
</div><!-- #wpjmr-restriction-messages -->
<?php
			// Disable rating field and close comment fields.
			add_filter( 'wpjmr_rating_field', '__return_false' );
			add_filter( 'comment_form_fields', '__return_empty_array' );
			add_filter( 'comment_form_submit_field', '__return_null', 10, 2 );
		}
	}

	/**
	 * Limit submission option only 1 per user per listing if settings enabled.
	 *
	 * @since 2.0.0
	 *
	 * @param object $post         WP_Post object.
	 * @param object $current_user WP_User object.
	 * @param bool   $is_author    True if current user is listing author.
	 * @return void
	 */
	public function rating_submission_limit( $post, $current_user, $is_author ) {
		// Do not limit guests.
		if ( $is_author || ! is_user_logged_in() ) {
			return;
		}

		// Allow multiple review from the same user?
		$allow_multiple = get_option( 'wpjmr_allow_multiple', false ) ? true : false;
		if ( $allow_multiple ) {
			return;
		}

		// Get current user comment for this listing.
		$args = array(
			'user_id' => $current_user->ID,
			'post_id' => $post->ID,
			'parent'  => 0,
		);
		$usercomments = get_comments( $args );

		// Comment found, show messages and disable comment.
		if ( count( $usercomments ) ) {
			echo sprintf( '<div id="wpjmr-restriction-messages" class="review-form-stars">%s</div>', wpautop( __( "You have already posted a review.", 'wp-job-manager-reviews' ) ) );

			// Disable rating field and close comment fields.
			add_filter( 'wpjmr_rating_field', '__return_false' );
			add_filter( 'comment_form_fields', '__return_empty_array' );
			add_filter( 'comment_form_submit_field', '__return_null', 10, 2 );
		}
	}

	/**
	 * Enable Upload in Review Comment Form.
	 *
	 * @since 2.0.0
	 *
	 * @param string $submit_field Submit Field HTML.
	 * @param array  $args         Comment Form Args.
	 * @return string
	 */
	public function enable_upload( $submit_field, $args ) {
		wp_enqueue_script( 'jquery' );
?>
<script>
jQuery( document ).ready( function($) {
	jQuery( '#<?php echo $args['id_form']; ?>' )[0].encoding = 'multipart/form-data';
} );
</script>
<?php
		return $submit_field;
	}

}
