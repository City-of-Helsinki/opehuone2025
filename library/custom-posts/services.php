<?php

/**
 * Custom post type registering, one per file
 *
 * Use: https://generatewp.com/post-type/
 */
function register_cpt_services() {

    $labels = array(
        'name'                  => _x( 'Palvelut', 'Post Type yleinen nimi', 'oppijaportaali' ),
        'singular_name'         => _x( 'Palvelu', 'Post Type yksittäinen nimi', 'oppijaportaali' ),
        'menu_name'             => __( 'Palvelut', 'oppijaportaali' ),
        'name_admin_bar'        => __( 'Palvelut', 'oppijaportaali' ),
        'archives'              => __( 'Arkistot', 'oppijaportaali' ),
        'attributes'            => __( 'Arkistot', 'oppijaportaali' ),
        'parent_item_colon'     => __( 'Yläsivu:', 'oppijaportaali' ),
        'all_items'             => __( 'Kaikki palvelut', 'oppijaportaali' ),
        'add_new'               => __( 'Lisää uusi palvelu', 'oppijaportaali' ),
        'new_item'              => __( 'Uusi palvelu', 'oppijaportaali' ),
        'edit_item'             => __( 'Muokkaa palvelua', 'oppijaportaali' ),
        'update_item'           => __( 'Päivitä palvelu', 'oppijaportaali' ),
        'view_item'             => __( 'Katso palvelu', 'oppijaportaali' ),
        'view_items'            => __( 'Katso palvelut', 'oppijaportaali' ),
        'search_items'          => __( 'Etsi palveluja', 'oppijaportaali' ),
        'not_found'             => __( 'Ei löytynyt', 'oppijaportaali' ),
        'not_found_in_trash'    => __( 'Ei löytynyt roskakorista', 'oppijaportaali' ),
        'featured_image'        => __( 'Julkaisun kuva', 'oppijaportaali' ),
        'set_featured_image'    => __( 'Aseta palvelun kuva', 'oppijaportaali' ),
        'remove_featured_image' => __( 'Poista palvelun kuva', 'oppijaportaali' ),
        'use_featured_image'    => __( 'Käytä palvelun kuvana', 'oppijaportaali' ),
        'insert_into_item'      => __( 'Lisää palveluun', 'oppijaportaali' ),
        'uploaded_to_this_item' => __( 'Ladattu tähän palveluun', 'oppijaportaali' ),
        'items_list'            => __( 'Palveluiden listaus', 'oppijaportaali' ),
        'items_list_navigation' => __( 'Palveluiden navigointi', 'oppijaportaali' ),
        'filter_items_list'     => __( 'Suodata palveluja', 'oppijaportaali' ),
    );
    $args   = array(
        'label'               => __( 'Palvelut', 'oppijaportaali' ),
        'description'         => __( 'Palvelut kuvaus', 'oppijaportaali' ),
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
    );
    register_post_type( 'services', $args );
}

add_action( 'init', 'register_cpt_services', 0 );
