<?php
/**
 * Functions.
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
 * Get Review Categories.
 *
 * @since 2.0.0
 *
 * @return array
 */
function wpjmr_get_categories() {

	$default = array(
		__( 'Speed', 'wp-job-manager-reviews' ),
		__( 'Quality', 'wp-job-manager-reviews' ),
		__( 'Price', 'wp-job-manager-reviews' ),
	);
	$default = implode( PHP_EOL, $default ); // Default string.

	$categories = get_option( 'wpjmr_categories', $default ); // String.

	return explode( PHP_EOL, $categories ); // Array.
}

/**
 * Get Max Star Number.
 * Return the max number of stars used to display. Default is 5;
 *
 * @since 2.0.0
 *
 * @return int
 */
function wpjmr_get_max_stars() {
	$stars = get_option( 'wpjmr_star_count', 5 );
	return absint( apply_filters( 'wpjmr_count_stars', $stars ) );
}

/**
 * Get review (cached).
 *
 * @since 2.0.0
 *
 * @param int $post_id Listing ID.
 * @return array
 */
function wpjmr_get_reviews( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Bail if not job listing.
	if ( 'job_listing' !== get_post_type( $post_id ) ) {
		return array();
	}

	// Only if posts has comments.
	$comments = absint( get_comments_number( $post_id ) );
	if ( ! $comments ) {
		return array();
	}

	// Get reviews datas.
	$reviews = get_post_meta( $post_id, '_all_ratings', true );
	$reviews = is_array( $reviews ) ? $reviews : array();

	// Reviews can't be more than comments. That's wrong, reset.
	if ( count( $reviews ) > $comments ) {
		$reviews = array();
	}

	// Check and update data once a day.
	$today = date( "Ymd" ); // YYYYMMDD.
	$last_updated = get_post_meta( $post_id, '_wpjmr_last_updated', true );

	if ( intval( $today ) !== intval( $last_updated ) || ! $reviews ) {
		$reviews = wpjmr_get_reviews_db( $post_id );
		update_post_meta( $post_id, '_all_ratings', $reviews );
		update_post_meta( $post_id, '_wpjmr_last_updated', $today );
	}

	return $reviews;
}

/**
 * Get Reviews DB by Listing ID.
 *
 * @since 2.0.0
 *
 * @param int $post_id Post ID.
 * @return array
 */
function wpjmr_get_reviews_db( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Var.
	$reviews = array();

	// Bail if not job listing.
	if ( 'job_listing' !== get_post_type( $post_id ) ) {
		return $reviews;
	}

	// Get all first level comments.
	$args = array(
		'post_id'    => $post_id,
		'parent'     => 0,
		'status'     => 'approve',
		'fields'     => 'ids',
		'meta_query' => array(
			'relation'    => 'OR',
			array (
				'key'     => 'review_average',
				'compare' => 'EXISTS',
			),
			array ( // Listify rating.
				'key'     => 'rating',
				'compare' => 'EXISTS',
			),
		),
	);
	$comments = get_comments( $args );
	if ( ! $comments ) {
		return $reviews;
	}

	// Loop all comment and add if it's a review.
	foreach ( $comments as $comment_id ) {

		// Maybe migrate v.1 DB to v.2.
		wpjmr_maybe_migrate_data( $comment_id );

		// Get review average.
		$review_average = get_comment_meta( $comment_id, 'review_average', true );

		// Add reviews.
		$reviews[ $comment_id ] = $review_average;
	}

	return $reviews;
}

/**
 * Get reviews average of a listing.
 *
 * @since 2.0.0
 *
 * @param int $post_id Listing ID.
 * @return int
 */
function wpjmr_get_reviews_average( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Get review average.
	$review_average = wpjmr_sanitize_number( get_post_meta( $post_id, '_average_rating', true ) );

	// Try to update if not found.
	if ( ! $review_average ) {
		wpjmr_update_reviews_average( $post_id );
	}

	// Still no review average, return 0.
	if ( ! $review_average ) {
		return 0;
	}

	// In v.2.0.0 we round on output not input.
	return round( $review_average * 2, apply_filters( 'wpjmr_review_average_round', 1 ) ) / 2;
}

/**
 * Get reviews count of a listing.
 *
 * @since 2.0.0
 *
 * @param int $post_id Post ID. Optional, will use current loop ID.
 * @return int
 */
function wpjmr_get_reviews_count( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}
	$reviews = wpjmr_get_reviews( $post_id );
	return count( $reviews );
}

/**
 * Get listing star rating display.
 *
 * @since 2.0.0
 *
 * @param int $post_id Listing ID.
 * @return string Single listing rating HTML.
 */
