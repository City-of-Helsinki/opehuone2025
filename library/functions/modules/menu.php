<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function opehuone_menu(string $location)
{
	$config = opehuone_menu_config($location);
	return $config ? wp_nav_menu(apply_filters("opehuone_{$location}_args", $config)) : '';
}

function opehuone_header_primary_menu_items_style_is( string $style ): bool {
	$mods = get_theme_mod( 'opehuone_header_primary_menu' );

	return isset( $mods['menu-items'] ) && $mods['menu-items'] === $style;
}

function opehuone_menu_config(string $location)
{
	if ( opehuone_header_primary_menu_items_style_is( 'menu-depth-2-5' ) ) {
		$desktop_depth = 2;
		$mobile_depth = 5;
	} else {
		$desktop_depth = 3;
		$mobile_depth = 3;
	}
	$subwalker = new Opehuone_Submenu_Walker();

	switch ($location) {
		case 'topbar_menu':
			return array(
				'theme_location'    => $location,
				'container'         => 'false',
				'container_id'      => '',
				'depth'             => 1,
				'menu_id'           => 'topbar-menu',
				'menu_class'        => 'menu menu--topbar',
				'echo'              => false,
				'fallback_cb'       => false,
				'item_spacing'      => 'discard',
				'walker'            => new Artcloud_Menu_Walker(),
			);
			break;

		case 'mobile_topbar_menu':
			return array(
				'theme_location'    => 'topbar_menu',
				'container'         => 'false',
				'container_id'      => '',
				'depth'             => 1,
				'menu_id'           => 'mobile-topbar-menu',
				'menu_class'        => 'mobile-menu menu menu--topbar',
				'echo'              => false,
				'fallback_cb'       => false,
				'item_spacing'      => 'discard',
				'walker'            => new Artcloud_Menu_Walker(),
			);
			break;

		case 'main_menu':
			return array(
				'theme_location'    => $location,
				'container'         => 'false',
				'depth'             => $desktop_depth,
				'menu_id'           => 'main-menu',
				'menu_class'        => 'menu menu--main',
				'echo'              => false,
				'fallback_cb'       => false,
				'link_before'		=> '',
				'link_after'		=> '',
				'before'			=> '<div class="link-wrap">',
				'after'				=> '</div>',
				'item_spacing'      => 'discard',
				'filter' 			=> true,
				'walker'            => new Opehuone_Menu_Walker(),
			);
			break;

		case 'sub_menu':
			return array(
				'theme_location'  => 'main_menu', // Use same menu
				'container'       => false,
				'depth'           => 2,
				'menu_id'         => 'main-menu-lvl-2',
				'echo'            => false,
				//'menu_class'      => 'menu menu--subi',
				'menu_class'     => 'sub-menu ' . $subwalker->parent_theme_color_class,
				'walker'          => $subwalker
			);
			break;
			

		case 'mobile_main_menu':
			return array(
				'theme_location'    => 'main_menu',
				'container'         => 'false',
				'depth'             => $mobile_depth,
				'menu_id'           => 'mobile-main-menu',
				'menu_class'        => 'mobile-menu menu menu--main',
				'echo'              => false,
				'fallback_cb'       => false,
				'link_before'		=> '',
				'link_after'		=> '',
				'before'			=> '<div class="link-wrap">',
				'after'				=> '</div>',
				'item_spacing'      => 'discard',
				'walker'            => new Artcloud_Menu_Walker(),
			);
			break;

		case 'footer_menu':
			return array(
				'theme_location'    => $location,
				'container'         => false,
				'container_class'   => '',
				'container_id'      => '',
				'depth'             => 1,
				'menu_id'           => 'footer-menu',
				'menu_class'        => 'menu menu--footer',
				'echo'              => false,
				'fallback_cb'       => false,
				'item_spacing'      => 'discard',
				'walker'            => new Artcloud_Menu_Walker(),
			);
			break;

        case 'footer_top_menu':
            return array(
                'theme_location'    => $location,
                'container'         => false,
                'container_class'   => '',
                'container_id'      => '',
                'depth'             => 1,
                'menu_id'           => 'footer-top-menu',
                'menu_class'        => 'menu--footer-top-menu',
                'echo'              => false,
                'fallback_cb'       => false,
                'item_spacing'      => 'discard',
                'walker'            => new Artcloud_Menu_Walker(),
            );
            break;

		default:
			return array();
			break;
	}
}

function add_custom_menu_active_class($classes, $item) {
    $queried_object = get_queried_object();
    
    // 1. For Taxonomy Archives
    if (is_archive() && $item->type === 'taxonomy') {
        $term_id = $item->object_id;
        
        // Fixed condition - added missing parenthesis
        if (
            (is_category() && is_category($term_id)) ||
            (is_tag() && is_tag($term_id)) ||
            (is_tax() && isset($queried_object->term_id) && $queried_object->term_id == $term_id)
        ) {
            $classes[] = 'menu__item--active';
        }
    }
    
    // 2. For Custom Post Type Archives
    if (is_post_type_archive() && $item->type === 'post_type_archive') {
        $current_post_type = get_query_var('post_type');
        if ($item->object === $current_post_type) {
            $classes[] = 'menu__item--active';
        }
    }
    
    // 3. For Author Archives
    if (is_author() && $item->type === 'custom') {
        $current_author_id = get_queried_object_id();
        $author_posts_url = get_author_posts_url($current_author_id);
        
        if (untrailingslashit($item->url) === untrailingslashit($author_posts_url)) {
            $classes[] = 'menu__item--active';
        }
    }
    
    return $classes;
}
add_filter('nav_menu_css_class', 'add_custom_menu_active_class', 10, 2);