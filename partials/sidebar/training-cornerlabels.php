<?php

use function Opehuone\Helpers\the_svg;

$terms = get_the_terms( get_the_ID(), 'cornerlabels' );

if ( empty( $terms ) || is_wp_error( $terms ) ) {
	return;
}

$terms_array = [];

foreach ( $terms as $term ) {
	$terms_array[] = $term->name;
}
?>

<div class="icon-detail">
	<?php the_svg( 'icons/oppiaste' ); ?>
	<div class="icon-detail__text-content">
		<span class="icon-detail__title"><?php esc_html_e( 'Oppiaste', 'helsinki-universal' ); ?></span>
		<span class="icon-detail__subtitle"><?php echo esc_html( implode( ', ', $terms_array ) ); ?></span>
	</div>
</div>
