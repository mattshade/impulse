<?php do_action( 'wpjmf_favorite_form_logged_out_before' ); ?>

<div class="job-manager-form wp-job-manager-favorites-form wp-job-manager-favorites-form--<?php echo is_single() ? 'single' : 'archive'; ?>">

	<div class="favorite-action wp-job-manager-favorites-action">

		<a class="popup-trigger-ajax wp-job-manager-favorites-status" href="<?php echo apply_filters( 'wpjmf_form_login_url', wp_login_url( get_permalink() ) ); ?>">
			<span class="screen-reader-text"><?php printf( __( 'Login to favorite this %s', 'listify' ), $post_type->labels->singular_name ); ?></span>

			<?php $count = wpjmf_favorite_count( $post->ID ); ?>

			<span class="wp-job-manager-favorites-count wp-job-manager-favorites-count--<?php echo is_single() ? 'single' : 'archive'; ?>">
				<?php if ( is_singular( 'job_listing' ) ) : ?>
					<?php printf( _n( '%d Favorite', '%d Favorites', $count, 'listify' ), absint( $count ) ); ?>
				<?php else : ?>
					<?php echo absint( $count ); ?>
				<?php endif; ?>
			</span>
		</a>

	</div>

</div>

<?php do_action( 'wpjmf_favorite_form_logged_out_after' ); ?>
