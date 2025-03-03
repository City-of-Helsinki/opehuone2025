<?php

namespace Opehuone\Assets;

/**
 * Enqueue assets for frontend
 *
 * @return void
 */
function add_frontend_enqueue_scripts() {
	$script_asset = require_once \get_theme_file_path( 'build/js/screen.asset.php' );
	$style_asset  = require_once \get_theme_file_path( 'build/css/screen.asset.php' );

	\wp_enqueue_script(
		'opehuone-screen',
		\get_theme_file_uri( 'build/js/screen.js' ),
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);

	\wp_enqueue_style(
		'opehuone-screen',
		\get_theme_file_uri( 'build/css/screen.css' ),
		$style_asset['dependencies'],
		$style_asset['version']
	);

	// Livereload
	if ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG && ! \is_admin() ) {
		$host = 'localhost';
		$port = '35729';
		\wp_enqueue_script( 'livereload', "http://$host:$port/livereload.js?snipver=1", [], null, true );
	}

	/**
	 * Move jquery to footer
	 */
	wp_scripts()->add_data( 'jquery', 'group', 1 );
	wp_scripts()->add_data( 'jquery-core', 'group', 1 );
	wp_scripts()->add_data( 'jquery-migrate', 'group', 1 );
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\add_frontend_enqueue_scripts', 20 );

/**
 * Enqueue assets for Gutenberg editor in admin
 *
 * @return void
 */
function enqueue_block_editor_assets() {
	$script_asset = require_once \get_theme_file_path( 'build/js/editor.asset.php' );
	$style_asset  = require_once \get_theme_file_path( 'build/css/editor.asset.php' );

	\wp_enqueue_script(
		'opehuone-editor',
		\get_theme_file_uri( 'build/js/editor.js' ),
		$script_asset['dependencies'],
		$script_asset['version'],
		true
	);

	\wp_enqueue_style(
		'opehuone-editor',
		\get_theme_file_uri( 'build/css/editor.css' ),
		$style_asset['dependencies'],
		$style_asset['version']
	);
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_block_editor_assets', 20 );


/**
 * Enqueue assets for login screen
 *
 * @return void
 */
function enqueue_login_assets() {
	$style_asset = require_once \get_theme_file_path( 'build/css/login.asset.php' );

	\wp_enqueue_style(
		'opehuone-login',
		\get_theme_file_uri( 'build/css/login.css' ),
		$style_asset['dependencies'],
		$style_asset['version']
	);
}

add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\\enqueue_login_assets' );

/**
 * Remove jquery-migrate from front end
 *
 * @param object $scripts Scripts object
 */
function dequeue_jquery_migrate( $scripts ) {
	if ( ! is_admin() && ! empty( $scripts->registered['jquery'] ) ) {
		$scripts->registered['jquery']->deps = array_diff(
			$scripts->registered['jquery']->deps,
			[ 'jquery-migrate' ]
		);
	}
}

add_action( 'wp_default_scripts', __NAMESPACE__ . '\\dequeue_jquery_migrate' );
