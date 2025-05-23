<?php

use Opehuone\Helpers;

$block_title      = isset( $args['title'] ) ? $args['title'] : null;
$block_url        = isset( $args['url'] ) ? $args['url'] : null;
$block_excerpt    = isset( $args['excerpt'] ) ? $args['excerpt'] : null;
$block_categories = isset( $args['categories'] ) ? $args['categories'] : [];

$date          = TrainingHelpers::get_training_date( $args['start_datetime'], $args['end_datetime'] );
$readable_type = TrainingHelpers::get_training_type( $args['type'] );
$theme         = TrainingHelpers::get_training_theme( $args['theme'] );
$type_svg      = TrainingHelpers::get_training_type_svg( $args['type'] );
?>
<div class="b-training-post">
	<div class="b-training-post__wrapper b-training-post__wrapper--has-theme-<?php echo esc_attr( $theme ); ?>">
		<?php if ( ! empty( $block_url ) && ! empty( $block_title ) ) : ?>
			<a href="<?php echo esc_url( $block_url ); ?>" class="b-training-post__title">
				<?php echo esc_html( $block_title ); ?>
			</a>
		<?php endif; ?>
		<div class="b-training-post__icon-rows">
			<div class="b-training-post__icon-row">
				<?php Helpers\the_svg( 'icons/calendar-clock' ); ?>
				<?php echo esc_html( $date ); ?>
			</div>
			<div class="b-training-post__icon-row">
				<?php Helpers\the_svg( 'icons/' . $type_svg ); ?>
				<?php echo esc_html( $readable_type ); ?>
			</div>
		</div>
		<?php if ( ! empty( $block_excerpt ) ) : ?>
			<p class="b-training-post__excerpt">
				<?php echo esc_html( $block_excerpt ); ?>
			</p>
		<?php endif; ?>
		<?php if ( ! empty( $block_categories ) && count( $block_categories ) > 0 ) : ?>
			<ul class="post-tags">
				<?php
				foreach ( $block_categories as $category ) {
					$color = ! empty( get_term_meta( $category->term_id, 'button_color_theme', true ) ) ? get_term_meta( $category->term_id, 'button_color_theme', true ) : 'suomenlinna';
					?>
					<li class="has-post-tag-color-<?php echo esc_attr( $color ); ?>">
						<?php echo esc_html( $category->name ); ?>
					</li>
					<?php
				}
				?>
			</ul>
		<?php endif; ?>
	</div>
</div>
