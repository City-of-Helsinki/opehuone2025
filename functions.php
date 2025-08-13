<?php

use \Opehuone\Helpers;

define( 'TEXT_DOMAIN', 'opehuone2025' );

// removes main menu action from Helsinki theme
function remove_parent_menu() {
    remove_action('helsinki_header_bottom', 'opehuone_header_main_menu', 20);
}
add_action('wp_loaded', 'remove_parent_menu');

add_action( 'helsinki_header_bottom', function() {
    get_template_part( 'partials/components/findkit' );
}, 19);
/**
 * Require helpers
 */
require dirname( __FILE__ ) . '/library/functions/helpers.php';
require dirname( __FILE__ ) . '/library/functions/modules/menu.php';
require dirname( __FILE__ ) . '/library/utils/walkers.php';
require dirname( __FILE__ ) . '/library/functions/polylang-fallbacks.php';
require dirname( __FILE__ ) . '/library/functions/template-functions.php';

// calls for menu template part
if ( ! function_exists('opehuone_header_main_menu') ) {
	function opehuone_header_main_menu() {
		get_template_part('partials/header/menu');
	}
}

/**
 * Set theme name which will be referenced from style & script registrations
 *
 * @return WP_Theme
 */
function opehuone_theme() {
	return wp_get_theme();
}

/**
 * Require some classes
 */
Helpers\require_files( dirname( __FILE__ ) . '/library/classes' );

/**
 * Require utils
 */
Helpers\require_files( dirname( __FILE__ ) . '/library/utils' );

/**
 * Require acf options
 */
Helpers\require_files( dirname( __FILE__ ) . '/library/acf-options' );

/**
 * Require custom post types and taxonomies
 */
Helpers\require_files( dirname( __FILE__ ) . '/library/custom-posts' );
Helpers\require_files( dirname( __FILE__ ) . '/library/taxonomies' );

/**
 * Hooks
 */
Helpers\require_files( dirname( __FILE__ ) . '/library/hooks' );

/**
 * Register local ACF-json
 */
add_filter( 'acf/settings/save_json', function () {
	return get_stylesheet_directory() . '/library/acf-data';
} );

add_filter( 'acf/settings/load_json', function ( $paths ) {
	$paths[] = get_stylesheet_directory() . '/library/acf-data';

	return $paths;
} );

/**
 * Add text to theme footer
 */
add_filter(
	'admin_footer_text',
	function () {
		return '<span id="footer-thankyou">' . opehuone_theme()->Name . ' by: <a href="' . opehuone_theme()->AuthorURI . '" target="_blank">' . opehuone_theme()->Author . '</a><span>';
	}
);

// Site is hidden from search engines, but Findkit needs the sitemap ==> lets enable it
add_filter( 'wp_sitemaps_enabled', '__return_true' );