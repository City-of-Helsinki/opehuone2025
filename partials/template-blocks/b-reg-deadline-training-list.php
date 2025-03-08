<?php

use Opehuone\Helpers;

$block_title      = isset( $args['title'] ) ? $args['title'] : null;
$block_url        = isset( $args['url'] ) ? $args['url'] : null;
$block_categories = isset( $args['categories'] ) ? $args['categories'] : [];
$date             = TrainingHelpers::get_training_date( $args['start_datetime'], $args['end_datetime'] );
?>
<li class="b-reg-deadline-training-list__item">
	<?php
	if ( count( $block_categories ) > 0 ) {
		$array = [];
		foreach ( $block_categories as $category ) {
			$array[] = $category->name;
		}
		?>
		<p class="b-reg-deadline-training-list__categories">
			<?php echo esc_html( implode( ', ', $array ) ); ?>
		</p>
		<?php
	}
	?>
	<?php if ( ! empty( $block_url ) && ! empty( $block_title ) ) : ?>
		<a href="<?php echo esc_url( $block_url ); ?>" class="b-reg-deadline-training-list__title">
			<?php echo esc_html( $block_title ); ?>
		</a>
	<?php endif; ?>
	<div class="b-reg-deadline-training-list__icon-rows">
		<div class="b-reg-deadline-training-list__icon-row">
			<?php Helpers\the_svg( 'icons/calendar-clock' ); ?>
			<?php echo esc_html( $date ); ?>
		</div>
	</div>
</li>
