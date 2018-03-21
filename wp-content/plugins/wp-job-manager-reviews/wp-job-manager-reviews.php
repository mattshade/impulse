<?php
/*
 * Plugin Name: Reviews for WP Job Manager
 * Plugin URI: https://astoundify.com/downloads/wp-job-manager-reviews/
 * Description: Leave reviews for listings in WP Job Manager. Define review categories and choose the number of stars available.
 * Version: 2.1.0
 * Author: Astoundify
 * Author URI: https://astoundify.com
 * Text Domain: wp-job-manager-reviews
 * Domain Path: /languages
 *
 * @package Reviews
 * @category Core
 * @author Astoundify
**/

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class WP_Job_Manager_Reviews.
 *
 * Main WPJMR class initializes the plugin.
 *
 * @class     WP_Job_Manager_Reviews
 * @version   1.0.0
 * @author    Astoundify
 */
class WP_Job_Manager_Reviews {

	/**
	 * Instace of WP_Job_Manager_Reviews.
	 *
	 * @since 1.0.0
	 * @access private
	 * @var object $instance The instance of WPJMR.
	 */
	private static $instance;

	/**
	 * Plugin file.
	 *
	 * @since 1.0.0
	 * @var string $file Plugin file path.
	 */
	public $file = __FILE__;

