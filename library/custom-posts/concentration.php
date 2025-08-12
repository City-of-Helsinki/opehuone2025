<?php

/**
 * Custom post type registering, one per file
 *
 * Use: https://generatewp.com/post-type/
 */
function register_cpt_concentration() {

    $labels = array(
        'name'                  => _x( 'Keskittymiset', 'Post Type yleinen nimi', 'oppijaportaali' ),
        'singular_name'         => _x( 'Keskittyminen', 'Post Type yksittäinen nimi', 'oppijaportaali' ),
        'menu_name'             => __( 'Keskittymiset', 'oppijaportaali' ),
        'name_admin_bar'        => __( 'Keskittymiset', 'oppijaportaali' ),
        'archives'              => __( 'Arkistot', 'oppijaportaali' ),
        'attributes'            => __( 'Arkistot', 'oppijaportaali' ),
        'parent_item_colon'     => __( 'Yläsivu:', 'oppijaportaali' ),
        'all_items'             => __( 'Kaikki Keskittymiset', 'oppijaportaali' ),
        'add_new'               => __( 'Lisää uusi keskittyminen', 'oppijaportaali' ),
        'new_item'              => __( 'Uusi keskittyminen', 'oppijaportaali' ),
        'edit_item'             => __( 'Muokkaa keskittymistä', 'oppijaportaali' ),
        'update_item'           => __( 'Päivitä keskittyminen', 'oppijaportaali' ),
        'view_item'             => __( 'Katso keskittyminen', 'oppijaportaali' ),
        'view_items'            => __( 'Katso keskittymiset', 'oppijaportaali' ),
        'search_items'          => __( 'Etsi keskittymisiä', 'oppijaportaali' ),
        'not_found'             => __( 'Ei löytynyt', 'oppijaportaali' ),
        'not_found_in_trash'    => __( 'Ei löytynyt roskakorista', 'oppijaportaali' ),
        'featured_image'        => __( 'Keskittymisen kuva', 'oppijaportaali' ),
        'set_featured_image'    => __( 'Aseta keskittymisen kuva', 'oppijaportaali' ),
        'remove_featured_image' => __( 'Poista keskittymisen kuva', 'oppijaportaali' ),
        'use_featured_image'    => __( 'Käytä keskittymisen kuvana', 'oppijaportaali' ),
        'insert_into_item'      => __( 'Lisää keskittymiseen', 'oppijaportaali' ),
        'uploaded_to_this_item' => __( 'Ladattu tähän keskittymiseen', 'oppijaportaali' ),
        'items_list'            => __( 'Keskittymisien listaus', 'oppijaportaali' ),
        'items_list_navigation' => __( 'Keskittymisien navigointi', 'oppijaportaali' ),
        'filter_items_list'     => __( 'Suodata keskittymisiä', 'oppijaportaali' ),
    );
    $args   = array(
        'label'               => __( 'Keskittymiset', 'oppijaportaali' ),
        'description'         => __( 'Keskittymisten kuvaus', 'oppijaportaali' ),
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
