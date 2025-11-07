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
		'name'                       => _x( 'Koulutusasteet', 'taxonomy general name', 'helsinki-universal' ),
		'singular_name'              => _x( 'Koulutusaste', 'taxonomy singular name', 'helsinki-universal' ),
		'search_items'               => __( 'Etsi koulutusasteita', 'helsinki-universal' ),
		'all_items'                  => __( 'Kaikki koulutusasteet', 'helsinki-universal' ),
		'parent_item'                => __( 'Koulutusasteen yläsivu', 'helsinki-universal' ),
		'parent_item_colon'          => __( 'Koulutusasteen yläsivu:', 'helsinki-universal' ),
		'edit_item'                  => __( 'Muokkaa koulutusastetta', 'helsinki-universal' ),
		'update_item'                => __( 'Päivitä koulutusaste', 'helsinki-universal' ),
		'add_new_item'               => __( 'Lisää uusi koulutusaste', 'helsinki-universal' ),
		'new_item_name'              => __( 'Uusi koulutusaste', 'helsinki-universal' ),
		'menu_name'                  => __( 'Koulutusasteet', 'helsinki-universal' ),
		'view_item'                  => __( 'Katso koulutusaste', 'helsinki-universal' ),
		'separate_items_with_commas' => __( 'Erottele pilkuilla', 'helsinki-universal' ),
		'add_or_remove_items'        => __( 'Lisää ja poista koulutusasteita', 'helsinki-universal' ),
		'choose_from_most_used'      => __( 'Valitse eniten käytetyistä', 'helsinki-universal' ),
		'popular_items'              => __( 'Suositut koulutusasteet', 'helsinki-universal' ),
		'not_found'                  => __( 'Ei löytynyt', 'helsinki-universal' ),
		'no_terms'                   => __( 'Ei koulutusasteita', 'helsinki-universal' ),
		'items_list'                 => __( 'Koulutusasteiden listaus', 'helsinki-universal' ),
		'items_list_navigation'      => __( 'Koulutusasteiden navigointi', 'helsinki-universal' ),
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
