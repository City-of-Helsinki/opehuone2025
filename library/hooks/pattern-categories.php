<?php

namespace Opehuone\PatternCategories;

/**
 * Add pattern category
 */
function block_pattern_custom_categories() {
	\register_block_pattern_category(
		'opehuone-patterns',
		[ 'label' => esc_html__( 'Opehuone lohkomallit', 'helsinki-universal' ) ]
	);
}

add_action( 'init', __NAMESPACE__ . '\\block_pattern_custom_categories' );
