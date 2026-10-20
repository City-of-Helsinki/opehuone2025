<?php
/**
 * Remove admin menu items & restrict screens from different user roles
 *
 * @package Opehuone
 */

// ************ Ilmoittaja (custom) role ************

// Clean admin menu
function opehuone_ilmoittaja_admin_menu() {
	$user = wp_get_current_user();

	if ( ! in_array( 'ilmoittaja', (array) $user->roles, true ) ) {
		return;
	}

	remove_menu_page( 'tools.php' );
	remove_menu_page( 'options-general.php' ); // because "Opehuone asetukset" is under "Asetukset" - thats why "Asetukset" is shown at admin menu (even without capability)
	remove_menu_page( 'edit.php?post_type=training' );
	remove_menu_page( 'edit.php?post_type=links' );

}
add_action( 'admin_menu', 'opehuone_ilmoittaja_admin_menu', 999 );

// Clean admin bar
function opehuone_ilmoittaja_admin_bar( $wp_admin_bar ) {
	$user = wp_get_current_user();

	if ( ! in_array( 'ilmoittaja', (array) $user->roles, true ) ) {
		return;
	}

	$wp_admin_bar->remove_node( 'new-links' );
	$wp_admin_bar->remove_node( 'new-training' );
}
add_action( 'admin_bar_menu', 'opehuone_ilmoittaja_admin_bar', 999 );

// Redirect Ilmoittaja away from restricted admin screens.
function opehuone_ilmoittaja_restrict_admin_screens( $screen ) {
	$user = wp_get_current_user();

	if ( ! in_array( 'ilmoittaja', (array) $user->roles, true ) ) {
		return;
	}

	$restricted_screens = array(
		'tools',
		'options-general',
	);

	$restricted_post_types = array(
		'training',
		'links',
	);

	if ( in_array( $screen->id, $restricted_screens, true ) || in_array( $screen->post_type, $restricted_post_types, true ) ) {
		wp_safe_redirect( admin_url() );
		exit;
	}
}
add_action( 'current_screen', 'opehuone_ilmoittaja_restrict_admin_screens' );

// ************ Sisällöntuottaja (kirjoittaja / author) role ************

// Clean admin menu
function opehuone_author_admin_menu() {
	$user = wp_get_current_user();

	if ( ! in_array( 'author', (array) $user->roles, true ) ) {
		return;
	}

	remove_menu_page( 'tools.php' );
	remove_menu_page( 'options-general.php' ); // because "Opehuone asetukset" is under "Asetukset" - thats why "Asetukset" is shown at admin menu (even without capability)
	remove_menu_page( 'edit.php?post_type=links' );
	remove_menu_page( 'edit.php?post_type=concentration' );
	remove_menu_page( 'edit.php?post_type=services' );

}
add_action( 'admin_menu', 'opehuone_author_admin_menu', 999 );

// Clean admin bar
function opehuone_author_admin_bar( $wp_admin_bar ) {
	$user = wp_get_current_user();

	if ( ! in_array( 'author', (array) $user->roles, true ) ) {
		return;
	}

	$wp_admin_bar->remove_node( 'new-links' );
	$wp_admin_bar->remove_node( 'new-concentration' );
	$wp_admin_bar->remove_node( 'new-services' );
}
add_action( 'admin_bar_menu', 'opehuone_author_admin_bar', 999 );

// Redirect Author away from restricted admin screens.
function opehuone_author_restrict_admin_screens( $screen ) {
	$user = wp_get_current_user();

	if ( ! in_array( 'author', (array) $user->roles, true ) ) {
		return;
	}

	$restricted_screens = array(
		'tools',
		'options-general',
	);

	$restricted_post_types = array(
		'links',
		'concentration',
		'services',
	);

	if ( in_array( $screen->id, $restricted_screens, true ) || in_array( $screen->post_type, $restricted_post_types, true ) ) {
		wp_safe_redirect( admin_url() );
		exit;
	}
}
add_action( 'current_screen', 'opehuone_author_restrict_admin_screens' );

// ************ Päätoimittaja (editor) role ************

// Clean admin menu
function opehuone_editor_admin_menu() {
	$user = wp_get_current_user();

	if ( ! in_array( 'editor', (array) $user->roles, true ) ) {
		return;
	}

	remove_menu_page( 'tools.php' );
	remove_menu_page( 'options-general.php' ); // because "Opehuone asetukset" is under "Asetukset" - thats why "Asetukset" is shown at admin menu (even without capability)
	remove_menu_page( 'edit.php?post_type=links' );
	remove_menu_page( 'edit.php?post_type=concentration' );
	remove_menu_page( 'edit.php?post_type=services' );

}
add_action( 'admin_menu', 'opehuone_editor_admin_menu', 999 );

// Clean admin bar
function opehuone_editor_admin_bar( $wp_admin_bar ) {
	$user = wp_get_current_user();

	if ( ! in_array( 'editor', (array) $user->roles, true ) ) {
		return;
	}

	$wp_admin_bar->remove_node( 'new-links' );
	$wp_admin_bar->remove_node( 'new-concentration' );
	$wp_admin_bar->remove_node( 'new-services' );
}
add_action( 'admin_bar_menu', 'opehuone_editor_admin_bar', 999 );

// Redirect Editor away from restricted admin screens.
function opehuone_editor_restrict_admin_screens( $screen ) {
	$user = wp_get_current_user();

	if ( ! in_array( 'editor', (array) $user->roles, true ) ) {
		return;
	}

	$restricted_screens = array(
		'tools',
		'options-general',
	);

	$restricted_post_types = array(
		'links',
		'concentration',
		'services',
	);

	if ( in_array( $screen->id, $restricted_screens, true ) || in_array( $screen->post_type, $restricted_post_types, true ) ) {
		wp_safe_redirect( admin_url() );
		exit;
	}
}
add_action( 'current_screen', 'opehuone_editor_restrict_admin_screens' );
