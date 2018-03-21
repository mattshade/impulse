<?php
/**
 * Add Settings in WPJM Settings Page.
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
 * Settings Class.
 * Handle the admin settings.
 *
 * @since 1.0.0
 */
class WPJMR_Settings {

	/**
	 * Construct.
	 *
	 * Initialize this class including hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Add settings.
		add_action( 'job_manager_settings', array( $this, 'settings_tab' ) );

		add_action( 'wp_job_manager_admin_field_wpjmr_dashboard_actions', array( $this, 'dashboard_actions_field' ), 10, 4 );
	}

	/**
	 * Settings page.
	 *
	 * Add an settings tab to the Listings -> settings page.
	 *
	 * @since 1.0.0
	 *
	 * @param array $settings Array of default settings.
	 * @return array $settings Array including the new settings.
	 */
	public function settings_tab( $settings ) {

		$settings['wpjmr_settings'] = array(
			__( 'Reviews', 'wp-job-manager-reviews' ),
			array(
				array(
					'name'          => 'wpjmr_star_count',
					'std'           => '5',
					'placeholder'   => '',
					'label'         => __( 'Stars', 'wp-job-manager-reviews' ),
					'desc'          => __( 'How many stars would you like to use?', 'wp-job-manager-reviews' ),
					'attributes'    => array()
				),
				array(
					'name'          => 'wpjmr_categories',
					'std'           => implode( PHP_EOL, wpjmr_get_categories() ),
					'placeholder'   => '',
					'label'         => __( 'Review categories', 'wp-job-manager-reviews' ),
					'desc'          => __( 'Categories you would you like to use, each category on one line.', 'wp-job-manager-reviews' ),
					'attributes'    => array(),
					'type'          => 'textarea'
				),
				array(
					'name'          => 'wpjmr_listing_authors_can_moderate',
					'std'           => '0',
					'placeholder'   => '',
					'label'         => __( 'Listing owners can moderate reviews', 'wp-job-manager-reviews' ),
					'cb_label'      => __( 'Listing owners can moderate reviews', 'wp-job-manager-reviews' ),
					'desc'          => __( 'Let listing owners moderate the reviews on their listings.', 'wp-job-manager-reviews' ),
					'attributes'    => array(),
					'type'          => 'checkbox'
				),
				array(
					'name'          => 'wpjmr_allow_owner',
					'std'           => '0',
					'placeholder'   => '',
					'label'         => __( 'Allow listing owner review', 'wp-job-manager-reviews' ),
					'cb_label'      => __( 'Allow listing owners to review their own listings.', 'wp-job-manager-reviews' ),
					'desc'          => '',
					'attributes'    => array(),
					'type'          => 'checkbox'
				),
				array(
					'name'          => 'wpjmr_allow_multiple',
					'std'           => '0',
					'placeholder'   => '',
					'label'         => __( 'Allow multiple review', 'wp-job-manager-reviews' ),
					'cb_label'      => __( 'Allow multiple review from the same user (does not apply to listing owner).', 'wp-job-manager-reviews' ),
					'desc'          => '',
					'attributes'    => array(),
					'type'          => 'checkbox'
				),
				array(
					'name'          => 'wpjmr_allow_guests',
					'std'           => '1',
					'placeholder'   => '',
					'label'         => __( 'Allow guests to review', 'wp-job-manager-reviews' ),
					'cb_label'      => __( 'Allow logged out users to leave a review.', 'wp-job-manager-reviews' ),
					'desc'          => '',
					'attributes'    => array(),
					'type'          => 'checkbox'
				),
				array(
					'name'          => 'wpjmr_allow_blank_comment',
					'std'           => '0',
					'placeholder'   => '',
					'label'         => __( 'Allow blank comment', 'wp-job-manager-reviews' ),
					'cb_label'      => __( 'Allow blank comment content in review.', 'wp-job-manager-reviews' ),
					'desc'          => '',
					'attributes'    => array(),
					'type'          => 'checkbox'
				),
				array(
					'name'          => 'wpjmr_restrict_review',
					'std'           => '0',
					'placeholder'   => '',
					'label'         => __( 'Restrict reviews to buyers', 'wp-job-manager-reviews' ),
					'cb_label'      => __( 'Restrict reviews  (Requires WP Job Manager - Products Plugin)', 'wp-job-manager-reviews' ),
					'desc'          => __( 'Restrict giving a review to users that are validated buyers of associated products.', 'wp-job-manager-reviews' ),
					'attributes'    => array(),
					'type'          => 'checkbox'
				),
				array(
					'name'          => 'wpjmr_allow_images',
					'std'           => '1',
					'placeholder'   => '',
					'label'         => __( 'Image Upload', 'wp-job-manager-reviews' ),
					'cb_label'      => __( 'Allow users to add image gallery to their review.', 'wp-job-manager-reviews' ),
					'desc'          => __( 'If enabled user can upload gallery when they submit review.', 'wp-job-manager-reviews' ),
					'attributes'    => array(),
					'type'          => 'checkbox',
				),
				array(
					'name'          => 'wpjmr_dashboard_actions',
					'std'           => array( 'approve', 'unapprove', 'spam', 'trash' ),
					'label'         => __( 'Dashboard Actions', 'wp-job-manager-reviews' ),
					'type'          => 'wpjmr_dashboard_actions',
				),
				array(
					'name'          => 'wp-job-manager-reviews',         // Plugin slug.
					'type'          => 'wp-job-manager-reviews_license', // {plugin_slug}_license.
					'std'           => '',
					'placeholder'   => '',
					'label'         => __( 'License Key', 'wp-job-manager-reviews' ),
					'desc'          => __( 'Enter the license key you received with your purchase receipt to continue receiving plugin updates.', 'wp-job-manager-reviews' )
				),
			),
		);

		return $settings;
	}

	/**
	 * Dashboard Action Field Callback
	 *
	 * @since 2.0.0
	 */
	public function dashboard_actions_field( $option, $attributes, $value, $placeholder ) {
		$value = is_array( $value ) ? $value : array(); // Make sure it's array.
		$actions = wpjmr_dashboard_actions(); // Available actions.
		?>
		<?php foreach( $actions as $action => $label ) : ?>
			<p>
				<label>
					<input name="<?php echo esc_attr( $option['name'] ); ?>[]" type="checkbox" value="<?php echo esc_attr( $action ); ?>" <?php echo in_array( $action, $value ) ? 'checked="checked"' : ''; ?>> <?php echo esc_html( $label ); ?>
				</label>
			</p>
		<?php endforeach; ?>
		<?php
	}

}
