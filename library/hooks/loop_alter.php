<?php

namespace Opehuone\LoopAlter;

/**
 * Alter WP-loops
 *
 * @hook pre_get_posts
 */
add_action( 'pre_get_posts', function ( $query ) {
	// Don't show past training posts in training archive main loop
	if ( is_post_type_archive( 'training' ) && ! is_admin() && $query->is_main_query() ) {
		$query->set( 'meta_query', [
			[
				'key'     => 'training_end_datetime',
				'value'   => current_time( 'Y-m-d\TH:i:s' ),
				'compare' => '>=',
				'type'    => 'DATETIME',
			],
		] );
	}
} );
