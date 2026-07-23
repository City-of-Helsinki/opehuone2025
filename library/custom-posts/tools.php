<?php

/**
 * Custom post type registering, one per file
 *
 * Use: https://generatewp.com/post-type/
 */
function register_cpt_tools() {

    $domain   = 'helsinki-universal';
    $plural   = __( 'Omat työkalut', $domain );
    $singular = __( 'Työkalu', $domain );

    $labels = array(
        'name'                  => _x( $plural, 'Post Type yleinen nimi', $domain ),
        'singular_name'         => _x( $singular, 'Post Type yksittäinen nimi', $domain ),
        'menu_name'             => $plural,
        'name_admin_bar'        => __( 'Työkalut', $domain ),
        'archives'              => __( 'Arkistot', $domain ),
        'attributes'            => __( 'Arkistot', $domain ),
        'parent_item_colon'     => __( 'Yläsivu:', $domain ),
        'all_items'             => sprintf( __( 'Kaikki %s', $domain ), lcfirst( $plural ) ),
        'add_new'               => sprintf( __( 'Lisää uusi %s', $domain ), lcfirst( $singular ) ),
        'new_item'              => sprintf( __( 'Uusi %s', $domain ), lcfirst( $singular ) ),
        'add_new_item'          => sprintf( __( 'Lisää uusi %s', $domain ), lcfirst( $singular ) ),
        'edit_item'             => sprintf( __( 'Muokkaa %s', $domain ), lcfirst( $singular ) ),
        'update_item'           => sprintf( __( 'Päivitä %s', $domain ), lcfirst( $singular ) ),
        'view_item'             => sprintf( __( 'Katso %s', $domain ), lcfirst( $singular ) ),
        'view_items'            => sprintf( __( 'Katso %s', $domain ), lcfirst( $plural ) ),
        'search_items'          => sprintf( __( 'Etsi %s', $domain ), lcfirst( $plural ) ),
        'not_found'             => __( 'Ei löytynyt', $domain ),
        'not_found_in_trash'    => __( 'Ei löytynyt roskakorista', $domain ),
        'featured_image'        => __( 'Julkaisun kuva', $domain ),
        'set_featured_image'    => sprintf( __( 'Aseta %s kuva', $domain ), lcfirst( $singular ) ),
        'remove_featured_image' => sprintf( __( 'Poista %s kuva', $domain ), lcfirst( $singular ) ),
        'use_featured_image'    => sprintf( __( 'Käytä %s kuvana', $domain ), lcfirst( $singular ) ),
        'insert_into_item'      => sprintf( __( 'Lisää %s', $domain ), lcfirst( $singular ) ),
        'uploaded_to_this_item' => sprintf( __( 'Ladattu tähän %s', $domain ), lcfirst( $singular ) ),
        'items_list'            => sprintf( __( '%s listaus', $domain ), $plural ),
        'items_list_navigation' => sprintf( __( '%s navigointi', $domain ), $plural ),
        'filter_items_list'     => sprintf( __( 'Suodata %s', $domain ), lcfirst( $plural ) ),
    );

    $args = array(
        'label'               => __( 'Työkalut', $domain ),
        'description'         => __( 'Työkalut kuvaus', $domain ),
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
