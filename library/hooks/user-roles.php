<?php
/**
 * Register new user role: Ilmoittaja.
 *
 * @package Opehuone
 */

function opehuone_add_ilmoittaja_role() {

	if ( get_role( 'ilmoittaja' ) ) {
		return;
	}

	add_role(
		'ilmoittaja',
		'Ilmoittaja',
		array(
			'read'                   => true,
			'edit_posts'             => true,
			'delete_posts'           => true,
			'edit_published_posts'   => true,
			'delete_published_posts' => true,
			'publish_posts'          => true,
			'upload_files'           => true,
		)
	);
}
add_action( 'init', 'opehuone_add_ilmoittaja_role' );

// Remove role contributor / avustaja
function opehuone_remove_contributor_role() {
	if ( get_role( 'contributor' ) ) {
		remove_role( 'contributor' );
	}
}
add_action( 'init', 'opehuone_remove_contributor_role' );
