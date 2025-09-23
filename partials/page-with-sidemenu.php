<?php
use function \Opehuone\TemplateFunctions\get_top_parent_page_title;
?>

<article class="content" data-current-page-id="<?php echo get_the_ID(); ?>">
	<div class="hds-container opehuone-page opehuone-content-container">
		<?php get_template_part( 'partials/breadcrumbs' ); ?>

        <?php get_template_part( 'partials/page-filters' ); ?>

		<div class="opehuone-grid opehuone-grid--reversed">
			<aside>
				<?php get_template_part( 'partials/sidemenu' ); ?>
			</aside>
			<div>
				<h1 class="page-title"><?php the_title(); ?></h1>
				<?php if ( has_excerpt() ) : ?>
					<p class="single-post__excerpt">
						<?php echo get_the_excerpt(); ?>
					</p>
				<?php endif; ?>
				<?php get_template_part( 'partials/page-meta' );  ?>
				<?php the_post_thumbnail( 'large', [ 'class' => 'featured-image' ] ); ?>
				<?php the_content(); ?>
                <?php
                $top_parent_title = get_top_parent_page_title();
                if ( $top_parent_title ) {
                    echo '<span data-fdk-tags style="display: none;">opehuone-search-label/' . esc_html( $top_parent_title ) .'</span>';
                }
                ?>

                <?php do_action('helsinki_content_body_after'); ?>
			</div>
		</div>
	</div>
</article>
