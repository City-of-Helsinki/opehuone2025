<?php

namespace Opehuone\ACFFilters;

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

// Validate the length of the service notice text field (at "Poikkeusilmoitukset" options page)
add_filter( 'acf/validate_value/name=service_notice_text', function ( $valid, $value, $field, $input_name ) {
	if ( $valid !== true ) {
		return $valid;
	}

	$max    = 500;
	$plain  = trim( html_entity_decode( wp_strip_all_tags( $value ) ) );
	$length = mb_strlen( $plain );

	if ( $length > $max ) {
		return sprintf(
			/* translators: 1: current length, 2: max length */
			__( 'Teksti on liian pitkä: %1$d / %2$d merkkiä.', 'helsinki-universal' ),
			$length,
			$max
		);
	}

	return $valid;
}, 10, 4 );