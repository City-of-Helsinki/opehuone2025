<?php
$cornerlabels = Opehuone_user_settings_reader::get_user_settings_key( 'cornerlabels' );

$query_args = [
	'post_type'      => 'post',
	'posts_per_page' => 8,
];

if ( is_array( $cornerlabels ) && count( $cornerlabels ) > 0 ) {
	$tax_query  = [
		'tax_query' => [
			[
				'taxonomy' => 'cornerlabels',
				'field'    => 'id',
				'terms'    => $cornerlabels,
			],
		]
	];
	$query_args = wp_parse_args( $tax_query, $query_args );
}

$query = new WP_Query( $query_args );

if ( ! $query->have_posts() ) {
	return;
}
$user_favs = \Opehuone\Utils\get_user_favs();
?>
<h2 class="front-page-posts-filter__title">
	<?php esc_html_e( 'Uutiset ja tiedotteet', 'helsini-universal' ); ?>
</h2>
<?php get_template_part( 'partials/front-page-filters' ); ?>
<div class="b-posts-row">
	<?php
	while ( $query->have_posts() ) {
		$query->the_post();

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
<div class="b-posts-row__button-wrapper">
	<a href="<?php echo esc_url( get_post_type_archive_link( 'post' ) ); ?>" class="b-posts-row__more-btn">
		<?php esc_html_e( 'Katso kaikki uutiset', 'helsinki-universal' ); ?>
	</a>
</div>

