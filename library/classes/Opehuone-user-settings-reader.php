<?php

class Opehuone_user_settings_reader {
	private static $meta_key = 'user_opehuone_settings';

	public static function get_user_settings_key( $key ) {
		if ( empty( $key ) || ! is_user_logged_in() ) {
			return false;
		}

		$current_user = wp_get_current_user();

		$user_settings = get_user_meta( $current_user->ID, self::$meta_key, true );

		if ( ! $user_settings ) {
			return false;
		}

		return $user_settings['what_to_show_categories'][ $key ];
	}
}
