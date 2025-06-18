<?php
$query_args = [
	'post_type'      => 'training',
	'posts_per_page' => 5,
	'meta_key'       => 'training_registration_deadline', // Define the meta key for ordering
	'orderby'        => 'meta_value', // Order by meta value
	'order'          => 'ASC', // Order in ascending order
	'meta_query'     => [
		'relation' => 'AND',
		[
			'key'     => 'training_registration_deadline', // Target the correct meta field
			'value'   => current_time( 'Y-m-d\TH:i:s' ), // Get the current date and time in WordPress timezone
			'compare' => '>=', // Only include posts where the date is in the future
			'type'    => 'DATETIME', // Ensure proper comparison as a date-time value
		],
		[
			'key'     => 'training_registration_deadline',
			'compare' => 'EXISTS',
		],
	],
];

$query = new WP_Query( $query_args );

// if ( ! $query->have_posts() ) {
// 	return;
// }
?>
<h2 class="training-archive__sidebar-title">
	<?php esc_html_e( 'Vielä ehdit ilmoittautua mukaan', 'helsinki-universal' ); ?>
</h2>
<ul class="b-reg-deadline-training-list">
	<?php
	while ( $query->have_posts() ) {
		$query->the_post();

		$block_args = [
			'url'            => get_the_permalink(),
			'title'          => get_the_title(),
			'start_datetime' => get_post_meta( get_the_ID(), 'training_start_datetime', true ),
			'end_datetime'   => get_post_meta( get_the_ID(), 'training_end_datetime', true ),
			'categories'     => get_the_terms( get_the_ID(), 'cornerlabels' ),
		];

		get_template_part( 'partials/template-blocks/b-reg-deadline-training-list', '', $block_args );
	}

	wp_reset_postdata();
	?>
</ul>
