<?php

use function \Opehuone\TemplateFunctions\get_post_archive_query;
use function \Opehuone\Utils\get_user_favs;
use function Opehuone\TemplateFunctions\display_load_more_button;

$posts_query = get_post_archive_query();

?>

<span class="posts-archive__number-of-posts" aria-live="polite">
	<span
		id="archive-number-of-posts"><?php echo esc_html( $posts_query->found_posts ); ?></span> <?php esc_html_e( 'hakutulosta', 'helsinki-universal' ); ?>
</span>

<div class="b-posts-row" id="posts-archive-results">
	<?php if ( $posts_query->have_posts() ) : ?>
	    <?php while ( $posts_query->have_posts() ) : $posts_query->the_post(); ?>
        <?php
		$block_args = [
			'post_id'    => get_the_ID(),
			'title'      => get_the_title(),
			'url'        => get_the_permalink(),
			'media_id'   => get_post_thumbnail_id(),
			'excerpt'    => get_the_excerpt(),
			'is_sticky'  => is_sticky(),
			'categories' => get_the_category(),
			'date'       => get_the_date(),
			'is_pinned'  => in_array( get_the_ID(), get_user_favs() ),
		];
		get_template_part( 'partials/template-blocks/b-post', '', $block_args );
        endwhile;
	wp_reset_postdata();
    endif;
	?>
</div>
<?php display_load_more_button( $posts_query->found_posts, 15 ); ?>


