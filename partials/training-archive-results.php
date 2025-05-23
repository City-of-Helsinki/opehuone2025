<?php

if ( ! have_posts() ) {
	return;
}

global $wp_query;
?>

<span class="training-archive__number-of-posts" aria-live="polite">
	<span id="training-archive-number-of-posts"><?php echo esc_html($wp_query->found_posts); ?></span> <?php esc_html_e('hakutulosta', 'helsinki-universal'); ?>
</span>

<div class="b-training-row" id="training-archive-results">
	<?php
	while ( have_posts() ) {
		the_post();

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
	} ?>
    <div class="pagination">
	<?php
	echo paginate_links( [
		'total'   => $wp_query->max_num_pages,
		'current' => max( 1, get_query_var( 'paged' ) ),
		'prev_text' => __('« Previous', 'helsinki-universal'),
		'next_text' => __('Next »', 'helsinki-universal'),
	] );
	?>
</div>
<?php
	wp_reset_postdata();
	?>
</div>
