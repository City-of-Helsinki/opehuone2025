<?php
// Welcome widget for the WordPress dashboard
function opehuone_welcome_render() {

$current_user = wp_get_current_user();
$first_name   = $current_user->user_firstname;
$widget_group = get_field( 'welcome_widget', 'option' );
$text         = isset( $widget_group['text'] ) ? $widget_group['text'] : '';
$image        = isset( $widget_group['image'] ) ? $widget_group['image'] : '';
?>

<div id="opehuone-welcome" class="opehuone-widgets">
    <div class="left-block">
        <?php if ( $image ) : ?>
            <?php 
            $src = isset( $image['url'] ) ? esc_url( $image['url'] ) : '';
            $alt = isset( $image['alt'] ) ? esc_attr( $image['alt'] ) : '';
            ?>
            <img src="<?php echo $src; ?>" alt="<?php echo $alt; ?>">
        <?php endif; ?>
    </div>
    <div class="right-block">
        <h2>
            <?php if ( $first_name ) : ?>
                <?php printf( esc_html__( 'Hei, %s!' ), esc_html( $first_name ) ); ?>
                <?php esc_html_e( 'Kiva kun olet täällä.' ); ?>
            <?php else : ?>
                <?php esc_html_e( 'Hei, kiva kun olet täällä.' ); ?>
            <?php endif; ?>
        </h2>
        <?php echo wp_kses_post( $text ); ?>
    </div>
</div>

<?php
}