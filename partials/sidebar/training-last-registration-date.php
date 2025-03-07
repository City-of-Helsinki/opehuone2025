<?php
$deadline = get_post_meta( get_the_ID(), 'training_registration_deadline', true );

$date = TrainingHelpers::get_single_date( $deadline );

if ( empty( $date ) ) {
	return;
}
?>
<div class="icon-detail">
	<?php \Opehuone\Helpers\the_svg( 'icons/clock' ); ?>
	<div class="icon-detail__text-content">
		<span
			class="icon-detail__title"><?php esc_html_e( 'Viimeinen ilmoittautumispäivä', 'helsinki-universal' ); ?></span>
		<span class="icon-detail__subtitle"><?php echo esc_html( $date ); ?></span>
	</div>
</div>
