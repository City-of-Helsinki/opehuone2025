<article class="content">
	<div class="content__container hds-container">
		<?php get_template_part( 'partials/breadcrumbs' ); ?>
		<div class="opehuone-grid">
			<section>
				<h1 class="single-post__title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="single-post__excerpt">
						<?php echo get_the_excerpt(); ?>
					</p>
				<?php endif; ?>
				<div class="single-post__date-row">
					<span>
						<?php
						echo get_the_date();
						$published = get_the_date( 'U' ); // Unix timestamp of publish date
						$modified  = get_the_modified_date( 'U' ); // Unix timestamp of modified date

						// echo string: "| Päivitetty date", if modifed date differs from publish date
						if ( $modified !== $published ) {
							echo ' | Päivitetty ' . get_the_modified_date();
						}

						?>
					</span>
					<?php
					$cornerlabels = wp_get_post_terms( get_the_ID(), 'cornerlabels' );

					if ( ! empty( $cornerlabels ) && ! is_wp_error( $cornerlabels ) ) {
						foreach ( $cornerlabels as $term ) {
							echo '<span class="single-post__date-row-cornerlabel">' . esc_html( $term->name ) . '</span>';
                            echo '<span data-fdk-tags style="display: none;">opehuone-search-label/' . esc_html( $term->name ) .'</span>';
						}
					}
					?>
				</div>
				<?php the_post_thumbnail( 'large', [ 'class' => 'single-post__featured-image' ] ); ?>
				<?php the_content(); ?>
				<?php get_template_part( 'partials/sidebar/post-category-tags' );  ?>
                <?php
                // Load the comment template if comments are open
                if ( comments_open() ) {
                    helsinki_comment_form();
                }
                ?>
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
