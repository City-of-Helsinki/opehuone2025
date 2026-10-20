<?php
/**
 * Hide specific page templates from the template dropdown.
 *
 * @package Opehuone
 */

/**
 * Filter the list of selectable page templates.
 *
 * @param array $templates Array of page templates
 * @return array Modified array of page templates
 */
function opehuone_unset_custom_templates( $templates ) {

	// Hide from every user
	$hidden_for_all = array(
		'custom-templates/dock-settings.php',
		'custom-templates/with-sidemenu.php',
		'template/landing-page.php',
		'template/no-sidebar.php',
	);

	foreach ( $hidden_for_all as $template ) {
		unset( $templates[ $template ] );
	}

	// Show only for admins
	if ( ! current_user_can( 'manage_options' ) ) {
		unset( $templates['custom-templates/user-settings.php'] );
	}

	// Show for everyone except authors
	$user = wp_get_current_user();
	if ( in_array( 'author', (array) $user->roles, true ) ) {
		unset( $templates['custom-templates/landing-page.php'] );
	}

	return $templates;
}
add_filter( 'theme_page_templates', 'opehuone_unset_custom_templates' );
