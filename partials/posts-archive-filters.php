<h2 class="posts-archive__section-title">
	<?php esc_html_e( 'Hae uutisia suodattamalla', 'helsinki-universal' ); ?>
</h2>
<form class="posts-archive-filtering">
	<div class="posts-archive__filters">
		<?php
		$filters = [
			[
				'name'     => esc_html__( 'Koulutusaste', 'helsinki-universal' ),
				'taxonomy' => 'cornerlabels',
			],
			[
				'name'     => esc_html__( 'Kategoria', 'helsinki-universal' ),
				'taxonomy' => 'category',
			],
			[
				'name'     => esc_html__( 'Aihe', 'helsinki-universal' ),
				'taxonomy' => 'post_theme',
			],
		];

		foreach ( $filters as $filter ) {
            // Check if query parameter exists and select the correct option in the dropdowns
            $query_param = 'filter_' . $filter['taxonomy'];
            $selected_filter = isset( $_GET[ $query_param ] ) ? intval( $_GET[ $query_param ] ) : null;
			?>
			<div class="posts-archive__single-filter">
				<label for="posts-archive-<?php echo esc_attr( $filter['taxonomy'] ); ?>"
					   class="posts-archive__filter-label">
					<?php echo esc_html( $filter['name'] ); ?>
				</label>
				<div class="posts-archive__select-filter-wrapper">
					<select class="posts-archive__select-filter"
							id="posts-archive-<?php echo esc_attr( $filter['taxonomy'] ); ?>"
							name="<?php echo esc_attr( $filter['taxonomy'] ); ?>">
						<option value=""><?php esc_html_e( 'Kaikki', 'helsinki-universal' ); ?></option>
						<?php
						$terms = get_terms( [
							'taxonomy'   => $filter['taxonomy'],
							'hide_empty' => true,
						] );

						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
							foreach ( $terms as $term ) {
								printf(
                                    '<option value="%d"%s>%s</option>',
                                    esc_attr( $term->term_id ),
                                    selected( $term->term_id, $selected_filter, false ),
                                    esc_html( $term->name )
                                );
							}
						}
						?>
					</select>
				</div>
			</div>
			<?php
		}
		?>
		<button type="submit" class="posts-archive__filters-submit">
			<?php esc_html_e( 'Hae', 'helsinki-universal' ); ?>
		</button>
	</div>
</form>
