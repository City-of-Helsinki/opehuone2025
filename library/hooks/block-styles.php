<?php

namespace Opehuone\BlockStyles;

/**
 * Register block styles.
 */

if ( ! function_exists( 'opehuone_block_styles' ) ) :

	/**
	 * Register custom block styles
	 *
	 * @return void
	 */
	function opehuone_block_styles() {

		/**
		 * The wp_enqueue_block_style() function allows us to enqueue a stylesheet
		 * for a specific block. These will only get loaded when the block is rendered
		 * (both in the editor and on the front end), improving performance
		 * and reducing the amount of data requested by visitors.
		 *
		 * See https://make.wordpress.org/core/2021/12/15/using-multiple-stylesheets-per-block/ for more info.
		 */

//		\register_block_style(
//			'core/paragraph',
//			array(
//				'name'         => 'ingress',
//				'label'        => __( 'Ingressi', 'helsinki-universal' ),
//				'inline_style' => '
//				.is-style-ingress {
//					font-size: var(--wp--preset--font-size--large);
//				}',
//			)
//		);
	}
endif;

add_action( 'init', __NAMESPACE__ . '\\opehuone_block_styles' );
