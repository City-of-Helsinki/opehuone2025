<?php

namespace Opehuone\RestFilters;

/**
 * Modify output of wp core block: category terms
 * a-tags to be replaced with span
 *
 * @param $content
 * @param $block
 *
 * @return array|mixed|string|string[]|null
 */
function remove_links_from_post_terms( $content, $block ) {
	// Check if this is the 'categories' block type.
	if ( isset( $block['attrs']['term'] ) && $block['attrs']['term'] === 'category' ) {
		// Use regex to replace <a> tags with <span> tags and remove href and rel attributes.
		$content = preg_replace( '/<a[^>]*>([^<]*)<\/a>/', '<span class="wp-block-post-terms__term">$1</span>', $content );
	}

	return $content;
}

add_filter( 'render_block_core/post-terms', __NAMESPACE__ . '\\remove_links_from_post_terms', 10, 2 );
