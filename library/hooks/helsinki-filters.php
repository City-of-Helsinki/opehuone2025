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
function remove_helsinki_topbar() {
	if ( has_action( 'helsinki_header_top', 'helsinki_topbar' ) ) {
		remove_action( 'helsinki_header_top', 'helsinki_topbar', 10 );
	}
}

add_action( 'wp_head', __NAMESPACE__ . '\\remove_helsinki_topbar' );


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
