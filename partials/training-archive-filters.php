<h2 class="training-archive__section-title">
	<?php esc_html_e( 'Hae koulutuksia', 'helsinki-universal' ); ?>
</h2>
<form class="training-archive-filtering">
	<div class="training-archive__filters">
		<?php
		$filters = [
			[
				'name'     => esc_html__( 'Opetusaste', 'helsinki-universal' ),
				'taxonomy' => 'cornerlabels',
			],
			[
				'name'     => esc_html__( 'Koulutusteema', 'helsinki-universal' ),
				'taxonomy' => 'training_theme',
			],
		];

		foreach ( $filters as $filter ) {
			?>
			<div class="training-archive__single-filter">
				<label for="training-archive-<?php echo esc_attr( $filter['taxonomy'] ); ?>"
					   class="training-archive__filter-label">
					<?php echo esc_html( $filter['name'] ); ?>
				</label>
				<div class="training-archive__select-filter-wrapper">
					<select class="training-archive__select-filter"
							id="training-archive-<?php echo esc_attr( $filter['taxonomy'] ); ?>"
							name="<?php echo esc_attr( $filter['taxonomy'] ); ?>">
						<option value=""><?php esc_html_e( 'Kaikki', 'helsinki-universal' ); ?></option>
						<?php
						$terms = get_terms( [
							'taxonomy'   => $filter['taxonomy'],
							'hide_empty' => false,
						] );

						if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
							foreach ( $terms as $term ) {
								printf(
									'<option value="%d">%s</option>',
									esc_attr( $term->term_id ),
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
		<button type="submit" class="training-archive__filters-submit">
			<?php esc_html_e( 'Hae', 'helsinki-universal' ); ?>
		</button>
	</div>
</form>
