<?php

use Opehuone\Helpers;

$timed_service = new Timed_service_message();
$status        = get_field( 'service_fault_message_status', 'option' );
$svg_icon_name = 'info-circle';

if ( $timed_service->is_service_active() ) {
	$theme_field     = 'timed_service_fault_theme';
	$title_field     = 'timed_service_fault_title';
	$message_field   = 'timed_service_fault_message';
	$read_more_field = 'timed_service_fault_message_read_more';
} else {
	$theme_field     = 'service_fault_theme';
	$title_field     = 'service_fault_title';
	$message_field   = 'service_fault_message';
	$read_more_field = 'service_fault_message_read_more';
}

$theme             = ! empty( get_field( $theme_field, 'option' ) ) ? get_field( $theme_field, 'option' ) : 'info';
$title             = get_field( $title_field, 'option' );
$message           = get_field( $message_field, 'option' );
$read_more_message = get_field( $read_more_field, 'option' );

if ( $theme === 'error' ) {
	$svg_icon_name = 'error-triangle';
}

if ( $status === true || $timed_service->is_service_active() ) {
	?>
	<div class="service-failure <?php echo 'service-failure--' . esc_attr( $theme ); ?>">
		<h2 class="service-failure__title">
			<?php Helpers\the_svg( 'icons/' . $svg_icon_name ); ?>
			<?php echo esc_html( $title ); ?>
		</h2>
		<?php if ( ! empty( $message ) ) : ?>
			<?php echo wp_kses_post( $message ); ?>
		<?php endif; ?>

		<?php if ( ! empty( $read_more_message ) ) : ?>
			<button class="service-failure__toggler">
				<?php esc_html_e( 'Lue lisää', 'helsinki-universal' ); ?>
				<?php Helpers\the_svg( 'icons/arrow-down' ); ?>
			</button>
			<div class="service-failure__read-more-section">
				<?php echo wp_kses_post( $read_more_message ); ?>
			</div>
		<?php endif; ?>
	</div>
	<?php
}
?>
