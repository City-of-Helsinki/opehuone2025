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

/**
 * Give the sisällöntuottaja (kirjoittaja / author) role capabilities for pages, including other users pages (this allows authors to add parent/top page relationships).
 */
function opehuone_author_page_caps() {
	
	$role = get_role( 'author' );

	if ( ! $role ) {
		return;
	}

	$caps = array(
		'edit_pages',
		'edit_published_pages',
		'edit_others_pages',
		'publish_pages',
		'delete_pages',
		'delete_published_pages',
		'delete_others_pages',
	);

	foreach ( $caps as $cap ) {
		if ( ! $role->has_cap( $cap ) ) {
			$role->add_cap( $cap );
		}
	}
}
add_action( 'init', 'opehuone_author_page_caps' );