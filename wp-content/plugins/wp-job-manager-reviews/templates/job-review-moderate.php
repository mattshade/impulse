<?php
/**
 * Shortcode Review Moderation Dashboard [review_dashboard].
 *
 * @since 1.0.0
 * @version 1.0.0
 *
 * @vars object $reviews       WP_Comment object.
 * @vars int    $max_num_pages Max num pages.
 */
?>

<div id="job-manager-review-moderate-board">

	<p><?php _e( 'Moderate your reviews below.', 'wp-job-manager-reviews' ); ?></p>

	<?php wpjmr_print_dashboard_notices(); // Display notices. ?>

	<table class="job-manager-reviews">

		<thead>
			<tr>
				<th class="" style="width: 50%;"><?php _e( 'Review', 'wp-job-manager-reviews' ); ?></th>
				<th class="" style="width: 15%;"><?php _e( 'Author', 'wp-job-manager-reviews' ); ?></th>
				<th class="" style="width: 20%;"><?php _e( 'Ratings', 'wp-job-manager-reviews' ); ?></th>
				<th class="" style="width: 25%;"><?php _e( 'Actions', 'wp-job-manager-reviews' ); ?></th>
			</tr>
		</thead>

		<tbody>
			<?php if ( ! $reviews ) : ?>
				<tr>
					<td colspan="6"><?php _e( 'There are currently no reviews found for any of your listings.', 'wp-job-manager-reviews' ); ?></td>
				</tr>
			<?php else : ?>
				<?php foreach ( $reviews as $review ) : ?>

					<?php
					// Vars:
					$actions = wpjmr_dashboard_actions( true ); // Get active actions.
					$title = ! empty( $review->post_title ) ? $review->post_title : __( '(no title)', 'wp-job-manager-reviews' );
					$content = get_comment_text( $review->comment_ID );

					// Get status and unset unneeded actions.
					$status = '';
					if ( '0' == $review->comment_approved ) {
						$status = __( 'Unapproved', 'wp-job-manager-reviews' );
						unset( $actions['unapprove'] );
					} elseif ( '1' == $review->comment_approved ) {
						$status = __( 'Approved', 'wp-job-manager-reviews' );
						unset( $actions['approve'] );
					} elseif ( 'spam' == $review->comment_approved ) {
						$status = __( 'Spam', 'wp-job-manager-reviews' );
						unset( $actions[ $approve ] );
						unset( $actions['spam'] );
					} elseif ( 'trash' == $review->comment_approved ) {
						$status = __( 'Deleted', 'wp-job-manager-reviews' );
						unset( $actions['trash'] );
					}
					?>

					<tr class="wp-job-manger-reviews-status-<?php echo $review->comment_approved; ?>">

						<td>
							<div class="review-content">
								<?php echo wp_kses_post( $content ); ?>
							</div><!-- .review-content -->

							<div class='review-content-listing'>
								<strong><?php echo sprintf( __( 'On listing %s', 'wp-job-manager-reviews' ), '<a href="' . get_permalink( $review->comment_post_ID ) . '">' . $title . '</a>' ); ?></strong>
							</div><!-- .review-content-listing -->
						</td>

						<td>
							<?php echo $review->comment_author; ?>
						</td>

						<td>
							<div class="wpjmr-list-reviews">
								<?php echo wpjmr_review_get_stars( $review->comment_ID ); ?>
							</div><!-- .wpjmr-list-reviews -->
						</td>

						<td>
							<div class="review-action-status">
								<strong><?php echo $status; ?></strong>
							</div><!-- .review-action-status -->

							<div class="job-dashboard-actions">

								<?php foreach( $actions as $action => $label ) : ?>
									<div>

										<a class="review-action review-action-<?php echo esc_attr( $action ); ?>" href="<?php echo esc_url( wp_nonce_url( add_query_arg( array( 'c' => $review->comment_ID, 'action' => $action ) ), 'moderate_comment', 'moderate_nonce' ) ); ?>">
											<?php echo wpjmr_get_svg( $action ); ?>&nbsp;<?php echo esc_html( $label ); ?>
										</a><!-- .review-action -->

									</div>
								<?php endforeach; ?>

							</div><!-- .job-dashboard-actions -->

						</td>
					</tr>
				<?php endforeach; // End $reviews as $review. ?>

			<?php endif; // End $reviews exists. ?>
		</tbody>

	</table><!-- .job-manager-reviews -->

	<?php get_job_manager_template( 'pagination.php', array( 'max_num_pages' => $max_num_pages ) ); ?>

</div><!-- .job-manager-review-moderate-board -->
