<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/dashboard-widgets/instructions.php';

add_action( 'wp_dashboard_setup', function () {
    // Remove default dashboard widgets
    remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_right_now',   'dashboard', 'normal' );
    remove_meta_box( 'dashboard_activity',    'dashboard', 'normal' );
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_primary',     'dashboard', 'side' );

    // Register custom dashboard widgets
    wp_add_dashboard_widget( 'opehuone_instructions', 'Ohjeet', 'opehuone_instructions_render' );
} );