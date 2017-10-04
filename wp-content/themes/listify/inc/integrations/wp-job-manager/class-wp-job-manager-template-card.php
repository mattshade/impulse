<?php
/**
 * A listing result card.
 *
 * Choose what to output and output content for the `content-job_listing.php`
 * template file. 
 *
 * @since 1.8.0
 *
 * @see Listify_WP_Job_Manager_Map_Template
 * @package Listify
 */
class Listify_WP_Job_Manager_Template_Card {

	/**
	 * WP Job Manager templating class.
	 *
	 * Instead of moving callbacks to this class they need to remain
	 * in their current class until a better backwards compatible
	 * setup can be added. They are also used in more than cards.
	 *
	 * Most callbacks in Listify_WP_Job_Manager_Template that relate to cards
	 * have a mirrored method in this class that checks if it should be displayed
	 * based on the customizer setting.
	 *
	 * @type object
	 */
	public static $template;

	/**
	 * The selected display style.
	 * @type string
	 */
	public static $style;

	public function __construct() {
		add_action( 'after_setup_theme', array( __CLASS__, 'template_tags' ) );
	}

	/**
	 * Hook in to WordPress
	 *
	 * @since 1.8.0
	 *
	 * @return void
	 */
	public static function template_tags() {
		add_action( 'listify-listing-card-before', array( __CLASS__, 'add_the_card_class' ) );
		add_action( 'listify-listing-card-after', array( __CLASS__, 'remove_the_card_class' ) );

		if ( 'default' == self::get_style() ) {
			add_action( 'listify_content_job_listing_meta', array( __CLASS__, 'the_title' ), 15 );
			add_action( 'listify_content_job_listing_meta', array( __CLASS__, 'the_location' ), 20 );
			add_action( 'listify_content_job_listing_meta', array( __CLASS__, 'the_phone' ), 25 );
			// add_action( 'listify_content_job_listing_meta', array( __CLASS__, 'the_price' ), 30 );
			// add_action( 'listify_content_job_listing_meta', array( __CLASS__, 'the_attribute' ), 35 );

			add_action( 'listify_content_job_listing_footer', array( __CLASS__, 'the_rating' ), 5 );
			add_action( 'listify_content_job_listing_footer', array( __CLASS__, 'the_secondary_image' ), 10 );
		} else if ( 'style-1' == self::get_style() ) {
			add_action( 'listify_content_job_listing_footer', array( __CLASS__, 'the_secondary_image' ), 2 );
			add_action( 'listify_content_job_listing_footer', array( __CLASS__, 'the_title' ), 15 );
			add_action( 'listify_content_job_listing_footer', array( __CLASS__, 'the_location' ), 20 );
			add_action( 'listify_content_job_listing_footer', array( __CLASS__, 'the_phone' ), 25 );
			// add_action( 'listify_content_job_listing_footer', array( __CLASS__, 'the_price' ), 35 );
			// add_action( 'listify_content_job_listing_footer', array( __CLASS__, 'the_attribute' ), 40 );
			add_action( 'listify_content_job_listing_footer', array( __CLASS__, 'the_rating' ), 45 );
		}

		add_action( 'listify_content_job_listing_meta', array( __CLASS__, 'the_featured_badge' ), 5 );
	}

	/**
	 * Add the extra card class added to a listing data item.
	 *
	 * @since 1.8.0
	 */
	public static function add_the_card_class() {
		add_filter( 'listify_job_listing_data', array( __CLASS__, 'the_card_class' ) );
	}

	/**
	 * Remove the extra card class added to a listing data item.
	 *
	 * @since 1.8.0
	 */
	public static function remove_the_card_class() {
		remove_filter( 'listify_job_listing_data', array( __CLASS__, 'the_card_class' ) );
	}

