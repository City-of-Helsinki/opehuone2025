<h2 class="posts-archive__section-title">
	<?php esc_html_e( 'Hae uutisia suodattamalla', 'helsinki-universal' ); ?>
</h2>
<p class="posts-archive__filters-ingress">
	<?php esc_html_e( 'Selaa uutisia aikajärjestyksessä tai hae uutisia oppiasteen, kategorian tai aiheen perusteella.', 'helsinki-universal' ); ?>
</p>
<form class="posts-archive-filtering">
	<div class="posts-archive__filters">
		<?php
		$filters = [
			[
				'name'     => esc_html__( 'Oppiaste', 'helsinki-universal' ),
				'taxonomy' => 'cornerlabels',
			],
			[
				'name'     => esc_html__( 'Kategoria', 'helsinki-universal' ),
				'taxonomy' => 'category',
			],
			[
				'name'     => esc_html__( 'Aihe', 'helsinki-universal' ),
				'taxonomy' => 'post_tag',
			],
		];

		foreach ( $filters as $filter ) {
			?>
			<div class="posts-archive__single-filter">
				<fieldset>
					<legend class="posts-archive__filter-label">
						<?php echo esc_html( $filter['name'] ); ?>
					</legend>
					<div class="posts-archive__select-filter-wrapper">
						<button class="checkbox-filter__filter-btn" aria-expanded="false"
								aria-label="<?php esc_attr_e( 'Näytä valinnat', 'helsinki-universal' ); ?>">
							<?php esc_html_e( 'Valitse', 'helsinki-universal' ); ?>
						</button>
						<div class="checkbox-filter__filter-dropdown">
							<div class="checkbox-filter__checkboxes-wrapper">
								<?php
								$terms = get_terms( [
									'taxonomy'   => $filter['taxonomy'],
									'hide_empty' => true,
								] );

								if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
									foreach ( $terms as $term ) {
										?>
										<label class="checkbox-filter__checkbox-label">
											<input type="checkbox" class="checkbox-filter__checkbox-input"
												   name="<?php echo esc_attr( $filter['taxonomy'] ); ?>[]"
												   value="<?php echo esc_attr( $term->term_id ); ?>">
											<?php echo esc_html( $term->name ); ?>
										</label>
										<?php
									}
								}
								?>
							</div>
							<button class="checkbox-filter__checkboxes-reset-btn">
								<?php esc_html_e( 'Tyhjennä valinnat', 'helsinki-universal' ); ?>
							</button>
						</div>
					</div>
				</fieldset>
			</div>
			<?php
		}
		?>
		<button type="submit" class="posts-archive__filters-submit">
			<?php esc_html_e( 'Hae', 'helsinki-universal' ); ?>
		</button>
	</div>
</form>
