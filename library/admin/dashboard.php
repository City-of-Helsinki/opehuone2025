<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Require widget files
require_once __DIR__ . '/dashboard-widgets/instructions.php';
require_once __DIR__ . '/dashboard-widgets/welcome.php';
require_once __DIR__ . '/dashboard-widgets/need-help.php';
require_once __DIR__ . '/dashboard-widgets/analytics.php';
require_once __DIR__ . '/dashboard-widgets/own-pages.php';
require_once __DIR__ . '/dashboard-widgets/own-posts.php';
require_once __DIR__ . '/dashboard-widgets/latest-trainings.php';

add_action( 'wp_dashboard_setup', function () {

    // Remove default dashboard widgets
    remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
    remove_meta_box( 'dashboard_right_now',   'dashboard', 'normal' );
    remove_meta_box( 'dashboard_activity',    'dashboard', 'normal' );
    remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
    remove_meta_box( 'dashboard_primary',     'dashboard', 'side' );

    // Register custom dashboard widgets
    wp_add_dashboard_widget( 'opehuone_instructions', 'Ohjeet', 'opehuone_instructions_render' );
    wp_add_dashboard_widget( 'opehuone_welcome', 'Tervetuloa Opehuoneeseen', 'opehuone_welcome_render' );
    wp_add_dashboard_widget( 'opehuone_need_help', 'Tarvitsetko apua?', 'opehuone_need_help_render' );
    wp_add_dashboard_widget( 'opehuone_own_pages', 'Omat sivut', 'opehuone_own_pages_render' );
    wp_add_dashboard_widget( 'opehuone_own_posts', 'Omat artikkelit', 'opehuone_own_posts_render' );
    wp_add_dashboard_widget( 'opehuone_latest_trainings', 'Viimeisimmät koulutukset', 'opehuone_latest_trainings_render' );

    if ( current_user_can( 'administrator' ) || current_user_can( 'editor' ) ) {
        wp_add_dashboard_widget( 'opehuone_analytics', 'Analytiikka', 'opehuone_analytics_render' );
    }

} );

// Create default order for dashboard widgets
add_filter( 'get_user_option_meta-box-order_dashboard', function ( $order ) {

    if ( ! empty( $order ) ) {
        return $order;
    }

    return array(
        // Left col, top to bottom
        'normal'  => 'opehuone_welcome,opehuone_own_pages,opehuone_own_posts,opehuone_latest_trainings',
        // Right col, top to bottom
        'side'    => 'opehuone_instructions,opehuone_need_help,opehuone_analytics',
        // Empty cols
        'column3' => '',
        'column4' => ''
    );
} );
