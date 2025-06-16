<?php
get_header();

/**
 * Hook: helsinki_loop_before
 *
 */
// do_action( 'helsinki_loop_before' );

?>

	<?php

	/**
	 * Hook: helsinki_loop_top
	 *
	 */
	// do_action( 'helsinki_loop_top' );

	get_template_part( 'partials/training-archive' );

	/**
	 * Hook: helsinki_loop_bottom
	 *
	 */
	do_action( 'helsinki_loop_bottom' );

	?>

<?php

get_footer(); ?>
