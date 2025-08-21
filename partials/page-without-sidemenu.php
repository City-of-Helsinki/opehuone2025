<?php
use function \Opehuone\TemplateFunctions\get_top_parent_page_title;
?>

<article class="content">
	<div class="hds-container opehuone-page opehuone-content-container">
		<?php get_template_part( 'partials/breadcrumbs' ); ?>
		<div class="opehuone-grid">
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
			</div>
		</div>
	</div>
</article>
