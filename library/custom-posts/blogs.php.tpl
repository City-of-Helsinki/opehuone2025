<?php

namespace Opehuone\CustomPosts\Blogs;

/**
 * Custom post type registering, one per file
 *
 * Use: https://generatewp.com/post-type/
 */
function custom_post_type() {

	$labels = array(
		'name'                  => _x( 'Blogit', 'Post Type General Name', 'blocksmith' ),
		'singular_name'         => _x( 'Blogi', 'Post Type Singular Name', 'blocksmith' ),
		'menu_name'             => __( 'Blogit', 'blocksmith' ),
		'name_admin_bar'        => __( 'Blogit', 'blocksmith' ),
		'archives'              => __( 'Arkistot', 'blocksmith' ),
		'attributes'            => __( 'Arkistot', 'blocksmith' ),
		'parent_item_colon'     => __( 'Yläsivu:', 'blocksmith' ),
		'all_items'             => __( 'Lisää uusi', 'blocksmith' ),
		'add_new'               => __( 'Lisää uusi', 'blocksmith' ),
		'new_item'              => __( 'Uusi', 'blocksmith' ),
		'edit_item'             => __( 'Muokkaa', 'blocksmith' ),
		'update_item'           => __( 'Päivitä', 'blocksmith' ),
		'view_item'             => __( 'Katso julkaisu', 'blocksmith' ),
		'view_items'            => __( 'Katso julkaisut', 'blocksmith' ),
		'search_items'          => __( 'Etsi julkaisuja', 'blocksmith' ),
		'not_found'             => __( 'Ei löytynyt', 'blocksmith' ),
		'not_found_in_trash'    => __( 'Ei löytynyt roskakorista', 'blocksmith' ),
		'featured_image'        => __( 'Julkaisun kuva', 'blocksmith' ),
		'set_featured_image'    => __( 'Aseta julkaisun kuva', 'blocksmith' ),
		'remove_featured_image' => __( 'Poista julkaisun kuva', 'blocksmith' ),
		'use_featured_image'    => __( 'Käytä julkaisun kuvana', 'blocksmith' ),
		'insert_into_item'      => __( 'Lisää julkaisuun', 'blocksmith' ),
		'uploaded_to_this_item' => __( 'Ladattu tähän julkaisuun', 'blocksmith' ),
		'items_list'            => __( 'Julkaisujen listaus', 'blocksmith' ),
		'items_list_navigation' => __( 'Julkaisujen vavigointi', 'blocksmith' ),
		'filter_items_list'     => __( 'Suodata julkaisuja', 'blocksmith' ),
	);
	$args   = array(
		'label'           => __( 'Blogit', 'blocksmith' ),
		'labels'          => $labels,
		'public'          => true,
		'hierarchical'    => true,
		'menu_position'   => 20,
		'show_in_rest'    => true,
		'supports'        => [
			'title',
			'editor',
			'excerpt',
			'thumbnail',
			'author'
		],
		'rewrite'         => [
			'with_front' => false,
			'slug'       => 'blogit'
		],
		'has_archive'     => true,
		'taxonomies'      => array( 'post_tag' ),
		'capability_type' => 'post',
	);
	register_post_type( 'blogs', $args );

}

add_action( 'init',  __NAMESPACE__ . '\\custom_post_type', 0 );
