<article class="content">
	<div class="content__container hds-container">
		<?php get_template_part( 'partials/breadcrumbs' ); ?>
		<div class="opehuone-grid">
			<div>
				<?php the_post_thumbnail( 'large', [ 'class' => 'single-post__featured-image' ] ); ?>
				<h1 class="sidemenu-page-title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</article>
