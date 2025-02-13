<?php

namespace Opehuone\CustomTaxonomies\ArticleLang;

/**
 * Registering taxonomy: taxonomy_name
 * One per file
 */

function init_taxonomy() {
	/**
	 * Change labels according to taxonomy
	 */
	$labels = array(
		'name'                       => _x( 'Kielet', 'taxonomy general name', 'helsinki-universal' ),
		'singular_name'              => _x( 'Kieli', 'taxonomy singular name', 'helsinki-universal' ),
		'search_items'               => __( 'Etsi kieliä', 'helsinki-universal' ),
		'all_items'                  => __( 'Kaikki kielet', 'helsinki-universal' ),
		'parent_item'                => __( 'Kielen yläsivu', 'helsinki-universal' ),
		'parent_item_colon'          => __( 'Kielen yläsivu:', 'helsinki-universal' ),
		'edit_item'                  => __( 'Muokkaa kieltä', 'helsinki-universal' ),
		'update_item'                => __( 'Päivitä kieli', 'helsinki-universal' ),
		'add_new_item'               => __( 'Lisää uusi kieli', 'helsinki-universal' ),
		'new_item_name'              => __( 'Uusi kieli', 'helsinki-universal' ),
		'menu_name'                  => __( 'Kielet', 'helsinki-universal' ),
		'view_item'                  => __( 'Katso kieltä', 'helsinki-universal' ),
		'separate_items_with_commas' => __( 'Erottele pilkuilla', 'helsinki-universal' ),
		'add_or_remove_items'        => __( 'Lisää ja poista kielistä', 'helsinki-universal' ),
		'choose_from_most_used'      => __( 'Valitse eniten käytetyistä', 'helsinki-universal' ),
		'popular_items'              => __( 'Suositut kielet', 'helsinki-universal' ),
		'not_found'                  => __( 'Ei löytynyt', 'helsinki-universal' ),
		'no_terms'                   => __( 'Ei kieliä', 'helsinki-universal' ),
		'items_list'                 => __( 'Kielien listaus', 'helsinki-universal' ),
		'items_list_navigation'      => __( 'Kielien navigointi', 'helsinki-universal' ),
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
	register_taxonomy( 'article_lang', [ 'post', 'training', 'page' ], $args );

}

add_action( 'init', __NAMESPACE__ . '\\init_taxonomy', 0 );
