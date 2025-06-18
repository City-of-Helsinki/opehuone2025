<?php

$page_id = get_field( 'trainings_page', 'option' );

if ( $page_id ) {
    get_template_part( 'partials/archive-page-with-sidemenu', null, ['page_id' => $page_id] );
}
?>

<div class="hds-container content__container">
<?php
	get_template_part( 'partials/empty' );
	get_template_part( 'partials/training-archive-filters' );
	get_template_part( 'partials/empty' );
	get_template_part( 'partials/training-archive-results' );
?>
</div>

