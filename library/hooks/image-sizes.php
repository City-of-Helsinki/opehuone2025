<?php

namespace Opehuone\ImageSizes;

/**
 * Register image sizes
 */

function imagesizes() {
	return [
		[
			'name'       => 'article_lift',
			'numan_name' => 'Artikkelinosto',
			'width'      => 730,
			'height'     => 450,
			'crop'       => true,
		],
	];
}

add_action( 'after_setup_theme', function () {
	if ( ! empty( imagesizes() ) ) {
		foreach ( imagesizes() as $size ) {
			add_image_size( $size['name'], $size['width'], $size['height'], $size['crop'] );
		}
	}
} );

/**
 * Custom image sizes to gutenberg image size selection into here
 */
add_filter(
	'image_size_names_choose',
	function ( $sizes ) {

		$array = [];

		foreach ( imagesizes() as $size ) {
			// Set the 'name' as key and 'numan_name' as value
			$array[ $size['name'] ] = $size['numan_name'];
		}

		return array_merge(
			$sizes,
			$array
		);
	}
);
