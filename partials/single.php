<?php

use function \Opehuone\TemplateFunctions\get_favorite_article_button;

?>

<article class="content">
	<div class="content__container hds-container">
		<?php get_template_part( 'partials/breadcrumbs' ); ?>
		<div class="opehuone-grid">
			<section>
				<h1 class="single-post__title"><?php the_title(); ?></h1>
                <?php
                $cornerlabels = wp_get_post_terms( get_the_ID(), 'cornerlabels' );

                if ( ! empty( $cornerlabels ) && ! is_wp_error( $cornerlabels ) ) {
                    echo '<div class="single-post__date-row-cornerlabel-container">';
                    foreach ( $cornerlabels as $term ) {
                        echo '<span class="single-post__date-row-cornerlabel">' . esc_html( $term->name ) . '</span>';
                        echo '<span data-fdk-tags style="display: none;">opehuone-search-label/' . esc_html( $term->name ) .'</span>';
                    }
                    echo '</div>';
                }
                ?>
				<?php if ( has_excerpt() ) : ?>
					<p class="single-post__excerpt">
						<?php echo get_the_excerpt(); ?>
					</p>
				<?php endif; ?>
				<?php get_template_part('partials/page-meta' ); ?>
				<?php the_post_thumbnail( 'large', [ 'class' => 'single-post__featured-image' ] ); ?>
				<?php the_content(); ?>
				<?php get_template_part( 'partials/post-category-tags' );  ?>
                <?php
                // Load the comment template if comments are open
                if ( comments_open() ) {
                    comments_template();
                }
                ?>

                <?php do_action('helsinki_content_body_after'); ?>
			</section>
			<aside>
				<div class="single-post__sidebar-post">
					<?php
					get_template_part( 'partials/sidebar/post-latest-news' );
					get_template_part( 'partials/sidebar/post-most-read-news' );
					?>
				</div>
			</aside>
		</div>
	</div>
</article>
