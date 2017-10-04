<?php
/**
 * WP Job Manager - Favorites: Logged in form.
 * 
 * @since 1.10.0
 */

$count = wpjmf_favorite_count( $post->ID ); ?>

<?php do_action( 'wpjmf_favorite_form_before' ); ?>

<form method="post" action="" class="job-manager-form wp-job-manager-favorites-form wp-job-manager-favorites-form--<?php echo is_single() ? 'single' : 'archive'; ?> <?php echo esc_attr( $is_favorited ? 'favorited' : 'not-favorited' );?>">

	<div class="favorite-action wp-job-manager-favorites-action">

		<?php if ( $is_favorited ) : ?>
			<a class="wp-job-manager-favorites-remove" href="<?php echo wp_nonce_url( add_query_arg( 'remove_favorite', absint( $post->ID ), get_permalink( $post->ID ) ), 'remove_favorite' ); ?>"><?php _e( 'Remove Favorite', 'listify' ); ?></a> 
		<?php endif; ?>

		<a class="wp-job-manager-favorites-status <?php if ( $is_favorited ) : ?>wp-job-manager-favorites-status--favorited<?php endif; ?>" href="#">
			<span class="wp-job-manager-favorites-count wp-job-manager-favorites-count--<?php echo is_single() ? 'single' : 'archive'; ?>">
				<?php printf( _n( '<span>%d</span> <em>Favorite</em>', '<span>%d</span> <em>Favorites</em>', $count, 'listify' ), absint( $count ) ); ?>
			</span>
		</a>

	</div>

	<div class="wp-job-manager-favorites-details">

		<p>
			<label for="favorite_notes"><?php _e( 'Notes:', 'listify' ); ?></label>
			<textarea name="favorite_notes" id="favorite_notes" cols="25" rows="3"><?php echo esc_textarea( $note ); ?></textarea>
		</p>

		<p>
			<?php wp_nonce_field( 'update_favorite' ); ?>
			<input type="hidden" name="favorite_post_id" value="<?php echo absint( $post->ID ); ?>" />
			<input type="submit" class="add-favorite wp-job-manager-favorites-add" name="submit_favorite" value="<?php echo strip_tags( __( 'Add Favorite', 'listify' ) ); ?>" />
			<input type="submit" class="update-favorite wp-job-manager-favorites-update" name="update_favorite" value="<?php echo strip_tags( __( 'Update Favorite', 'listify' ) ); ?>" />
			<span class="updated-favorite wp-job-manager-favorites-updated" style="display:none;"><?php _e( 'Favorite Updated', 'listify' ); ?></span>
		</p>
	</div>

</form>

<?php do_action( 'wpjmf_favorite_form_after' ); ?>
