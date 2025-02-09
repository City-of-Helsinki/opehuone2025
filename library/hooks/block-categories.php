<?php

namespace Opehuone\BlockCategories;

/**
 * Use filter to create new block categories
 *
 * @param array $categories Array of the categories
 * @param object $post Post object
 *
 * @return array
 */
function add_block_categories( $categories, $post ) {
	return array_merge(
		$categories,
		[
			[
				'slug'  => 'opehuone-blocks',
				'title' => __( 'Opehuone lohkot', 'helsinki-universal' ),
			],
		]
	);
}

add_filter( 'block_categories_all', __NAMESPACE__ . '\\add_block_categories', 10, 2 );
