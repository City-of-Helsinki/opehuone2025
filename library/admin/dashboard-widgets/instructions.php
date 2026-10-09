<?php
// Instructions widget for the WordPress dashboard
function opehuone_instructions_render() {
?>

<div class="opehuone-instructions">
    <p>
        <strong><?php esc_html_e( 'Sisällöntuottajan oppaasta' ); ?></strong>
        <?php esc_html_e( 'löydät vinkit sisällöntuotantoon.' ); ?>
    </p>
</div>

<?php
}