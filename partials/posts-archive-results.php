<?php

if ( ! have_posts() ) {
	return;
}

global $wp_query;

$user_favs = \Opehuone\Utils\get_user_favs();
$per_page  = 15;
?>

<span class="posts-archive__number-of-posts" aria-live="polite">
	<span
		id="posts-archive-number-of-posts"><?php echo esc_html( $wp_query->found_posts ); ?></span> <?php esc_html_e( 'hakutulosta', 'helsinki-universal' ); ?>
</span>

<div class="b-posts-row" id="posts-archive-results">
	<?php
	while ( have_posts() ) {
		the_post();

		$block_args = [
			'post_id'    => get_the_ID(),
			'title'      => get_the_title(),
			'url'        => get_the_permalink(),
			'media_id'   => get_post_thumbnail_id(),
			'excerpt'    => get_the_excerpt(),
			'is_sticky'  => is_sticky(),
			'categories' => get_the_category(),
			'date'       => get_the_date(),
			'is_pinned'  => in_array( get_the_ID(), $user_favs ),
		];

		get_template_part( 'partials/template-blocks/b-post', '', $block_args );
	}

	wp_reset_postdata();
	?>
</div>
<div class="posts-archive__load-more-wrapper">
	<button class="posts-archive__load-more-btn"
			data-total-posts="<?php echo esc_attr( $wp_query->found_posts ); ?>"
			data-posts-offset="15" data-cornerlabels="" data-categories=""
			data-post-tags="">
		<?php esc_html_e( 'Lataa lisää', 'helsinki-universal' ); ?>
	</button>
</div>
