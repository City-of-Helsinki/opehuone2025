<?php

/**
 * Favorite button template
 *
 * @var array $args – passed from get_template_part() in get_favorite_article_button() function
 */

use function \Opehuone\Helpers\the_svg;

$block_is_pinned = ! empty( $args['block_is_pinned'] );
$pinner_aria     = $args['pinner_aria'] ?? '';
$button_action   = $args['button_action'] ?? '';

?>

<button
    class="pin-btn<?php echo $block_is_pinned ? ' pinned' : ''; ?>"
    data-post-id="<?php echo get_the_ID(); ?>"
    data-action="<?php echo esc_attr( $button_action ); ?>"
    aria-pressed="<?php echo $block_is_pinned ? 'true' : 'false'; ?>"
    aria-label="<?php echo esc_attr( $pinner_aria ); ?>"
    type="button"
>
    <!-- Not pinned -->
    <span class="icon icon-default">
        <?php the_svg( 'icons/' . 'pin-large' ); ?>
    </span>

    <!-- Hover -->
    <span class="icon icon-hover">
        <?php the_svg( 'icons/pin-large-hover' ); ?>
    </span>

    <!-- Pinned -->
    <span class="icon icon-pinned">
        <?php the_svg( 'icons/pinned-large' ); ?>
    </span>
</button>

