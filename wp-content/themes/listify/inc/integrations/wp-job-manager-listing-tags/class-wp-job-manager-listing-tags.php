<?php
/**
 * WP Job Manager - Listing Tags
 *
 * @since 1.10.0
 */
class Listify_WP_Job_Manager_Listing_Tags extends Listify_Integration {

	/**
	 * @since 1.10.0
	 */
    public function __construct() {
        $this->integration = 'wp-job-manager-listing-tags';
        $this->includes = array(
			'../wp-job-manager-tags/widgets/class-widget-job_listing-tags.php',
            'widgets/class-widget-job_listing-listing-tags.php',
        );

        parent::__construct();
    }

	/**
	 * Hook in to WordPress
	 *
	 * @since 1.10.0
	 */
    public function setup_actions() {
		$listing_tags = WPJMLT_Front_Setup::get_instance();
        remove_filter( 'the_job_description', array( $listing_tags, 'display_tags' ) );

        add_action( 'widgets_init', array( $this, 'widgets_init' ) );
    }

	/**
	 * Register widgets.
	 *
	 * @since 1.10.0
	 */
    public function widgets_init() {
        register_widget( 'Listify_Widget_Listing_Listing_Tags' );
    }

}

$GLOBALS[ 'listify_job_manager_listing_tags' ] = new Listify_WP_Job_Manager_Listing_Tags();
