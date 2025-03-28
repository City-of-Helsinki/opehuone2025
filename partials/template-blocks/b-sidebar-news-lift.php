<?php
$block_title = isset( $args['title'] ) ? $args['title'] : null;
$block_url   = isset( $args['url'] ) ? $args['url'] : null;
$block_date  = isset( $args['date'] ) ? $args['date'] : null;
?>

<li class="b-sidebar-news-lift__list-item">
	<p class="b-sidebar-news-lift__date">
		<?php echo esc_html( $block_date ); ?>
	</p>
	<a href="<?php echo esc_url( $block_url ); ?>" class="b-sidebar-news-lift__link">
		<?php echo esc_html( $block_title ); ?>
	</a>
</li>