	/**
	 * Get WP Job Manager template object.
	 *
	 * This object contains callbacks for outputting the card data.
	 *
	 * @since 1.8.0
	 *
	 * @return object $template
	 */
	public static function get_template() {
		if ( ! self::$template ) {
			global $listify_job_manager;

			self::$template = $listify_job_manager->template;
		}

		return self::$template;
	}

	/**
	 * Determine the class for the card grid.
	 *
	 * @since 1.8.0
	 * @param string $data
	 * @return string $data
	 */
	public static function the_card_class( $data ) {
		$classes = array();
		$template = self::get_template();

		if ( ! apply_filters( 'listify_listings_display_style_switcher', false ) ) {
			$style = 'grid';
		} else {
			$style = $template->get_archive_display_style();

			if ( is_author() || isset( $wp_query->query[ 'is_author' ] ) ) {
				$style = 'list';
			}

			if ( is_front_page() ) {
				$style = 'grid';
			}
		}

		$classes[] = $template->get_grid_columns($style);
		$classes[] = 'style-' . $style;

		return str_replace( 'class="', 'class="' . implode( ' ', $classes ) . ' ', $data );
	}

	/**
	 * Get the selected display style.
	 *
	 * @since 1.8.0
	 *
	 * @return string $style
	 */
	public static function get_style() {
		return 'default';
		// return get_theme_mod( 'listing-card-style', 'default' );
	}

	/**
	 * Maybe display title.
	 *
	 * @since Listify 1.8.0
	 */
	public static function the_title() {
		if ( ! get_theme_mod( 'listing-card-display-title', true ) ) {
			return;
		}

		$template = self::get_template();
		$template->the_title();
	}

	/**
	 * Maybe display location.
	 *
	 * @since Listify 1.8.0
	 */
	public static function the_location() {
		if ( ! get_theme_mod( 'listing-card-display-location', true ) ) {
			return;
		}

		$template = self::get_template();
		$template->the_location();
	}

	/**
	 * Maybe display phone.
	 *
	 * @since Listify 1.8.0
	 */
	public static function the_phone() {
		if ( ! get_theme_mod( 'listing-card-display-phone', true ) ) {
			return;
		}

		$template = self::get_template();
		$template->the_phone();
	}

	/**
	 * Maybe display the rating.
	 *
	 * @since Listify 1.8.0
	 */
	public static function the_rating() {
		if ( ! get_theme_mod( 'listing-card-display-rating', true ) ) {
			return;
		}

		do_action( 'listify_output_rating', __CLASS__ );
	}

	/**
	 * Maybe display secondary image.
	 *
	 * @since Listify 1.8.0
	 */
	public static function the_secondary_image() {
		if ( ! get_theme_mod( 'listing-card-display-secondary-image', true ) ) {
			return;
		}

		$template = self::get_template();
		$template->the_company_image();
	}

	/**
	 * Maybe display secondary image.
	 *
	 * @since Listify 1.8.0
	 */
	public static function the_featured_badge() {
		if ( 'badge' != get_theme_mod( 'listing-archive-feature-style', 'outline' ) ) {
			return;
		}

		$template = self::get_template();
		$template->the_featured_badge();
	}

	/**
	 * Maybe display price.
	 *
	 * @since Listify 1.8.0
	 */
	public static function the_price() {
		if ( ! listify_has_integration( 'wp-job-manager-products' ) ) {
			return;
		}

		if ( ! get_theme_mod( 'listing-card-display-price', true ) ) {
			return;
		}

		global $listify_job_manager_products;

		$listify_job_manager_products->the_price();
	}

	/**
	 * Maybe display a resource.
	 *
	 * @since Listify 1.8.0
	 */
	public static function the_attribute() {
		if ( ! listify_has_integration( 'wp-job-manager-products' ) ) {
			return;
		}

		if ( ! get_theme_mod( 'listing-card-display-booking-attribute', true ) ) {
			return;
		}

		global $listify_job_manager_products;

		$listify_job_manager_products->the_attribute();
	}

}

new Listify_WP_Job_Manager_Template_Card();
