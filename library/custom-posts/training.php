<?php

namespace Opehuone\CustomPosts\Training;

function custom_post_type() {

	$labels = array(
		'name'                  => _x( 'Koulutukset', 'Post Type General Name', 'helsinki-universal' ),
		'singular_name'         => _x( 'Koulutus', 'Post Type Singular Name', 'helsinki-universal' ),
		'menu_name'             => __( 'Koulutukset', 'helsinki-universal' ),
		'name_admin_bar'        => __( 'Koulutukset', 'helsinki-universal' ),
		'archives'              => __( 'Arkistot', 'helsinki-universal' ),
		'attributes'            => __( 'Arkistot', 'helsinki-universal' ),
		'parent_item_colon'     => __( 'Yläsivu:', 'helsinki-universal' ),
		'all_items'             => __( 'Kaikki koulutukset', 'helsinki-universal' ),
		'add_new'               => __( 'Lisää uusi', 'helsinki-universal' ),
		'new_item'              => __( 'Uusi', 'helsinki-universal' ),
		'edit_item'             => __( 'Muokkaa', 'helsinki-universal' ),
		'update_item'           => __( 'Päivitä', 'helsinki-universal' ),
		'view_item'             => __( 'Katso julkaisu', 'helsinki-universal' ),
		'view_items'            => __( 'Katso julkaisut', 'helsinki-universal' ),
		'search_items'          => __( 'Etsi julkaisuja', 'helsinki-universal' ),
		'not_found'             => __( 'Ei löytynyt', 'helsinki-universal' ),
		'not_found_in_trash'    => __( 'Ei löytynyt roskakorista', 'helsinki-universal' ),
		'featured_image'        => __( 'Julkaisun kuva', 'helsinki-universal' ),
		'set_featured_image'    => __( 'Aseta julkaisun kuva', 'helsinki-universal' ),
		'remove_featured_image' => __( 'Poista julkaisun kuva', 'helsinki-universal' ),
		'use_featured_image'    => __( 'Käytä julkaisun kuvana', 'helsinki-universal' ),
		'insert_into_item'      => __( 'Lisää julkaisuun', 'helsinki-universal' ),
		'uploaded_to_this_item' => __( 'Ladattu tähän julkaisuun', 'helsinki-universal' ),
		'items_list'            => __( 'Julkaisujen listaus', 'helsinki-universal' ),
		'items_list_navigation' => __( 'Julkaisujen vavigointi', 'helsinki-universal' ),
		'filter_items_list'     => __( 'Suodata julkaisuja', 'helsinki-universal' ),
	);
	$args   = array(
		'label'           => __( 'Koulutukset', 'helsinki-universal' ),
		'labels'          => $labels,
		'public'          => true,
		'has_archive'     => true,
		'show_ui'         => true,
		'hierarchical'    => true,
		'menu_position'   => 20,
		'show_in_rest'    => true,
		'menu_icon'       => 'dashicons-clipboard',
		'supports'        => [
			'title',
			'editor',
			'excerpt',
			'custom-fields',
			'revisions',
            'thumbnail'
		],
		'capability_type' => 'post',
		'rewrite'         => [
			'slug'       => 'koulutukset',
			'with_front' => false
		],
	);

	// Register meta fields
	foreach ( get_meta_fields() as $meta_field ) {
		\register_post_meta(
			'training',
			$meta_field['key'],
			[
				'show_in_rest'      => true,
				'single'            => true,
				'type'              => $meta_field['type'] ?? 'string',
				'sanitize_callback' => 'wp_strip_all_tags',
			]
		);
	}

	\register_post_type( 'training', $args );

}

add_action( 'init', __NAMESPACE__ . '\\custom_post_type', 0 );

/**
 * Get meta fields
 *
 * @return array
 */
function get_meta_fields(): array {
	return [
		// Add needed post meta here...
		[ 'key' => 'training_start_datetime' ],
		[ 'key' => 'training_end_datetime' ],
		[ 'key' => 'training_draft_datetime' ],
		[ 'key' => 'training_theme_color' ],
		[ 'key' => 'training_type' ],
		[ 'key' => 'training_registration_deadline' ],
		[ 'key' => 'training_more_info' ],
		[ 'key' => 'training_registration_url' ],
        [ 'key' => 'training_schedule' ],
	];
}
