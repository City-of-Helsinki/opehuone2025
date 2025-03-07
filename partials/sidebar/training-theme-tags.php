<?php

$terms = get_the_terms( get_the_ID(), 'training_theme' );

if ( empty( $terms ) || is_wp_error( $terms ) ) {
	return;
}

?>
<ul class="post-tags post-tags--is-lg">
	<?php
	foreach ( $terms as $category ) {
		$color = ! empty( get_term_meta( $category->term_id, 'button_color_theme', true ) ) ? get_term_meta( $category->term_id, 'button_color_theme', true ) : 'suomenlinna';
		?>
		<li class="has-post-tag-color-<?php echo esc_attr( $color ); ?>">
			<?php echo esc_html( $category->name ); ?>
		</li>
		<?php
	}
	?>
</ul>
