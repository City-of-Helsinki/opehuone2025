<?php
get_header();

/**
 * Hook: helsinki_loop_before
 *
 */
do_action( 'helsinki_loop_before' );

?>

<div class="content">

	<?php

	/**
	 * Hook: helsinki_loop_top
	 *
	 */
	if ( get_queried_object_id() === (int) get_option('page_for_posts') ) {
		get_template_part('partials/header/news-header');
	} else {
		do_action( 'helsinki_loop_top' );
	}

	?>

	<div class="hds-container content__container">
		<?php get_template_part( 'partials/posts-archive' ); ?>
	</div>

	<?php

	/**
	 * Hook: helsinki_loop_bottom
	 *
	 */
	do_action( 'helsinki_loop_bottom' );

	?>

</div>

<?php

/**
 * Hook: helsinki_loop_after
 *
 */
do_action( 'helsinki_loop_after' );

get_footer(); ?>
