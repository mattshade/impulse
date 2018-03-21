<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Listing Payments/Paid Listing Integrations.
 *
 * Handle additional admin options.
 *
 * @version   1.5.1
 */
class WPJMR_Listing_Payments {

	/**
	 * Construct.
	 * Initialize this class including hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add option field.
		add_action( 'woocommerce_product_options_general_product_data', array( $this, 'product_data' ) );

		// Save field.
		add_action( 'woocommerce_process_product_meta_job_package', array( $this, 'save_package_data' ), 11 );
		add_action( 'woocommerce_process_product_meta_job_package_subscription', array( $this, 'save_package_data' ), 11 );

		// Listing ids to moderate by user.
		add_filter( 'wpjmr_current_user_listing_ids', array( $this, 'limit_to_purchased_listing_allowed_moderation' ) );
	}

	/**
	 * Show the job package product options
	 *
	 * @since 2.0.0
	 *
	 * @return void
	 */
	public function product_data() {
		global $post;
		$post_id = $post->ID;
?>
<div class="options_group show_if_job_package show_if_job_package_subscription">
	<?php woocommerce_wp_checkbox( array(
		'id'          => '_job_listing_allow_moderation',
		'label'       => __( 'Moderate Reviews?', 'wp-job-manager-reviews' ),
		'description' => __( 'Allow reviews left on this listing to be moderated by the listing owner.', 'wp-job-manager-reviews' ),
		'value'       => get_post_meta( $post_id, '_job_listing_allow_moderation', true ),
	) ); ?>
</div>
<?php
	}

	/**
	 * Save Job Package data for the product
	 *
	 * @param  int $post_id Post ID.
	 * @return void
	 */
	public function save_package_data( $post_id ) {
		update_post_meta( $post_id, '_job_listing_allow_moderation', 'yes' === $_POST['_job_listing_allow_moderation'] ? 'yes' : 'no' );
	}

	/**
	 * Limit to product review.
	 *
	 * @since 2.0.0
	 *
	 * @param array $listing_ids All listing IDs owned by current user for moderation purpose.
	 * @return array Listing IDs purchased by current user.
	 */
	public function limit_to_purchased_listing_allowed_moderation( $listing_ids ) {
		if ( ! $listing_ids ) {
			return array();
		}
		$listings = array();
		foreach ( $listing_ids as $id ) {

			// Check if moderation allowed.
			$product_id = get_post_meta( $id, '_package_id', true );
			$package_moderate = get_post_meta( $product_id, '_job_listing_allow_moderation', true );

			// Add listing if moderation allowed.
			if ( 'yes' === $package_moderate ) {
				$listings[] = $id;
			}
		}
		return $listings;
	}

}
