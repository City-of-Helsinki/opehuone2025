<?php

namespace Opehuone\Helpers;

/**
 * Helper-functions
 *
 * @package Blocksmith
 */

/**
 * Used to get the asset uri
 *
 * @param string $filename File name
 *
 * @return string
 */
function asset_local( $filename ) {
	return trailingslashit( get_theme_file_path() ) . "build/{$filename}";
}

function the_svg( $file_name ) {
	readfile( asset_local( 'images' ) . '/' . $file_name . '.svg' );
}

/**
 * Include all files from folder
 *
 * @param string $dir Directory
 * @param string $suffix Suffix of the file
 */
function require_files( $dir, $suffix = 'php' ) {
	$dir = trailingslashit( $dir );

	if ( ! is_dir( $dir ) ) {
		return;
	}

	$files = new \DirectoryIterator( $dir );

	foreach ( $files as $file ) {
		if ( ! $file->isDot() && $file->getExtension() === $suffix ) {
			$filename = $dir . $file->getFilename();
			require_once( $filename );
		}
	}
}

/**
 * Returns folders inside given folder as array
 *
 * Array will not include '.' or '..' folder names
 *
 * @param string $dir Directory
 *
 * @return array|false Array of folder names inside a folder ot false if not folder found
 */
function get_directories_array( $dir ) {
	return array_diff( scandir( $dir ), [ '.', '..' ] );
}
