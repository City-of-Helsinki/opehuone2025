<?php


?>
<article class="content">
	<div class="content__container hds-container">
		<div class="opehuone-grid opehuone-grid--reversed">
			<aside>
				<?php get_template_part( 'partials/sidemenu' ); ?>
			</aside>
			<div>
				<h1><?php the_title(); ?></h1>
				<?php
				the_post_thumbnail( 'large', [ 'class' => 'opehuone-thumbnail' ] );
				the_content();
				?>
			</div>
		</div>
	</div>
</article>
