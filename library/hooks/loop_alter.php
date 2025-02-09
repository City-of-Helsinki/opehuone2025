<?php

namespace Opehuone\LoopAlter;

/**
 * Alter WP-loops
 *
 * @hook pre_get_posts
 */
add_action( 'pre_get_posts', function ( $query ) {
	// Add/Modify query handling
//	if ( is_tag() && $query->is_main_query() ) {
//		$query->set( 'post_type', array(
//			'guide',
//			'cases',
//			'blogs'
//		) );
//	}
//
//	if ( is_post_type_archive( 'cases' ) && ! is_admin() && $query->is_main_query() ) {
//		$query->set( 'order', 'ASC' );
//		$query->set( 'orderby', 'menu_order' );
//		$query->set( 'posts_per_page', 50 );
//	}
//
//	if ( ( is_post_type_archive( 'blogs' ) || is_post_type_archive( 'guide' ) ) && ! is_admin() && $query->is_main_query() ) {
//		$query->set( 'posts_per_page', 9 );
//	}
//
//	if ( is_admin() && is_post_type_archive( 'blogs' ) ) {
//		$query->set( 'orderby', 'date' );
//		$query->set( 'order', 'DESC' );
//	}
} );
