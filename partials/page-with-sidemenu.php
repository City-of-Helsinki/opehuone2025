<article class="content">
	<div class="content__container hds-container">
		<?php get_template_part( 'partials/breadcrumbs' ); ?>
		<div class="opehuone-grid opehuone-grid--reversed">
			<aside>
				<?php get_template_part( 'partials/sidemenu' ); ?>
			</aside>
			<div>
                <?php
                $cornerlabels = wp_get_post_terms( get_the_ID(), 'cornerlabels' );
                if ( ! empty( $cornerlabels ) && ! is_wp_error( $cornerlabels ) ) {
                    foreach ( $cornerlabels as $term ) {
                        echo '<span data-fdk-tags style="display: none;">' . esc_html( $term->name ) . '</span>';
                    }
                }
                ?>
				<?php the_post_thumbnail( 'large', [ 'class' => 'sidemenu-page-featured-image' ] ); ?>
				<h1 class="sidemenu-page-title"><?php the_title(); ?></h1>
				<?php the_content(); ?>
			</div>
		</div>
	</div>
</article>
