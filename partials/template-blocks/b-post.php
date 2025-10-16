<?php

use function \Opehuone\TemplateFunctions\get_custom_card_thumbnail;

$block_post_id    = isset( $args['post_id'] ) ? $args['post_id'] : null;
$block_title      = isset( $args['title'] ) ? $args['title'] : null;
$block_url        = isset( $args['url'] ) ? $args['url'] : null;
$block_media_id   = isset( $args['media_id'] ) ? $args['media_id'] : null;
$block_excerpt    = isset( $args['excerpt'] ) ? $args['excerpt'] : null;
$block_date       = isset( $args['date'] ) ? $args['date'] : null;
$block_categories = isset( $args['categories'] ) ? $args['categories'] : [];
$block_is_sticky  = isset( $args['is_sticky'] ) ? $args['is_sticky'] : false;
$block_is_pinned  = isset( $args['is_pinned'] ) ? $args['is_pinned'] : false;

$pin_svg       = 'pin';
$pinner_aria   = 'Lisää sivu kirjanmerkiksi';
$button_action = 'favs_add';

if ( $block_is_pinned ) {
	$pin_svg       = 'pinned';
	$pinner_aria   = 'Poista sivu kirjanmerkeistä';
	$button_action = 'favs_remove';
}

$post_thumbnail = wp_get_attachment_image( $block_media_id, 'medium', false, [ 'class' => 'b-post__image' ] );

if ( ! $post_thumbnail ) {
    $post_thumbnail = get_custom_card_thumbnail();
}
?>
<div class="b-post">
    <figure class="b-post__figure">
        <?php echo $post_thumbnail ?>
        <?php if ( $block_is_sticky ) : ?>
            <span class="b-post__sticky">Uutisnosto</span>
        <?php endif; ?>
        <?php if ( is_user_logged_in() ) : ?>
        <button class="b-post__pinner" data-action="<?php echo esc_attr( $button_action ); ?>"
                data-post-id="<?php echo esc_attr( $block_post_id ); ?>"
                aria-label="<?php echo esc_attr( $pinner_aria ); ?>">
            <?php \Opehuone\Helpers\the_svg( 'icons/' . $pin_svg ); ?>
        </button>
        <?php endif; ?>
    </figure>
	<?php if ( ! empty( $block_date ) ) : ?>
		<time class="b-post__date">
			<?php echo esc_html( $block_date ); ?>
		</time>
	<?php endif; ?>
	<?php if ( ! empty( $block_url ) && ! empty( $block_title ) ) : ?>
		<a href="<?php echo esc_url( $block_url ); ?>" class="b-post__title">
			<?php echo esc_html( $block_title ); ?>
		</a>
	<?php endif; ?>
	<?php if ( ! empty( $block_excerpt ) ) : ?>
		<p class="b-post__excerpt">
			<?php echo esc_html( $block_excerpt ); ?>
		</p>
	<?php endif; ?>
	<?php if ( count( $block_categories ) > 0 ) : ?>
		<ul class="post-tags">
			<?php
			foreach ( $block_categories as $category ) {
				$color = ! empty( get_term_meta( $category->term_id, 'color_theme', true ) ) ? get_term_meta( $category->term_id, 'color_theme', true ) : 'suomenlinna';
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
