<?php

namespace Opehuone\CustomTaxonomies\Taxname;

/**
 * Registering taxonomy: taxonomy_name
 * One per file
 */

function init_taxonomy() {
	/**
	 * Change labels according to taxonomy
	 */
	$labels = array(
		'name'                       => _x( 'Blogin kirjoittajat', 'taxonomy_name taxonomy general name', 'blocksmith' ),
		'singular_name'              => _x( 'Blogin kirjoittaja', 'taxonomy_name taxonomy singular name', 'blocksmith' ),
		'menu_name'                  => __( 'Blogin kirjoittajat', 'blocksmith' ),
		'all_items'                  => __( 'Kaikki blogin kirjoittajat', 'blocksmith' ),
		'parent_item'                => __( 'Blogin kirjoittaja', 'blocksmith' ),
		'parent_item_colon'          => __( 'Blogin kirjoittaja:', 'blocksmith' ),
		'new_item_name'              => __( 'Uusi blogin kirjoittaja', 'blocksmith' ),
		'add_new_item'               => __( 'Lisää uusi blogin kirjoittaja', 'blocksmith' ),
		'edit_item'                  => __( 'Muokkaa kirjoittajaa', 'blocksmith' ),
		'update_item'                => __( 'Päivitä blogin kirjoittaja', 'blocksmith' ),
		'view_item'                  => __( 'Katso blogin kirjoittaja', 'blocksmith' ),
		'separate_items_with_commas' => __( 'Erottele pilkuilla', 'blocksmith' ),
		'add_or_remove_items'        => __( 'Lisää ja poista kirjoittajia', 'blocksmith' ),
		'choose_from_most_used'      => __( 'Valitse eniten käytetyistä', 'blocksmith' ),
		'popular_items'              => __( 'Suositut blogin kirjoittajat', 'blocksmith' ),
		'search_items'               => __( 'Etsi', 'blocksmith' ),
		'not_found'                  => __( 'Ei löytynyt', 'blocksmith' ),
		'no_terms'                   => __( 'Ei blogin kirjoittajia', 'blocksmith' ),
		'items_list'                 => __( 'Kirjoittajien listaus', 'blocksmith' ),
		'items_list_navigation'      => __( 'Kirjoittajien navigointi', 'blocksmith' ),
	);
	$args   = array(
		'labels'              => $labels,
		'hierarchical'        => false,
		'public'              => true,
		'show_ui'             => true,
		'show_admin_column'   => true,
		'show_in_nav_menus'   => true,
		'show_tagcloud'       => false,
		'show_in_rest'        => true,
	);
	register_taxonomy( 'taxonomy_name', [ 'post' ], $args );

}

add_action( 'init', __NAMESPACE__ . '\\init_taxonomy', 0 );
