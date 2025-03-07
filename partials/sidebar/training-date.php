<?php
$start = get_post_meta( get_the_ID(), 'training_start_datetime', true );
$end   = get_post_meta( get_the_ID(), 'training_end_datetime', true );

$date = TrainingHelpers::get_training_date( $start, $end );
?>
<div class="icon-detail">
	<?php \Opehuone\Helpers\the_svg( 'icons/calendar-clock' ); ?>
	<div class="icon-detail__text-content">
		<span class="icon-detail__title"><?php esc_html_e( 'Päivämäärä ja aika', 'helsinki-universal' ); ?></span>
		<span class="icon-detail__subtitle"><?php echo esc_html( $date ); ?></span>
	</div>
</div>