	/**
	 * Construct.
	 *
	 * Initialize the class and plugin.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		$this->plugin_url = trailingslashit( plugin_dir_url( __FILE__ ) );
		$this->plugin_dir = plugin_dir_path( __FILE__ );
		$this->init();
	}

	/**
	 * Instace.
	 *
	 * An global instance of the class. Used to retrieve the instance
	 * to use on other files/plugins/themes.
	 *
	 * @since 1.0.0
	 * @return object Instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}


	/**
	 * Initialize plugin.
	 * Load all file and classes.
	 *
	 * @since 1.0.0
	 */
	public function init() {

		// Load Plugin Translation.
		load_plugin_textdomain( dirname( plugin_basename( __FILE__ ) ), false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

		// Bail if WPJM not active.
		if ( ! class_exists( 'WP_Job_Manager' ) ) {
			return;
		}

		// Functions.
		require_once( $this->plugin_dir . 'includes/functions.php' );

		/* === CLASSES === */

		// Review Form.
		require_once( $this->plugin_dir . 'includes/class-wpjmr-form.php' );
		$this->form = new WPJMR_Form();

		// Submit Review.
		require_once( $this->plugin_dir . 'includes/class-wpjmr-submit.php' );
		$this->submit = new WPJMR_Submit();

		// Display Review.
		require_once( $this->plugin_dir . 'includes/class-wpjmr-display.php' );
		$this->display = new WPJMR_Display();

		// Edit Review.
		require_once( $this->plugin_dir . 'includes/class-wpjmr-edit.php' );
		$this->edit = new WPJMR_Edit();

		// Post Edit Screen.
		require_once( $this->plugin_dir . 'includes/class-wpjmr-post-edit.php' );
		$this->post_edit = new WPJMR_Post_Edit();

		// Shortcode [review_stars], [review_average], [review_count], & [review_dashboard].
		require_once( $this->plugin_dir . 'includes/class-wpjmr-shortcodes.php' );
		$this->shortcodes = new WPJMR_Shortcodes();

		/* === DEPRECATED === */

		require_once( $this->plugin_dir . 'includes/class-wpjmr-deprecated.php' );
		$this->listing = new WPJMR_Listing();
		$this->review  = new WPJMR_Review();
		$this->reviews = new WPJMR_Reviews();

		/* === SETTINGS === */

		// Settings.
		if ( is_admin() ) {
			require_once( $this->plugin_dir . 'includes/class-wpjmr-settings.php' );
			$this->settings = new WPJMR_Settings();
		}

		/* === INTEGRATIONS === */

		// Listing Payments/Paid Listing.
		if ( class_exists( 'WooCommerce' ) && ( function_exists( 'wp_job_manager_wcpl_init' ) || defined( 'ASTOUNDIFY_WPJMLP_PLUGIN' ) ) ) {
			require_once( $this->plugin_dir . 'includes/integrations/class-wpjmr-listing-payments.php' );
			new WPJMR_Listing_Payments();
		}

		// WPJM Products.
		if ( class_exists( 'WooCommerce' ) && class_exists( 'WP_Job_Manager_Products' ) ) {
			require_once( $this->plugin_dir . 'includes/integrations/class-wpjmr-products.php' );
			new WPJMR_Products();
		}

		// Jetpack (Comments).
		require_once( $this->plugin_dir . 'includes/integrations/jetpack.php' );

		// Polylang.
		if ( function_exists( 'pll_register_string' ) ) {
			require_once( $this->plugin_dir . 'includes/integrations/class-wpjmr-polylang.php' );
			new WPJMR_Polylang();
		}

		// Add comment support fo job listing.
		add_action( 'init', array( $this, 'add_comments_support' ) );

		// Load Scripts.
		add_action( 'wp_enqueue_scripts', array( $this, 'wpjmr_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'wpjmr_admin_enqueue_scripts' ) );
	}

	/**
	 * Enable Listing Comments
	 *
	 * @since 1.11.0
	 */
	public function add_comments_support() {
		add_post_type_support( 'job_listing', 'comments' );
	}

	/**
	 * Enqueue scripts.
	 *
	 * Enqueue all style en javascripts.
	 *
	 * @since 1.0.0
	 */
	public function wpjmr_enqueue_scripts() {
		// General stylesheet.
		wp_enqueue_style( 'wp-job-manager-reviews', plugins_url( 'assets/css/wp-job-manager-reviews.css', __FILE__ ), array( 'dashicons' ) );

		// Javascript.
		wp_enqueue_script( 'wp-job-manager-reviews-js', plugins_url( 'assets/js/wp-job-manager-reviews.js', __FILE__ ), array( 'jquery' ) );
	}

	/**
	 * Admin scripts.
	 *
	 * @since 2.0.0
	 */
	public function wpjmr_admin_enqueue_scripts( $hook_suffix ) {
		global $post_type;
		if ( in_array( $hook_suffix, array( 'comment.php', 'edit-comments.php' ) ) || 'post.php' === $hook_suffix && 'job_listing' === $post_type ) {
			wp_enqueue_style( 'wpjmr-gallery-admin', $this->plugin_url . 'assets/css/wp-job-manager-reviews-gallery-admin.css', array() );
		}
	}

	/* === DEPRECATED FUNCTIONS === */

	/**
	 * Review categories (Deprecated).
	 *
	 * The default review categories. Can be extended via the options page or filter.
	 *
	 * @since 1.0.0
	 * @deprecated 2.0.0
	 *
	 * @return array List of review categories to display.
	 */
	public function wpjmr_get_review_categories() {
		_deprecated_function( __FUNCTION__, '2.0.0', 'wpjmr_get_categories' );
		return wpjmr_get_categories();
	}

	/**
	 * Return stars (Deprecated)
	 *
	 * Return the number of stars used to display. Default is 5;
	 *
	 * @since 1.0.0
	 * @deprecated 2.0.0
	 *
	 * @return int Number of stars.
	 */
	public function wpjmr_get_count_stars() {
		_deprecated_function( __FUNCTION__, '2.0.0', 'wpjmr_get_max_stars' );
		return wpjmr_get_max_stars();
	}

}


/**
 * Load the plugin updater.
 *
 * @since 1.8.0
 */
function wp_job_manager_reviews_updater() {
	require_once( dirname( __FILE__ ) . '/vendor/astoundify/plugin-updater/astoundify-pluginupdater.php' );

	new Astoundify_PluginUpdater( __FILE__ );

	if ( defined( 'JOB_MANAGER_VERSION' ) ) {
		new Astoundify_PluginUpdater_Integration_WPJobManager( __FILE__ );
	}
}

// Load updater on admin init.
add_action( 'admin_init', 'wp_job_manager_reviews_updater', 9 );


/**
 * The main function responsible for returning the WP_Job_Manager_Reviews object.
 *
 * Use this function like you would a global variable, except without needing to declare the global.
 *
 * @since 1.0.0
 *
 * @return object WP_Job_Manager_Reviews class object.
 */
function wpjmr() {
	return WP_Job_Manager_Reviews::instance();
}

// Load plugin instance on plugins loaded.
add_action( 'plugins_loaded', 'wpjmr' );
