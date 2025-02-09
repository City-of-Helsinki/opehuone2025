<?php
$query_args = [
	'post_type'      => 'post',
	'posts_per_page' => 8,
];

$query = new WP_Query( $query_args );

if ( ! $query->have_posts() ) {
	return;
}
?>
<h2>
	Uutiset ja tiedotteet
</h2>
<div class="b-posts-row">
	<?php
	while ( $query->have_posts() ) {
		$query->the_post();

		$block_args = [
			'title'      => get_the_title(),
			'url'        => get_the_permalink(),
			'media_id'   => get_post_thumbnail_id(),
			'excerpt'    => get_the_excerpt(),
			'is_sticky'  => is_sticky(),
			'categories' => get_the_category(),
			'date'       => get_the_date(),
		];

		get_template_part( 'partials/blocks/b-post' );
	}

	wp_reset_postdata();
	?>
</div>

