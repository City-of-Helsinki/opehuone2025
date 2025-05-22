<?php

use \Opehuone\Helpers;

define( 'TEXT_DOMAIN', 'opehuone2025' );

/**
 * Require helpers
 */
require dirname( __FILE__ ) . '/library/functions/helpers.php';
require dirname( __FILE__ ) . '/library/functions/polylang-fallbacks.php';

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
