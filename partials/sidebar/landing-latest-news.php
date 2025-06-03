<?php
$news_category_term_id = get_field('news_category');
$news_title = get_field('news_title');

?>
<div class="b-sidebar-news-lifts">
	<?php if ( !empty( $news_title ) ): ?>
		<h3 class="b-sidebar-news-lifts__title">
			<?php echo esc_html( $news_title ); ?>
		</h3>
	<?php endif; ?>
	<ul class="b-sidebar-news-lift__list">
		<?php
		$args = array(
			'post_type'           => 'post',
			'posts_per_page'      => 5,
			'ignore_sticky_posts' => 1,
		);

		if ( !empty( $news_category_term_id ) ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $news_category_term_id,
				)
			);
		}

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$block_args = array(
					'date'  => get_the_date(),
					'title' => get_the_title(),
					'url'   => get_the_permalink(),
				);

				get_template_part( 'partials/template-blocks/b-sidebar-news-lift', '', $block_args );
			}
		}

		wp_reset_postdata();
		?>
	</ul>
</div>
