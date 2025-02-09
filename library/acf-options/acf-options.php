<?php

if ( function_exists( 'acf_add_options_page' ) ) {
	$args = [
		'page_title'      => __( 'Opehuone asetukset', 'helsinki-universal' ),
		'parent_slug'     => 'options-general.php',
		'update_button'   => __( 'Päivitä opehuoneen asetukset', 'helsinki-universal' ),
		'updated_message' => __( "Asetukset päivitetty", 'helsinki-universal' ),
	];
	acf_add_options_page( $args );
}
