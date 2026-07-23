<?php

/**
 * Custom post type registering, one per file
 *
 * Use: https://generatewp.com/post-type/
 */
function register_cpt_tools() {

    $labels = array(
        'name'                  => _x( 'Omat työkalut', 'Post Type yleinen nimi', 'helsinki-universal' ),
        'singular_name'         => _x( 'Työkalu', 'Post Type yksittäinen nimi', 'helsinki-universal' ),
        'menu_name'             => __( 'Omat työkalut', 'helsinki-universal' ),
        'name_admin_bar'        => __( 'Työkalut', 'helsinki-universal' ),
        'archives'              => __( 'Arkistot', 'helsinki-universal' ),
        'attributes'            => __( 'Arkistot', 'helsinki-universal' ),
        'parent_item_colon'     => __( 'Yläsivu:', 'helsinki-universal' ),
        'all_items'             => __( 'Kaikki työkalut', 'helsinki-universal' ),
        'add_new'               => __( 'Lisää uusi työkalu', 'helsinki-universal' ),
        'new_item'              => __( 'Uusi työkalu', 'helsinki-universal' ),
        'add_new_item'          => __( 'Lisää uusi työkalu', 'helsinki-universal' ),
        'edit_item'             => __( 'Muokkaa työkalua', 'helsinki-universal' ),
        'update_item'           => __( 'Päivitä työkalu', 'helsinki-universal' ),
        'view_item'             => __( 'Katso työkalu', 'helsinki-universal' ),
        'view_items'            => __( 'Katso työkalut', 'helsinki-universal' ),
        'search_items'          => __( 'Etsi työkaluja', 'helsinki-universal' ),
        'not_found'             => __( 'Ei löytynyt', 'helsinki-universal' ),
        'not_found_in_trash'    => __( 'Ei löytynyt roskakorista', 'helsinki-universal' ),
        'featured_image'        => __( 'Julkaisun kuva', 'helsinki-universal' ),
        'set_featured_image'    => __( 'Aseta työkalun kuva', 'helsinki-universal' ),
        'remove_featured_image' => __( 'Poista työkalun kuva', 'helsinki-universal' ),
        'use_featured_image'    => __( 'Käytä työkalun kuvana', 'helsinki-universal' ),
        'insert_into_item'      => __( 'Lisää työkaluun', 'helsinki-universal' ),
        'uploaded_to_this_item' => __( 'Ladattu tähän työkaluun', 'helsinki-universal' ),
        'items_list'            => __( 'Työkalujen listaus', 'helsinki-universal' ),
        'items_list_navigation' => __( 'Työkalujen navigointi', 'helsinki-universal' ),
        'filter_items_list'     => __( 'Suodata työkaluja', 'helsinki-universal' ),
    );
    $args   = array(
        'label'               => __( 'Työkalut', 'helsinki-universal' ),
        'description'         => __( 'Työkalut kuvaus', 'helsinki-universal' ),
        'labels'              => $labels,
        'supports'            => array( 'title' ),
        'hierarchical'        => true,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 5,
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => true,
        'can_export'          => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-hammer',
    );
    register_post_type( 'tools', $args );
}

add_action( 'init', 'register_cpt_tools', 0 );
