<div class="opehuone-content-container">
	<?php get_template_part( 'partials/error-notifications' ); ?>
	<div class="opehuone-grid">
		<main>
			<?php
			get_template_part( 'partials/front-page-news' );
			?>
		</main>
		<aside class="sidebar-boxes">
			<?php
			get_template_part( 'partials/sidebar/intra-box' );
			get_template_part( 'partials/sidebar/date-box' );
			get_template_part( 'partials/sidebar/links-box' );
			get_template_part( 'partials/sidebar/favorites-box' );
			?>
		</aside>
	</div>
</div>

