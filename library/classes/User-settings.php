<?php

class User_settings {
	private static $user_id;

	public function __construct() {
		$current_user  = wp_get_current_user();
		self::$user_id = $current_user->ID;
	}

	public static function get_user_dock( $key = 'user_dock_items' ) {
		$user_dock = get_user_meta( self::$user_id, $key, true ) ? get_user_meta( self::$user_id, $key, true ) : false;

		if ( false === $user_dock ) {
			// Need to get default dock set
			$new_dock = self::get_default_dock_items();

			update_user_meta( self::$user_id, $key, $new_dock );

			return $new_dock;
		} else {
			return $user_dock;
		}
	}

	public static function get_single_dock_items( $dock_id ) {
		$response = self::get_api_response( 'dock-items' );

		if ( false === $response ) {
			return false;
		}

		return $response->{$dock_id};
	}

	public static function get_api_response( $path = '' ) {
		$api_response      = wp_remote_get( get_rest_url() . "wp/v2/{$path}" );
		$api_response_body = wp_remote_retrieve_body( $api_response );

		if ( ! is_wp_error( $api_response_body ) ) {
			return json_decode( $api_response_body );
		} else {
			return false;
		}
	}

	public static function update_dock_sorting( $list, $user_id ) {
		$list_to_save = [];
		foreach ( $list as $dock_item ) {
			$this_row       = self::get_single_dock_items( $dock_item );
			$list_to_save[] = [
				'id'         => $dock_item,
				'title'      => $this_row->title,
				'first_char' => $this_row->first_char,
				'url'        => $this_row->url,
				'icon_url'   => $this_row->icon_url,
			];
		}
		update_user_meta( $user_id, 'user_dock_items', $list_to_save );
	}

	public static function update_user_settings( $data, $user_id ) {
		update_user_meta( $user_id, 'user_opehuone_settings', $data );
	}

	public function get_user_settings() {
		$user_settings = get_user_meta( self::$user_id, 'user_opehuone_settings', true ) ? get_user_meta( self::$user_id, 'user_opehuone_settings', true ) : false;

		if ( false === $user_settings ) {
			// Need to get default set
			$default_user_settings = [
				'theme'                   => 'light',
				'highlight_color'         => 'metro',
				'header_color'            => 'light',
				'what_to_show_categories' => [
					'category'     => Utils()->get_all_term_ids_of_taxonomy( 'category' ),
					'cornerlabels' => Oppiaste_settings_updater::get_oppiaste_array(),
					'article_lang' => Utils()->get_all_term_ids_of_taxonomy( 'article_lang' ),
				],
			];

			update_user_meta( self::$user_id, 'user_opehuone_settings', $default_user_settings );

			return $default_user_settings;
		} else {
			return $user_settings;
		}
	}

	public static function get_user_own_links( $user_id ) {
		$user_links = get_user_meta( $user_id, 'user_opehuone_own_links', true ) ? get_user_meta( $user_id, 'user_opehuone_own_links', true ) : false;

		if ( false === $user_links ) {
			// Need to get default set
			$default_user_own_links = [
				'removed_default_urls' => [],
				'added_custom_links'   => [],
			];

			update_user_meta( $user_id, 'user_opehuone_own_links', $default_user_own_links );

			return $default_user_own_links;
		} else {
			return $user_links;
		}
	}

	public static function get_default_dock_items() {
		$items = [];

		if ( have_rows( 'dock_items', 'option' ) ) {
			while ( have_rows( 'dock_items', 'option' ) ) {
				the_row();
				$dock_title = get_sub_field( 'dock_title' );
				$first_char = substr( $dock_title, 0, 1 );
				$dock_icon  = get_sub_field( 'dock_icon' );
				$icon_url   = wp_get_attachment_image_src( $dock_icon, 'full' );
				$dock_url   = get_sub_field( 'dock_url' );

				$args = [
					'id'         => sanitize_title( $dock_title ),
					'title'      => $dock_title,
					'url'        => $dock_url,
					'icon_url'   => $icon_url[0],
					'first_char' => $first_char,
				];

				$items[] = $args;
			}
		}

		return $items;
	}
}
