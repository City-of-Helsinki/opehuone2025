<?php

namespace Opehuone\Hooks;

/**
 * Add scripts to head
 *
 * @hook wp_head
 */
add_action(
	'wp_head',
	function () {
		?>
		<?php
	},
	999
);

/**
 * Add scripts to footer
 *
 * @hook wp_footer
 */
add_action(
	'wp_footer',
	function () {
		?>
		<?php
	},
	999
);

/**
 * Add scripts after opening body
 */
add_action(
	'wp_body_open',
	function () {
		?>
		<?php
	}, 1
);

add_filter(
	'excerpt_more',
	function () {
		return '...';
	}
);

/**
 * Redirect non-logged in users, just uncomment hook to have normal functionality
 */
function redirect_non_logged_in() {
	// No need for this is local/development envs
	if ( \wp_get_environment_type() === 'local' || \wp_get_environment_type() === 'development' ) {
		return;
	}

	if ( ! \is_user_logged_in() ) {
		\wp_redirect( wp_login_url() );
		exit;
	}
}

//add_action( 'template_redirect', __NAMESPACE__ . '\\redirect_non_logged_in' );
