<?php

use function \Opehuone\TemplateFunctions\get_top_monthly_posts;

?>
<div class="b-sidebar-news-lifts">
	<h3 class="b-sidebar-news-lifts__title">
		<?php esc_html_e( 'Kuukauden luetuimmat uutiset', 'helsinki-universal' ); ?>
	</h3>
	<ul class="b-sidebar-news-lift__list">
		<?php
		$query = get_top_monthly_posts();

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