function wpjmr_reviews_get_stars( $post_id = null ) {
	if ( ! $post_id ) {
		$post_id = get_the_ID();
	}

	// Get review average.
	$rating = wpjmr_get_reviews_average( $post_id );

	// Display stars based on total average ratings.
	$full_stars = floor( $rating );
	$half_stars = ceil( $rating - $full_stars );
	$empty_stars = wpjmr_get_max_stars() - $full_stars - $half_stars;
	ob_start();
?>

<span class="stars-rating wp-job-manager-star-listing-star-rating">
	<?php echo str_repeat( '<span class="dashicons dashicons-star-filled"></span>', $full_stars ); ?>
	<?php echo str_repeat( '<span class="dashicons dashicons-star-half"></span>', $half_stars ); ?>
	<?php echo str_repeat( '<span class="dashicons dashicons-star-empty"></span>', $empty_stars ); ?>
</span>

<?php
	return ob_get_clean();
}
$end_rating_data;
$rating_data;
/**
 * Get single review stars
 *
 * @since 2.0.0
 *
 * @param int $comment_id Comment ID.
 * @return string Single comment/review rating HTML.
 */
function wpjmr_review_get_stars( $comment_id ) {

	wpjmr_maybe_migrate_data( $comment_id );
	$ratings = get_comment_meta( $comment_id, 'review_stars', true );
	ob_start();

?>
	<div class='wpjmr-list-reviews'>

		<?php

		$newratings = json_encode($ratings, JSON_NUMERIC_CHECK);
		$newCleanRatings = preg_replace("/\r/", " ", $ratings);
		$newpoopratings = str_replace('\r', '', $newratings);

		//echo $newpoopratings;
		echo "<script type='text/javascript'> ratingsArray.push(". $newpoopratings .");</script>";
		 foreach ( $ratings as $category => $rating ) :
				$category = apply_filters( 'wpjmr_category_label', $category );
			?>
			<!-- <div class='stars-rating star-rating'> -->
				<!-- <div class='star-rating-title'><?php //echo esc_html( $category ); ?></div> -->
				<!-- <?php //for ( $i = 0; $i < wpjmr_get_max_stars(); $i++ ) : ?><span class="dashicons dashicons-star-<?php //echo $i < $rating ? 'filled' : 'empty'; ?>"></span><?php //endfor; ?></div> -->


			<?php

			//echo $category;
			//echo $rating;
			//$json  = sprintf( '<script type="application/ld+json">%s</script>', wp_json_encode( $this->json_ld( $category, $rating ) ) );
			//return $json;
			?>
			<?php //echo '<script type="application/json">' . $category . $rating . '</script>';
			//$rating_vars = array("category", "rating");
			$rating_data[$category] = $rating;
			//$result = compact("event", "nothing_here", $rating_vars);
			//$items = array();
//foreach($result as $ratings_results) {
 //$items[] = $ratings_results;
//}



?>
		<?php endforeach; ?>
<?php
// foreach($rating_data as $ratings_results) {
//  $end_rating_data[] = $rating_data;
//// }
//print_r(wp_json_encode($rating_data));

?>

	</div>
<?php
	return ob_get_clean();

}


/**
 * Update and recalculate review average of a listing.
 *
 * @since 2.0.0
 *
 * @param int $post_id Listing ID.
 * @return bool
 */
function wpjmr_update_reviews_average( $post_id ) {
	$total = 0;
	$reviews = wpjmr_get_reviews( $post_id );
	if ( ! $reviews ) {
		return wpjmr_sanitize_number( $total );
	}
	foreach ( $reviews as $review ) {
		$total += $review;
	}
	$average = wpjmr_sanitize_number( $total / count( $reviews ) );
	return update_post_meta( $post_id, '_average_rating', $average );
}

/**
 * Get single review average.
 *
 * @since 2.0.0
 *
 * @param int $comment_id Comment ID.
 * @return int
 */
function wpjmr_get_review_average( $comment_id ) {
	$average = get_comment_meta( $comment_id, 'review_average', true );
	return number_format( wpjmr_sanitize_number( $average ), 1, '.', ',' );
}

/**
 * Maybe Migrate Comment Data.
 * Migrate old comment data to new data. For back compat with v.1 rating data & listify rating data.
 *
 * For back-compat, old datas are not removed. You can see how to remove the data from commented codes below.
 * List of v.1 comment meta datas are:
 * - "star-rating-{num}" example, "star-rating-0", "star-rating-1", etc.
 * - "star-rating-{key}" example, "star-rating-speed", "star-rating-quality", etc.
 * - "review_categories"
 *
 * In v.2, all datas above is added in single comment meta "review_stars".
 *
 * @since 2.0.0
 *
 * @param int $comment_id Comment ID.
 * @return void
 */
