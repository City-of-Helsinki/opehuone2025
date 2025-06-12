<article class="content" data-current-page-id="<?php echo get_the_ID(); ?>">
	<div class="content__container hds-container">
		<?php get_template_part( 'partials/breadcrumbs' ); ?>
		<div class="filters-container">
			<p>Suodata sisältöä oppiasteen mukaan</p>
			<?php get_template_part( 'partials/page-filters' ); ?>
		</div>
		<div class="opehuone-grid opehuone-grid--reversed">
			<aside>
				<?php get_template_part( 'partials/sidemenu' ); ?>
			</aside>
			<div>
				<?php the_post_thumbnail( 'large', [ 'class' => 'sidemenu-page-featured-image' ] ); ?>
				<h1 class="sidemenu-page-title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</article>
