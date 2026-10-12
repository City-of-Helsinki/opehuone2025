<?php
// Instructions widget for the WordPress dashboard
function opehuone_instructions_render() {

$widget_group = get_field( 'instructions_widget', 'option' );
$text         = isset( $widget_group['text'] ) ? $widget_group['text'] : '';
$image        = isset( $widget_group['image'] ) ? $widget_group['image'] : '';
?>

<div id="opehuone-instructions" class="opehuone-widgets">
    <div class="left-block">
        <?php echo wp_kses_post( $text ); ?>
    </div>
    <div class="right-block">
        <?php if ( $image ) : ?>
            <?php 
            $src = isset( $image['url'] ) ? esc_url( $image['url'] ) : '';
            $alt = isset( $image['alt'] ) ? esc_attr( $image['alt'] ) : '';
            ?>
            <img src="<?php echo $src; ?>" alt="<?php echo $alt; ?>">
        <?php endif; ?>
    </div>
</div>

<?php
}