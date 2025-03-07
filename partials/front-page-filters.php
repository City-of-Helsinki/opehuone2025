<?php
$what_to_target = isset( $args['to_target'] ) ? $args['to_target'] : 'posts';
$cornerlabels   = Opehuone_user_settings_reader::get_user_settings_key( 'cornerlabels' );
?>
<div class="front-page-posts-filter">
	<form class="front-page-posts-filter__posts-form" data-target="<?php echo esc_attr( $what_to_target ); ?>"
		  id="front-page-filter-<?php echo esc_attr( $what_to_target ); ?>">
		<div class="front-page-posts-filter__checkboxes-row">
			<?php
			$terms = get_terms( [
				'taxonomy'   => 'cornerlabels',
				'hide_empty' => true,
			] );

			if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
				foreach ( $terms as $term ) {
					?>
					<label class="front-page-posts-filter__checkbox-label">
						<input type="checkbox" class="front-page-posts-filter__checkbox-input" name="cornerlabels[]"
							   value="<?php echo esc_attr( $term->term_id ); ?>" <?php echo in_array( $term->term_id, $cornerlabels ) ? ' checked' : ''; ?>>
						<?php echo esc_html( $term->name ); ?>
					</label>
					<?php
				}
			}
			?>
		</div>
	</form>
</div>
