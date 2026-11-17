<?php

use function Opehuone\Helpers\the_svg;

$start = get_post_meta( get_the_ID(), 'training_start_datetime', true );
$end   = get_post_meta( get_the_ID(), 'training_end_datetime', true );
$training_schedule = get_post_meta( get_the_ID(), 'training_schedule', true );

// Show time only if "Näytä kellonaika" switch is on
$show_time = (bool) get_post_meta( get_the_ID(), 'training_show_time', true );

$date = TrainingHelpers::get_training_date( $start, $end, $show_time );

// Extra dates (JSON string)
$extra_dates_raw = get_post_meta( get_the_ID(), 'training_extra_dates', true );
$extra_dates     = [];

if ( ! empty( $extra_dates_raw ) ) {
	$decoded = json_decode( $extra_dates_raw, true );

	if ( is_array( $decoded ) ) {
		foreach ( $decoded as $extra ) {
			$extra_start = $extra['start'] ?? '';
			$extra_end   = $extra['end'] ?? '';

			// Skip if start time is empty
			if ( empty( $extra_start ) ) {
				continue;
			}

			$extra_dates[] = TrainingHelpers::get_training_date( $extra_start, $extra_end, $show_time );
		}
	}
}
?>
<div class="icon-detail">
	<?php the_svg( 'icons/calendar-clock' ); ?>
	<div class="icon-detail__text-content">
		<span class="icon-detail__title"><?php esc_html_e( 'Päivämäärä ja aika', 'helsinki-universal' ); ?></span>
		<span class="icon-detail__subtitle"><?php echo nl2br( esc_html( $date ) ); ?></span>

		<?php foreach ( $extra_dates as $extra_date ) : ?>
			<span class="icon-detail__subtitle"><?php echo nl2br( esc_html( $extra_date ) ); ?></span>
		<?php endforeach; ?>

		<?php if ( ! empty( $training_schedule ) ) : ?>
			<span class="icon-detail__subtitle"><?php echo esc_html( $training_schedule ); ?></span>
		<?php endif; ?>
	</div>
</div>