function wpjmr_maybe_migrate_data( $comment_id ) {
	// Get stars.
	$stars = get_comment_meta( $comment_id, 'review_stars', true );
	if ( $stars ) { // Bail if already using new data schema.
		return;
	}

	// Get comment.
	$comment = get_comment( $comment_id );
	if ( 'job_listing' !== get_post_type( $comment->comment_post_ID ) || 0 !== intval( $comment->comment_parent ) ) {
		return;
	}

	// Review average.
	$review_average = get_comment_meta( $comment_id, 'review_average', true );

	// Migrate Listify rating data.
	$listify_review = get_comment_meta( $comment_id, 'rating', true );
	if ( $listify_review && ! $review_average ) {
		update_comment_meta( $comment_id, 'review_average', $listify_review );
		$categories = wpjmr_get_categories();
		$stars = array();
		foreach( $categories as $category ) {
			$stars[ $category ] = $listify_review;
		}
		update_comment_meta( $comment_id, 'review_stars', $stars );
		return; // Bail. End Listify review.
	}

	// Get categories.
	$categories = get_comment_meta( $comment_id, 'review_categories', true );
	if ( ! $categories || ! is_array( $categories ) ) {
		return;
	}

	// Format old funky data to new data structure.
	$stars = array();
	$r_1 = get_comment_meta( $comment_id, 'star-rating-' . sanitize_title( current( $categories ) ) );
	$r_2 = get_comment_meta( $comment_id, 'star-rating-0' );
	if ( $r_1 ) {
		foreach ( $categories as $category ) {
			$key = 'star-rating-' . sanitize_title( $category );
			$stars[ $category ] = get_comment_meta( $comment_id, $key, true );
			//delete_comment_meta( $comment_id, $key ); // Rating v.1 data.
		}
	} elseif ( $r_2 ) {
		foreach ( $categories as $index => $category ) {
			$key = 'star-rating-' . $index;
			$stars[ $category ] = get_comment_meta( $comment_id, $key, true );
			//delete_comment_meta( $comment_id, $key ); // Rating v.1 data.
		}
	}

	// Delete old categories.
	//delete_comment_meta( $comment_id, 'review_categories' ); // Rating v.1 data.

	// Update stars data with new structure.
	update_comment_meta( $comment_id, 'review_stars', $stars );

}

/**
 * Get SVG
 *
 * @since 2.0.0
 *
 * @param string $icon Icon name.
 * @return string
 */
function wpjmr_get_svg( $icon ) {
	$file =plugin_dir_path( wpjmr()->file ) . "assets/images/{$icon}.svg";

	if ( file_exists( $file ) ) {
		ob_start();
?>
<span class="wpjmr-icon"><?php require( $file ); ?></span>
<?php
		return trim( ob_get_clean() );
	}
	return false;
}

/**
 * Format Files Data.
 *
 * @since 2.0.0
 *
 * @param array $files $_FILES.
 * @return array
 */
function wpjmr_handle_uploads( $post_id, $comment_id ) {

	// Check if enabled.
	if ( ! get_option( 'wpjmr_allow_images', true ) ) {
		return;
	}

	// Get uploaded images data.
	if ( ! isset( $_FILES['wpjmr-gallery'] ) ) {
		return;
	}

	// Format multiple files into individual $_FILES data.
	$_files_gallery = $_FILES['wpjmr-gallery'];
	$files_data = array();
	if ( isset( $_files_gallery['name'] ) && is_array( $_files_gallery['name'] ) ) {
		$file_count = count( $_files_gallery['name'] );
		for ( $n = 0; $n < $file_count; $n++ ) {
			if( $_files_gallery['name'][$n] && $_files_gallery['type'][$n] && $_files_gallery['tmp_name'][$n] ){
				if( ! $_files_gallery['error'][$n] ){ // Check error.
					$type = wp_check_filetype( $_files_gallery['name'][$n] );

					// Only image allowed.
					if ( strpos( $type['type'], 'image' ) !== false ) {
						$files_data[] = array(
							'name'     => $_files_gallery['name'][$n],
							'type'     => $type['type'],
							'tmp_name' => $_files_gallery['tmp_name'][$n],
							'error'    => $_files_gallery['error'][$n],
							'size'     => filesize( $_files_gallery['tmp_name'][$n] ), // in byte.
						);
					}
				}
			}
		}
	} // end if().

	// Upload each file.
	foreach ( $files_data as $file_data ) {

		// Load WP Media.
		if ( ! function_exists( 'media_handle_upload' ) ) {
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			require_once( ABSPATH . 'wp-admin/includes/file.php' );
			require_once( ABSPATH . 'wp-admin/includes/media.php' );
		}

		// Set files data to upload.
		$_FILES['wpjmr-gallery'] = $file_data;
		$attachment_id = media_handle_upload( 'wpjmr-gallery', $post_id );

		// Track using attachment/post meta.
		update_post_meta( $attachment_id, 'wpjmr-gallery', $comment_id );

		// Track using comment meta (multiple).
		add_comment_meta( $comment_id, 'wpjmr-gallery', $attachment_id, false );
	}
}

