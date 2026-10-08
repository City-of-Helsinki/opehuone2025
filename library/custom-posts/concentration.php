<?php

/**
 * Custom post type registering, one per file
 *
 * Use: https://generatewp.com/post-type/
 */
function register_cpt_concentration() {

    $labels = array(
        'name'                  => _x( 'Taukoharjoitukset', 'Post Type yleinen nimi', 'oppijaportaali' ),
        'singular_name'         => _x( 'Taukoharjoitus', 'Post Type yksittäinen nimi', 'oppijaportaali' ),
        'menu_name'             => __( 'Taukoharjoitukset', 'oppijaportaali' ),
        'name_admin_bar'        => __( 'Taukoharjoitus', 'oppijaportaali' ),
        'archives'              => __( 'Arkistot', 'oppijaportaali' ),
        'attributes'            => __( 'Arkistot', 'oppijaportaali' ),
        'parent_item_colon'     => __( 'Yläsivu:', 'oppijaportaali' ),
        'all_items'             => __( 'Kaikki taukoharjoitukset', 'oppijaportaali' ),
        'add_new'               => __( 'Lisää uusi taukoharjoitus', 'oppijaportaali' ),
        'new_item'              => __( 'Uusi taukoharjoitus', 'oppijaportaali' ),
        'edit_item'             => __( 'Muokkaa taukoharjoitusta', 'oppijaportaali' ),
        'update_item'           => __( 'Päivitä taukoharjoitus', 'oppijaportaali' ),
        'view_item'             => __( 'Katso taukoharjoitus', 'oppijaportaali' ),
        'view_items'            => __( 'Katso taukoharjoitukset', 'oppijaportaali' ),
        'search_items'          => __( 'Etsi taukoharjoituksia', 'oppijaportaali' ),
        'not_found'             => __( 'Ei löytynyt', 'oppijaportaali' ),
        'not_found_in_trash'    => __( 'Ei löytynyt roskakorista', 'oppijaportaali' ),
        'featured_image'        => __( 'Taukoharjoituksen kuva', 'oppijaportaali' ),
        'set_featured_image'    => __( 'Aseta taukoharjoituksen kuva', 'oppijaportaali' ),
        'remove_featured_image' => __( 'Poista taukoharjoituksen kuva', 'oppijaportaali' ),
        'use_featured_image'    => __( 'Käytä taukoharjoituksen kuvana', 'oppijaportaali' ),
        'insert_into_item'      => __( 'Lisää taukoharjoitukseen', 'oppijaportaali' ),
        'uploaded_to_this_item' => __( 'Ladattu tähän taukoharjoitukseen', 'oppijaportaali' ),
        'items_list'            => __( 'Taukoharjoitusten listaus', 'oppijaportaali' ),
        'items_list_navigation' => __( 'Taukoharjoitusten navigointi', 'oppijaportaali' ),
        'filter_items_list'     => __( 'Suodata taukoharjoituksia', 'oppijaportaali' ),
    );
    $args   = array(
        'label'               => __( 'Taukoharjoitukset', 'oppijaportaali' ),
        'description'         => __( 'Taukoharjoitusten kuvaus', 'oppijaportaali' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'author', 'thumbnail' ),
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
    );
    register_post_type( 'concentration', $args );
}

add_action( 'init', 'register_cpt_concentration', 0 );
