<article class="content">
	<div class="hds-container opehuone-page opehuone-content-container">
		<?php get_template_part( 'partials/breadcrumbs' ); ?>
		<div class="opehuone-grid opehuone-grid--reversed">
			<aside>
				<?php get_template_part( 'partials/sidemenu' ); ?>
			</aside>
			<div>
				<?php the_post_thumbnail( 'large', [ 'class' => 'featured-image' ] ); ?>
				<h1 class="page-title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</article>
