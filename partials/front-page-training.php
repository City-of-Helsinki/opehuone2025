<?php
use function \Opehuone\TemplateFunctions\get_user_cornerlabels_with_added_default_value;

$cornerlabels   = get_user_cornerlabels_with_added_default_value();

$query_args = [
	'post_type'      => 'training',
	'posts_per_page' => 8,
	'meta_key'       => 'training_start_datetime', // Define the meta key for ordering
	'orderby'        => 'meta_value', // Order by meta value
	'order'          => 'ASC', // Order in ascending order
	'meta_query'     => [
		[
			'key'     => 'training_end_datetime', // Target the correct meta field
			'value'   => current_time( 'Y-m-d\TH:i:s' ), // Get the current date and time in WordPress timezone
			'compare' => '>=', // Only include posts where the date is in the future
			'type'    => 'DATETIME', // Ensure proper comparison as a date-time value
		],
	],
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
?>
<h2 class="front-page-posts-filter__title">
	<?php esc_html_e( 'Koulutukset', 'helsini-universal' ); ?>
</h2>
<?php get_template_part( 'partials/front-page-filters', '', [ 'to_target' => 'training' ] ); ?>
<div class="b-training-row">
	<?php
	while ( $query->have_posts() ) {
		$query->the_post();

		$block_args = [
			'url'            => get_the_permalink(),
			'title'          => get_the_title(),
			'type'           => get_post_meta( get_the_ID(), 'training_type', true ),
			'theme'          => get_post_meta( get_the_ID(), 'training_theme_color', true ),
			'start_datetime' => get_post_meta( get_the_ID(), 'training_start_datetime', true ),
			'end_datetime'   => get_post_meta( get_the_ID(), 'training_end_datetime', true ),
			'excerpt'        => get_the_excerpt(),
			'categories'     => get_the_terms( get_the_ID(), 'training_theme' ),
		];

		get_template_part( 'partials/template-blocks/b-training-post', '', $block_args );
	}

	wp_reset_postdata();
	?>
</div>
<div class="b-training-row__button-wrapper">
	<a href="<?php echo esc_url( get_post_type_archive_link( 'training' ) ); ?>" class="b-training-row__more-btn">
		<?php esc_html_e( 'Katso kaikki koulutukset', 'helsinki-universal' ); ?>
	</a>
</div>
