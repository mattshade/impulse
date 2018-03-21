<?php
/**
 * Register Plugin Shortcodes.
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
 * Class WPJMR_Shortcodes
 * Handle all reviews.
 *
 * @since 1.0.0
 */
class WPJMR_Shortcodes {

	/**
	 * Construct.
	 * Initialize this class including hooks.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		// Shortcode [review_stars].
		add_shortcode( 'review_stars', array( $this, 'shortcode_review_stars' ) );

		// Shortcode [review_average].
		add_shortcode( 'review_average', array( $this, 'shortcode_review_average' ) );

		// Shortcode [review_count].
		add_shortcode( 'review_count', array( $this, 'shortcode_review_count' ) );

		// Shortcode Review Moderation Dashboard [review_dashboard].
		add_shortcode( 'review_dashboard', array( $this, 'shortcode_review_dashboard' ) );

		// On review moderate action.
		if ( is_user_logged_in() && isset( $_GET['c'], $_GET['action'], $_GET['moderate_nonce'] ) && $_GET['c'] && $_GET['action'] && $_GET['moderate_nonce'] ) {
			add_action( 'init', array( $this, 'moderate_comment_action' ) ); // Need to be in init hook.
		}
	}


	/**
	 * [review_stars].
	 *
	 * A shortcode for the review stars..
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Attributes given in the shortcode.
	 * @return string Shortcode output.
	 */
	public function shortcode_review_stars( $atts = array() ) {
		extract( shortcode_atts( array(
			'post_id' => get_the_ID(),
		), $atts ) );

		if ( ! $post_id ) {
			return;
		}

		return '<span class="review-stars">' . wpjmr_reviews_get_stars( $post_id ) . '</span>';
	}


	/**
	 * [review_average].
	 *
	 * A shortcode for the review average.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Attributes given in the shortcode.
	 * @return string Shortcode output.
	 */
	public function shortcode_review_average( $atts = array() ) {
		extract( shortcode_atts( array(
			'post_id' => get_the_ID(),
		), $atts ) );

		if ( ! $post_id ) {
			return;
		}

		return '<span class="review-average">' . wpjmr_get_reviews_average( $post_id ) . '</span>';
	}


	/**
	 * Shortcode [review_count].
	 *
	 * A shortcode for the review count.
	 *
	 * @since 1.0.0
	 *
	 * @param array $atts Attributes given in the shortcode.
	 * @return string Shortcode output.
	 */
	public function shortcode_review_count( $atts = array() ) {
		extract( shortcode_atts( array(
			'post_id' => get_the_ID(),
		), $atts ) );

		if ( ! $post_id ) {
			return;
		}

		return '<span class="review-count">' . wpjmr_get_reviews_count( $post_id ) . '</span>';
	}

	/**
	 * Review Dashboard.
	 * Shortcode to display the review moderate in the dashboard.
	 *
	 * @since 1.0.1
	 *
	 * @return string
	 */
	public function shortcode_review_dashboard() {

		$can_moderate = get_option( 'wpjmr_listing_authors_can_moderate', '0' );

		if ( ! $can_moderate ) {
			return wpautop( __( 'Review moderation has not been enabled.', 'wp-job-manager-reviews' ) );
		}

		if ( ! is_user_logged_in() ) {
			return wpautop( __( 'Please log in to moderate reviews.', 'wp-job-manager-reviews' ) );
		}

		// Get all user listings.
		add_filter( 'job_manager_get_listings', array( $this, 'wpjmr_listings_for_current_user' ), 10, 2 );
		$all_listings = get_job_listings();
		remove_filter( 'job_manager_get_listings', array( $this, 'wpjmr_listings_for_current_user' ) );

		// User listings:
		$listings = array();
		$listing_ids = $all_listings->have_posts() ? $all_listings->get_posts() : array();
		$listing_ids = apply_filters( 'wpjmr_current_user_listing_ids', $listing_ids );

		// Comment per page.
		$per_page = 10;

		// Comments query.
		$args = array(
			'post__in'              => $listing_ids ? $listing_ids : array( -1 ),
			'post_author'           => get_current_user_id(),
			'post_type'             => 'job_listing',
			'author__not_in'        => array( get_current_user_id() ),
			'status'                => 'all',
			'include_unapproved'    => true,
			'number'                => $per_page,
			'offset'                => get_query_var( 'paged' ) > 1 ? ( ( get_query_var( 'paged' ) * $per_page ) - $per_page ) : 0,
		);
		$reviews = get_comments( apply_filters( 'wpjmr_moderate_review_comments_args', $args ) );

		// Get total comments count.
		$comment_query = new WP_Comment_Query();
		$comment_count = $comment_query->query( array(
			'count'                 => true, // Only return the total number of comment.
			'post_author'           => get_current_user_id(),
			'post_type'             => 'job_listing',
			'status'                => 'all',
			'include_unapproved'    => true,
		) );

		ob_start();
		get_job_manager_template( 'job-review-moderate.php', array(
			'reviews'               => $reviews,
			'max_num_pages'         => round( $comment_count / $per_page ),
		), '', plugin_dir_path( wpjmr()->file ) . 'templates/' );
		return ob_get_clean();
	}

