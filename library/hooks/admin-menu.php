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
	remove_menu_page( 'tablepress' ); // TablePress plugin

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
	$wp_admin_bar->remove_node( 'new-tablepress-table' );
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

	// Plugin pages are matched by the "page" query parameter prefix (includes subpages).
	$restricted_page_prefixes = array(
		'tablepress', // TablePress plugin
	);

	$is_restricted = in_array( $screen->id, $restricted_screens, true ) || in_array( $screen->post_type, $restricted_post_types, true );

	if ( ! $is_restricted && isset( $_GET['page'] ) ) {
		$page = sanitize_text_field( wp_unslash( $_GET['page'] ) );

		foreach ( $restricted_page_prefixes as $prefix ) {
			if ( 0 === strpos( $page, $prefix ) ) {
				$is_restricted = true;
				break;
			}
		}
	}

	if ( $is_restricted ) {
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
	remove_menu_page( 'tablepress' ); // TablePress plugin

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
	$wp_admin_bar->remove_node( 'new-tablepress-table' );
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

	// Plugin pages are matched by the "page" query parameter prefix (includes subpages).
	$restricted_page_prefixes = array(
		'tablepress', // TablePress plugin
	);

	$is_restricted = in_array( $screen->id, $restricted_screens, true ) || in_array( $screen->post_type, $restricted_post_types, true );

	if ( ! $is_restricted && isset( $_GET['page'] ) ) {
		$page = sanitize_text_field( wp_unslash( $_GET['page'] ) );

		foreach ( $restricted_page_prefixes as $prefix ) {
			if ( 0 === strpos( $page, $prefix ) ) {
				$is_restricted = true;
				break;
			}
		}
	}

	if ( $is_restricted ) {
		wp_safe_redirect( admin_url() );
		exit;
	}
}
add_action( 'current_screen', 'opehuone_editor_restrict_admin_screens' );

// Enable codeEditing for admins and editors (HDS plugin disables this as default)
add_filter( 'block_editor_settings_all', function( $settings ) {
    $user          = wp_get_current_user();
    $allowed_roles = array( 'administrator', 'editor' );

    if ( array_intersect( $allowed_roles, (array) $user->roles ) ) {
        $settings['codeEditingEnabled'] = true;
    }
    return $settings;
}, 999 );


// ************ Poikkeusilmoitukset: access by capability ************

function opehuone_service_notices_capability() {
	
	// Roles that can manage service notices
	$allowed_roles = array( 'administrator', 'editor', 'ilmoittaja' );
	$all_roles     = array( 'administrator', 'editor', 'ilmoittaja', 'author', 'subscriber' );

	foreach ( $all_roles as $role_name ) {
		$role = get_role( $role_name );
		if ( ! $role ) {
			continue;
		}

		$should_have = in_array( $role_name, $allowed_roles, true );

		if ( $should_have && ! $role->has_cap( 'manage_service_notices' ) ) {
			$role->add_cap( 'manage_service_notices' );
		} elseif ( ! $should_have && $role->has_cap( 'manage_service_notices' ) ) {
			$role->remove_cap( 'manage_service_notices' );
		}
	}
}
add_action( 'init', 'opehuone_service_notices_capability' );
