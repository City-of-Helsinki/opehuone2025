<?php

namespace Opehuone\CustomTaxonomies\PostTheme;

/**
 * Registering taxonomy: taxonomy_name
 * One per file
 */

function init_taxonomy() {
	/**
	 * Change labels according to taxonomy
	 */
	$labels = array(
		'name'                       => _x( 'Aiheet', 'taxonomy general name', 'helsinki-universal' ),
		'singular_name'              => _x( 'Aihe', 'taxonomy singular name', 'helsinki-universal' ),
		'search_items'               => __( 'Etsi aiheita', 'helsinki-universal' ),
		'all_items'                  => __( 'Kaikki aiheet', 'helsinki-universal' ),
		'parent_item'                => __( 'Aiheen yläsivu', 'helsinki-universal' ),
		'parent_item_colon'          => __( 'Aiheen yläsivu:', 'helsinki-universal' ),
		'edit_item'                  => __( 'Muokkaa aihetta', 'helsinki-universal' ),
		'update_item'                => __( 'Päivitä aihetta', 'helsinki-universal' ),
		'add_new_item'               => __( 'Lisää uusi aihe', 'helsinki-universal' ),
		'new_item_name'              => __( 'Uusi aihe', 'helsinki-universal' ),
		'menu_name'                  => __( 'Aiheet', 'helsinki-universal' ),
		'view_item'                  => __( 'Katso aihe', 'helsinki-universal' ),
		'separate_items_with_commas' => __( 'Erottele pilkuilla', 'helsinki-universal' ),
		'add_or_remove_items'        => __( 'Lisää ja poista aiheista', 'helsinki-universal' ),
		'choose_from_most_used'      => __( 'Valitse eniten käytetyistä', 'helsinki-universal' ),
		'not_found'                  => __( 'Ei löytynyt', 'helsinki-universal' ),
		'no_terms'                   => __( 'Ei aiheita', 'helsinki-universal' ),
		'items_list'                 => __( 'Aiheiden listaus', 'helsinki-universal' ),
		'items_list_navigation'      => __( 'Aiheiden navigointi', 'helsinki-universal' ),
	);
	$args   = array(
		'public'             => false,
		'hierarchical'       => true,
		'labels'             => $labels,
		'show_ui'            => true,
		'show_admin_column'  => true,
		'query_var'          => true,
		'publicly_queryable' => true,
		'show_in_rest'       => true,
		'show_in_nav_menus'  => false,
		'show_tagcloud'      => false,
		'show_admin_column'  => true,

	);
	register_taxonomy( 'post_theme', [ 'post' ], $args );

}

add_action( 'init', __NAMESPACE__ . '\\init_taxonomy', 0 );
