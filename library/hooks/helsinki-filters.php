<?php

namespace Opehuone\HelsinkiMainThemeFilters;

// Default scheme
add_filter( 'helsinki_default_scheme', function () {
	return 'tram';
} );

// Enable search
add_filter( 'helsinki_header_search_enabled', '__return_false' );

// Allow all blocks, especially classic (core/freeform) block
add_filter( 'helsinki_wp_disallowed_blocks', function () {
	return [];
} );

// Remove topbar from header
function remove_helsinki_theme_actions() {
	if ( has_action( 'helsinki_header_top', 'helsinki_topbar' ) ) {
		remove_action( 'helsinki_header_top', 'helsinki_topbar', 10 );
	}

    if ( has_action ('helsinki_footer_bottom', 'helsinki_footer_logo' ) ) {
        remove_action('helsinki_footer_bottom', 'helsinki_footer_logo', 10);
    }

    if ( has_action('helsinki_not_found', 'helsinki_not_found_notice' ) ) {
        remove_action('helsinki_not_found', 'helsinki_not_found_notice', 30);
    }
}

add_action( 'wp_head', __NAMESPACE__ . '\\remove_helsinki_theme_actions' );


add_action( 'helsinki_header', function () {
    get_template_part( 'partials/header/search-button' );
	get_template_part( 'partials/header/profile-opener' );
}, 41 );

add_filter( 'body_class', function ( $classes ) {
	if ( is_page_template( 'custom-templates/user-settings.php' ) || is_home() || is_post_type_archive( 'training' ) ) {
		$classes = array_diff( $classes, [ 'has-sidebar' ] );
	}

	return $classes;
}, 999 );

add_filter( 'helsinki_hero_layout_style', function ( $style, $post_id ) {
	if ( is_front_page() ) {
		$style = 'has-front-page';
	}

	return $style;
}, 9999, 2 );


add_action('helsinki_footer', function() {
    get_template_part('partials/footer/footer-top');
}, 11);

add_action('helsinki_footer_bottom', function() {
    get_template_part('partials/footer/logo' );
}, 10);

/**
 * Display 404 page content based on Opehuone asetukset ACF WYSIWYG field
 *
 */
add_action('helsinki_not_found', function () {
    $page_not_found_acf = get_field('404_page_content', 'option');

    if ( empty( $page_not_found_acf ) ) {
        return;
    }

    echo $page_not_found_acf;
}, 10);