	/**
	 * Filter Listing Query for current user.
	 * 
	 * @since 1.11.0
	 *
	 * @param array $query_args WP Query Listing Args.
	 * @param  array $args Args for get_job_listings().
	 * @return array
	 */
	public function wpjmr_listings_for_current_user( $query_args, $args ) {
		$query_args['author']         = get_current_user_id();
		$query_args['disable_cache']  = time(); // disables WPJM cache.
		$query_args['posts_per_page'] = -1;
		$query_args['fields']         = 'ids';
		return apply_filters( 'wpjmr_listings_args_for_current_user', $query_args );
	}

	/**
	 * Moderate comment action.
	 * Triggered if a user clicked on a moderate action link on moderate dashboard shortcode.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function moderate_comment_action() {

		// Bail if nonce is not verified
		if ( ! wp_verify_nonce( $_GET['moderate_nonce'], 'moderate_comment' ) ) {
			return false;
		}

		// Get comment.
		$comment = get_comment( absint( $_GET['c'] ) );
		if ( ! $comment ) {
			return false;
		}

		// Get active actions.
		$actions = wpjmr_dashboard_actions( true );
		if ( ! array_key_exists( $_GET['action'], $actions ) ) {
			return false;
		}

		// Get listing.
		$post = get_post( $comment->comment_post_ID );

		// Bail if user is not the listing author.
		if ( get_current_user_id() != $post->post_author ) {
			return false;
		}

		// Hook.
		do_action( 'wpjmr_process_dashboard_comment_action', $_GET['action'], $comment, $post );

		// Report to admin.
		if ( 'report' === $_GET['action'] ) {
			$current_user = wp_get_current_user();

			// Notification to admin.
			$args = array(
				'to'       => get_bloginfo( 'admin_email' ),
				'reply_to' => $current_user->user_email,
				'message'  => sprintf( __( '%1$s requested a review moderation for Review #%2$s for %3$s', 'wp-job-manager-reviews' ), "{$current_user->display_name} ({$current_user->user_email})", $comment->comment_ID, $post->post_title ) . '<br/><br/>' . get_edit_comment_link( $comment ),
			);
			$sent = wpjmr_send_mail( $args );

			// Notification to user.
			if ( $sent ) {
				$args = array(
					'to'       => $current_user->user_email,
					'reply_to' => get_bloginfo( 'admin_email' ),
					'message'  => sprintf( __( 'Your review moderation request for %1$s was sent successfully.', 'wp-job-manager-reviews' ), "{$post->post_title} (Review #{$comment->comment_ID})" ),
				);
				wpjmr_send_mail( $args );

				// Notice.
				wpjmr_set_dashboard_notices( sprintf( __( 'Review #%1$d for %2$s reported to site admin.', 'wp-job-manager-reviews' ), $comment->comment_ID, $post->post_title ) );
			}

		} else { // Other Actions.

			// Action.
			$comment_approved = 0;
			if ( 'approve' === $_GET['action'] ) {
				$comment_approved = 1;
			} elseif ( 'unapprove' === $_GET['action'] ) {
				$comment_approved = 0;
			} elseif ( 'spam' === $_GET['action'] ) {
				$comment_approved = 'spam';
			} elseif ( 'trash' === $_GET['action'] ) {
				$comment_approved = 'trash';
			}

			// Update comments.
			$comment_args = array(
				'comment_ID'       => $comment->comment_ID,
				'comment_approved' => $comment_approved,
			);
			$updated = wp_update_comment( $comment_args );

			// Add updated notice.
			if ( $updated ) {
				wpjmr_set_dashboard_notices( sprintf( __( 'Review #%1$d for %2$s updated.', 'wp-job-manager-reviews' ), $comment->comment_ID, $post->post_title ) );
			}

		}

		// Redirect user back.
		wp_safe_redirect( esc_url( remove_query_arg( array( 'action', 'c', 'moderate_nonce' ) ) ) );
		exit;
	}

}
