<div class="front-page-posts-filter">
	<form class="front-page-posts-filter__posts-form">
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
							   value="<?php echo esc_attr( $term->term_id ); ?>">
						<?php echo esc_html( $term->name ); ?>
					</label>
					<?php
				}
			}
			?>
		</div>
	</form>
</div>
