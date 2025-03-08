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
	do_action( 'helsinki_loop_top' );

	?>

	<div class="hds-container content__container">
		<?php get_template_part( 'partials/training-archive' ); ?>
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

get_footer(); ?>
