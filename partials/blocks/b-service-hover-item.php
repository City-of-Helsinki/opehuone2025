<?php
$hover_title       = isset( $args['name'] ) ? $args['name'] : null;
$hover_url         = isset( $args['url'] ) ? $args['url'] : null;
$hover_description = isset( $args['description'] ) ? $args['description'] : null;
?>
<div class="services-column__hover-content" aria-hidden="true">
			<span class="services-column__hover-content__headline">
				<?php echo esc_html( $hover_title ); ?>
			</span>
    <span class="services-column__hover-content__url">
				<?php echo esc_url( $hover_url ); ?>
			</span>
    <?php if ( ! empty( $hover_description ) ) : ?>
        <span class="services-column__hover-content__bottom">
				<?php echo esc_html( $hover_description ); ?>
			</span>
    <?php endif; ?>
</div>
