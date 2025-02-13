<?php

namespace Opehuone\CustomTaxonomies\TrainingTheme;

/**
 * Registering taxonomy: taxonomy_name
 * One per file
 */

function init_taxonomy() {
	/**
	 * Change labels according to taxonomy
	 */
	$labels = array(
		'name'                       => _x( 'Koulutusteemat', 'taxonomy general name', 'helsinki-universal' ),
		'singular_name'              => _x( 'Koulutusteema', 'taxonomy singular name', 'helsinki-universal' ),
		'search_items'               => __( 'Etsi koulutusteemoja', 'helsinki-universal' ),
		'all_items'                  => __( 'Kaikki koulutusteemat', 'helsinki-universal' ),
		'parent_item'                => __( 'Koulutusteeman yläsivu', 'helsinki-universal' ),
		'parent_item_colon'          => __( 'Koulutusteeman yläsivu:', 'helsinki-universal' ),
		'edit_item'                  => __( 'Muokkaa koulutusteemaa', 'helsinki-universal' ),
		'update_item'                => __( 'Päivitä koulutusteema', 'helsinki-universal' ),
		'add_new_item'               => __( 'Lisää uusi koulutusteema', 'helsinki-universal' ),
		'new_item_name'              => __( 'Uusi koulutusteema', 'helsinki-universal' ),
		'menu_name'                  => __( 'Koulutusteemat', 'helsinki-universal' ),
		'view_item'                  => __( 'Katso koulutusteema', 'helsinki-universal' ),
		'separate_items_with_commas' => __( 'Erottele pilkuilla', 'helsinki-universal' ),
		'add_or_remove_items'        => __( 'Lisää ja poista teemoista', 'helsinki-universal' ),
		'choose_from_most_used'      => __( 'Valitse eniten käytetyistä', 'helsinki-universal' ),
		'popular_items'              => __( 'Suositut teemat', 'helsinki-universal' ),
		'not_found'                  => __( 'Ei löytynyt', 'helsinki-universal' ),
		'no_terms'                   => __( 'Ei teemoja', 'helsinki-universal' ),
		'items_list'                 => __( 'Teemojen listaus', 'helsinki-universal' ),
		'items_list_navigation'      => __( 'Teemojen navigointi', 'helsinki-universal' ),
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
		'show_in_nav_menus'  => true,
		'show_tagcloud'      => false,
	);
	register_taxonomy( 'training_theme', [ 'training' ], $args );

}

add_action( 'init', __NAMESPACE__ . '\\init_taxonomy', 0 );
