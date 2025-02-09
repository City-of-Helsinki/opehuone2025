<?php

namespace Opehuone\Utils;

function get_user_favs() {
	if ( ! is_user_logged_in() ) {
		return [];
	}
	$user = wp_get_current_user();

	$posts = get_user_meta( $user->ID, 'opehuone_favs', true ) ? get_user_meta( $user->ID, 'opehuone_favs', true ) : [];

	return $posts;
}
