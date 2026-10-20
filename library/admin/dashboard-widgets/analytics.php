<?php
// Analytics widget for the WordPress dashboard
function opehuone_analytics_render() {

$widget_group = get_field( 'analytics_widget', 'option' );
$text         = isset( $widget_group['text'] ) ? $widget_group['text'] : '';
?>

<div id="opehuone-analytics" class="opehuone-widgets">
    <?php echo wp_kses_post( $text ); ?>
</div>

<?php
}