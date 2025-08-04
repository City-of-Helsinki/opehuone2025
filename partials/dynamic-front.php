<div class="opehuone-content-container">
	<?php get_template_part( 'partials/error-notifications' ); ?>
	<div class="opehuone-grid">
		<main>
			<?php
			get_template_part( 'partials/components/dock' );
			get_template_part( 'partials/front-page-news' );
			get_template_part( 'partials/empty' );
			get_template_part( 'partials/front-page-training' );
			?>
		</main>
		<aside class="sidebar-boxes">
			<?php
			get_template_part( 'partials/sidebar/intra-box' );
			get_template_part( 'partials/sidebar/date-box' );
			get_template_part( 'partials/sidebar/links-box' );
			get_template_part( 'partials/sidebar/favorites-box' );

            // Concentration related components
            get_template_part( 'partials/sidebar/break-corner-box' );
            get_template_part( 'partials/audios' );
            get_template_part( 'partials/components/concentration' );
			?>
		</aside>
	</div>
	<?php get_template_part( 'partials/empty' ); ?>
</div>

