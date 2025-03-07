<?php
$string = get_post_meta( get_the_ID(), 'training_more_info', true );

if ( empty( $string ) ) {
	return;
}
?>
<div class="icon-detail">
	<?php \Opehuone\Helpers\the_svg( 'icons/more-info' ); ?>
	<div class="icon-detail__text-content">
		<span class="icon-detail__title"><?php esc_html_e( 'Lisätiedot', 'helsinki-universal' ); ?></span>
		<span class="icon-detail__subtitle"><?php echo esc_html( $string ); ?></span>
	</div>
</div>
