<?php

namespace Opehuone\CustomTaxonomies\Cornerlabels;

/**
 * Registering taxonomy: taxonomy_name
 * One per file
 */

function init_taxonomy() {
	/**
	 * Change labels according to taxonomy
	 */
	$labels = array(
		'name'                       => _x( 'Oppiasteet', 'taxonomy general name', 'helsinki-universal' ),
		'singular_name'              => _x( 'Oppiaste', 'taxonomy singular name', 'helsinki-universal' ),
		'search_items'               => __( 'Etsi oppiasteita', 'helsinki-universal' ),
		'all_items'                  => __( 'Kaikki oppiasteet', 'helsinki-universal' ),
		'parent_item'                => __( 'Oppiasteen yläsivu', 'helsinki-universal' ),
		'parent_item_colon'          => __( 'Oppiasteen yläsivu:', 'helsinki-universal' ),
		'edit_item'                  => __( 'Muokkaa oppiastetta', 'helsinki-universal' ),
		'update_item'                => __( 'Päivitä oppiaste', 'helsinki-universal' ),
		'add_new_item'               => __( 'Lisää uusi oppiaste', 'helsinki-universal' ),
		'new_item_name'              => __( 'Uusi oppiaste', 'helsinki-universal' ),
		'menu_name'                  => __( 'Oppiasteet', 'helsinki-universal' ),
		'view_item'                  => __( 'Katso oppiaste', 'helsinki-universal' ),
		'separate_items_with_commas' => __( 'Erottele pilkuilla', 'helsinki-universal' ),
		'add_or_remove_items'        => __( 'Lisää ja poista oppiasteita', 'helsinki-universal' ),
		'choose_from_most_used'      => __( 'Valitse eniten käytetyistä', 'helsinki-universal' ),
		'popular_items'              => __( 'Suositut oppiasteet', 'helsinki-universal' ),
		'not_found'                  => __( 'Ei löytynyt', 'helsinki-universal' ),
		'no_terms'                   => __( 'Ei oppiasteita', 'helsinki-universal' ),
		'items_list'                 => __( 'Oppiasteiden listaus', 'helsinki-universal' ),
		'items_list_navigation'      => __( 'Oppiasteiden navigointi', 'helsinki-universal' ),
	);
	$args   = array(
		'public'             => false,
		'hierarchical'       => true,
		'labels'             => $labels,
		'show_ui'            => true,
		'show_admin_column'  => true,
		'query_var'          => true,
		'publicly_queryable' => true,
		'show_in_nav_menus'  => true,
		'show_tagcloud'      => false,
		'show_in_rest'       => true,
	);
	register_taxonomy( 'cornerlabels', [ 'post', 'training', 'page', 'links', 'services' ], $args );

}

add_action( 'init', __NAMESPACE__ . '\\init_taxonomy', 0 );
