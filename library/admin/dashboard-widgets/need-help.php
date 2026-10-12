<?php
// Need help? widget for the WordPress dashboard
function opehuone_need_help_render() {

$widget_group = get_field( 'need_help_widget', 'option' );
$text         = isset( $widget_group['text'] ) ? $widget_group['text'] : '';
?>

<div id="opehuone-need-help" class="opehuone-widgets">
    <?php echo wp_kses_post( $text ); ?>
</div>

<?php
}