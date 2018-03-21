<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * WPJM Products Support.
 *
 * @since 2.0.0
 */
class WPJMR_Products {

	/**
	 * Constructor Class.
	 *
	 * @since 2.0.0
	 */
	public function __construct() {

		// Limit reviewer to product owner.
		add_action( 'wpjmr_rating_field_init', array( $this, 'rating_field_for_product_owner' ), 10, 3 );
	}

	/**
	 * Rating Field for Product Owner
	 *
	 * @since 2.0.0
	 *
	 * @param object $post         WP_Post object.
	 * @param object $current_user WP_User object.
	 * @param bool   $is_author    True if current user is listing author.
	 * @return void
	 */
	public function rating_field_for_product_owner( $post, $current_user, $is_author ) {
		// Do not effect author of listing.
		if ( $is_author ) {
			return;
		}

		// Only if resctricted for product owner activated.
		if ( ! get_option( 'wpjmr_restrict_review' ) ) {
			return;
		}

		// Get this listing WC Products bought by user.
		$user_bought_product = false;
		$products = get_post_meta( $post->ID, '_products', true );
		if ( $products ) {
			foreach ( $products as $product_id ) {
				if ( wc_customer_bought_product( $current_user->email, $current_user->ID, $product_id ) ) {
					$user_bought_product = true; // User can submit review.
					break;
				}
			}
		}

		// User did not bought product: disable review.
		if ( ! $user_bought_product ) {

			// Remove rating field and replace with notice.
			echo sprintf( '<div id="wpjmr-restriction-messages" class="review-form-stars">%s</div>', wpautop( __( "Only people who have purchased this item can leave a review.", 'wp-job-manager-reviews' ) ) );
			add_filter( 'wpjmr_rating_field', '__return_false' );

			// Close comment fields.
			add_filter( 'comment_form_fields', '__return_empty_array' );
			add_filter( 'comment_form_submit_field', '__return_null', 10, 2 );
		}
	}

}

