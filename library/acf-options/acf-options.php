<?php

if ( function_exists( 'acf_add_options_page' ) ) {

	// Opehuone asetukset -options page
	acf_add_options_page( [
		'page_title'      => __( 'Opehuone asetukset', 'helsinki-universal' ),
		'parent_slug'     => 'options-general.php',
		'update_button'   => __( 'Päivitä opehuoneen asetukset', 'helsinki-universal' ),
		'updated_message' => __( "Asetukset päivitetty", 'helsinki-universal' ),
	] );

	// Poikkeusilmoitus -options page
	acf_add_options_page( [
		'page_title'      => __( 'Poikkeusilmoitukset', 'helsinki-universal' ),
		'menu_title'      => __( 'Poikkeus&shy;ilmoitukset', 'helsinki-universal' ),
		'menu_slug'       => 'poikkeusilmoitukset-asetukset',
		'update_button'   => __( 'Päivitä poikkeusilmoitukset', 'helsinki-universal' ),
		'updated_message' => __( 'Poikkeusilmoitukset päivitetty', 'helsinki-universal' ),
		'icon_url'        => 'dashicons-megaphone',
	] );
}
