<?php
/**
 * WP Job Manager - Favorites
 *
 * @since 1.10.0
 * @category Integration
 */
class Listify_WP_Job_Manager_Favorites extends Listify_Integration {

	/**
	 * @since 1.10.0
	 */
	public function __construct() {
		$this->has_customizer = true;
		$this->includes = array();
		$this->integration = 'wp-job-manager-favorites';

		parent::__construct();
	}

	/**
	 * Hook in to WordPress
	 *
	 * @since 1.10.0
	 */
	public function setup_actions() {
		// Filter template loading.
		add_filter( 'job_manager_locate_template', array( $this, 'locate_template' ), 10, 3 );

		// Display on listing card.
		add_action( 'listify_content_job_listing_before', array( $this, 'listing_card_favorites' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );

		// Load scripts.
		add_action( 'job_manager_job_filters_after', array( $this, 'enqueue_scripts' ) );
		add_action( 'wpjmf_favorite_form_before', array( $this, 'enqueue_scripts' ) );
	}

	/**
	 * Load templates from this local integration directory.
	 *
	 * @since 1.10.0
	 *
	 * @param string $template
	 * @param string $template_name
	 * @param string $template_path
	 */
	public function locate_template( $template, $template_name, $template_path ) {
		// If one was only found in the actual plugin, check here as well.
		if ( $template && 'wp-job-manager-favorites' == $template_path ) {
			$maybe = locate_template( array( 'inc/integrations/' . $this->integration . '/templates/' . $template_name ) );

			if ( '' !== $maybe ) {
				return $maybe;
			}
		}

		return $template;
	}

	/**
	 * Maybe display favorite count.
	 *
	 * @since Listify 1.10.0
	 */
	public function listing_card_favorites() {
		if ( ! get_theme_mod( 'listing-card-display-bookmarks', true ) ) {
			return;
		}

		$favorites = WPJMF_Form::get_instance();
		$favorites->form();
	}

	/**
	 * Register scripts.
	 *
	 * @since 1.10.0
	 */
	public function register_scripts() {
		wp_dequeue_style( 'wpjmf-form' );
		wp_register_script( 'listify-wp-job-manager-favorites', $this->get_url() . 'js/wp-job-manager-favorites.min.js', array( 'jquery', 'listify', 'wp-job-manager-ajax-filters' ) );
	}

	/**
	 * Enqueue script on favorite form.
	 *
	 * @since 1.10.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( 'listify-wp-job-manager-favorites' );
		wp_enqueue_script( 'wpjmf-form' );
	}
}

// Polute the global namespace...
// @todo get rid of this.
$GLOBALS[ 'listify_job_manager_favorites' ] = new Listify_WP_Job_Manager_Favorites();
