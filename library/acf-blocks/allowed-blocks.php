<?php
/**
 * Gutenberg blocks related stuff. Default blocks comes from the parent theme and/or the HDS plugin.
 *
 */

use \Opehuone\Helpers;

// Register ACF blocks
add_action( 'acf/init', function() {
    if ( ! function_exists( 'acf_register_block_type' ) ) {
        return;
    }

    acf_register_block_type( array(
        'name'              => 'opehuone-embed',
        'title'             => 'Upotus',
        'description'       => 'Upotuskoodien käyttö sallituista lähteistä (esim. Thinglink).',
        'render_callback'   => 'opehuone_render_embed_block',
        'category'          => 'widgets',
        'icon'              => 'embed-generic',
        'keywords'          => array( 'upotus', 'embed', 'iframe' ),
        'acf_block_version' => 3,
    ) );
} );

// Remove unnecessary core & helsinki -blocks from the editor
add_filter( 'allowed_block_types_all', function( $allowed_blocks, $context ) {

	$removed_blocks = array(
        'core/video',
		'core/nextpage',
		'core/social-links',
		'core/social-link',
        'hds-wp/rss-feed',
        'hds-wp/recent-posts',
	);

	if ( true === $allowed_blocks ) {
		$allowed_blocks = array_keys( WP_Block_Type_Registry::get_instance()->get_all_registered() );
	}

	if ( ! is_array( $allowed_blocks ) ) {
		return $allowed_blocks;
	}

	return array_values( array_diff( $allowed_blocks, $removed_blocks ) );
}, 998, 2 );

// Allowed blocks
add_filter( 'allowed_block_types_all', function( $allowed_blocks, $context ) {
    if ( is_array( $allowed_blocks ) ) {
        $allowed_blocks[] = 'acf/opehuone-embed';
        $allowed_blocks[] = 'core/embed';
    }
    return $allowed_blocks;
}, 999, 2 );

//  Require block files
Helpers\require_files( dirname( __FILE__ ) . '/embed-block' );
