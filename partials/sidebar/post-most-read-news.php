<div class="b-sidebar-news-lifts">
	<h3 class="b-sidebar-news-lifts__title">
		<?php esc_html_e( 'Luetuimmat uutiset (tähän joku logiikka, nyt antaa vain viimeisimmät', 'helsinki-universal' ); ?>
	</h3>
	<ul class="b-sidebar-news-lift__list">
		<?php
		$args = [
			'post_type'           => 'post',
			'posts_per_page'      => 5,
			'ignore_sticky_posts' => 1,
			'post__not_in'        => [ get_the_ID() ],
		];

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();

				$block_args = [
					'date'  => get_the_date(),
					'title' => get_the_title(),
					'url'   => get_the_permalink(),
				];

				get_template_part( 'partials/template-blocks/b-sidebar-news-lift', '', $block_args );
			}
		}

		wp_reset_postdata();
		?>
	</ul>
</div>