/**
 * Helper Function to Send Email
 *
 * @since 2.0.0
 */
function wpjmr_send_mail( $args ){
	$sitename = strtolower( $_SERVER['SERVER_NAME'] );
	if ( substr( $sitename, 0, 4 ) == 'www.' ) {
		$sitename = substr( $sitename, 4 );
	}
	$args_default = array(
		'to'             => get_bloginfo( 'admin_email' ),
		'from'           => 'wordpress@' . $sitename,
		'from_name'      => esc_html( 'Reviews Notification', 'wp-job-manager-reviews' ),
		'reply_to'       => '',
		'subject'        => esc_html( 'Reviews Notification', 'wp-job-manager-reviews' ),
		'message'        => '',
		'content_type'   => 'text/html',
		'charset'        => get_bloginfo( 'charset' ),
	);
	$args = wp_parse_args( $args, $args_default );
	$args = apply_filters( 'wpjmr_send_mail_args', $args );

	$headers  = array(
		'From: "' . strip_tags( $args['from_name'] ) . '" <' . sanitize_email( $args['from'] ) . '>',
		"Reply-To: " . $args['reply_to'],
		"Content-type: " . $args['content_type'] . "; charset: " . $args['charset'],
	);

	return wp_mail( sanitize_email( $args['to'] ), esc_attr( $args['subject'] ), wp_kses_post( $args['message'] ), $headers );
}

/**
 * Moderate Actions.
 *
 * @since 2.0.0
 *
 * @param bool $active True to load only active actions.
 * @return array
 */
function wpjmr_dashboard_actions( $active = false ) {
	$actions = array(
		'approve'   => esc_html__( 'Approve', 'wp-job-manager-reviews' ),
		'unapprove' => esc_html__( 'Unapprove', 'wp-job-manager-reviews' ),
		'spam'      => esc_html__( 'Spam', 'wp-job-manager-reviews' ),
		'trash'     => esc_html__( 'Delete', 'wp-job-manager-reviews' ),
		'report'    => esc_html__( 'Report', 'wp-job-manager-reviews' ),
	);
	$actions = apply_filters( 'wpjmr_moderate_actions', $actions );

	// Unset inactive actions.
	if ( $active ) {
		$option = get_option( 'wpjmr_dashboard_actions' );
		$option = is_array( $option ) ? $option : array();
		foreach ( $actions as $action => $action_label ) {
			if ( ! in_array( $action, $option ) ) {
				unset( $actions[ $action ] );
			}
		}
	}

	return $actions;
}

/**
 * Get Dashboard Notices
 *
 * @since 2.0.0
 *
 * @param bool $clear True to also clear the transient data.
 */
function wpjmr_print_dashboard_notices( $clear = true ) {
	$name = get_current_user_id() . '_wpjmr_notices';
	$data = get_transient( $name );
	$data = is_array( $data) ?  $data : array();
	if ( $clear ) {
		delete_transient( $name );
	}
	if ( ! $data ) {
		return;
	}
	foreach( $data as $notice ) {
		printf( '<div class="wpjmr-notice">%s</div>', wpautop( wp_kses_post( $notice ) ) );
	}
}

/**
 * Comment Gallery Output
 *
 * @since 2.0.0
 *
 * @param int $comment_id Comment ID.
 * @return string
 */
function wpjmr_get_gallery( $comment_id ) {
	$gallery = get_comment_meta( $comment_id, 'wpjmr-gallery', false );
	if ( ! $gallery ) {
		return '';
	}
	ob_start();
?>
<div class="wpjmr-gallery">
	<?php echo do_shortcode( '[gallery ids="' . implode( ',', $gallery ) . '"]' ); ?>
</div>
<?php
	return apply_filters( 'wpjmr-gallery-output', ob_get_clean(), $comment_id, $gallery );
}

/**
 * Set Dashboard Notices.
 *
 * @since 2.0.0
 *
 * @param string $notice Notice to set.
 * @return bool
 */
function wpjmr_set_dashboard_notices( $notice ) {
	$name = get_current_user_id() . '_wpjmr_notices';
	$data = get_transient( $name );
	$data = is_array( $data) ?  $data : array();
	$data[] = $notice;
	return set_transient( $name, $data );
}

/**
 * Sanitize Number
 * Added because intval() round the return value,
 *
 * @since 2.1.0
 *
 * @param mixed $input Data to sanitize.
 * @return string
 */
function wpjmr_sanitize_number( $input ) {
	if ( is_numeric( $input ) ) {
		return $input + 0;
	}
	return 0;
}
