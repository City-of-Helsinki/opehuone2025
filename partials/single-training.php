<article class="content">
	<div class="content__container hds-container">
		<div class="opehuone-grid">
			<section>
				<p>MURUPOLKU TÄHÄN</p>
				<h1 class="single-post__title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</section>
			<aside>
				<div class="single-post__sidebar-training">
					<?php
					get_template_part( 'partials/sidebar/training-theme-tags' );
					get_template_part( 'partials/sidebar/training-cornerlabels' );
					?>
				</div>
			</aside>
		</div>
	</div>
</article>
