<?php

namespace Opehuone\AjaxHelpers\PostsArchive;

/**
 * AJAX related stuff to posts archive
 */


/**
 * Post parameters sent as arrays convert to comma separated string, that's why this helper function is needed
 *
 * @param $string
 *
 * @return array|string[]
 */
function convert_string_to_array( $string ) {
	// If any case string happens to be array, just return that
	if ( is_array( $string ) ) {
		return $string;
	}

	$array = [];
	if ( $string === '' ) {
		return [];
	}

	if ( ! is_array( $string ) ) {
		$array = explode( ',', $string );

		return $array;
	}

	return $array;
}

function get_user_favs( $user_id ) {
	$current_favs = get_user_meta( $user_id, 'opehuone_favs', true );
	if ( ! $current_favs ) {
		$current_favs = [];
	}

	return $current_favs;
}

/**
 * This is a helper function to build tax_query args for query
 * Parameters are by default empty array
 *
 * tax_query relation should be 'AND' and there are at least two arrays with at least on item
 *
 * If individual array is non-empty then add following array to tax_query:
 * [
 * 'taxonomy' => 'taxonomyname this case cornerlabels',
 * 'field'    => 'term_id',
 * 'terms'    => $cornerlabels,
 * ]
 *
 * @param $cornerlabels
 * @param $categories
 * @param $post_tags
 *
 */
function set_tax_query( $cornerlabels = [], $categories = [], $post_tags = [] ) {
	$tax_query = [];

	if ( ! empty( $cornerlabels ) ) {
		$tax_query[] = [
			'taxonomy' => 'cornerlabels',
			'field'    => 'term_id',
			'terms'    => $cornerlabels,
		];
	}

	if ( ! empty( $categories ) ) {
		$tax_query[] = [
			'taxonomy' => 'category',
			'field'    => 'term_id',
			'terms'    => $categories,
		];
	}

	if ( ! empty( $post_tags ) ) {
		$tax_query[] = [
			'taxonomy' => 'post_tag',
			'field'    => 'term_id',
			'terms'    => $post_tags,
		];
	}

	// If there are at least two non-empty taxonomies, set the relation to 'AND'.
	if ( count( $tax_query ) > 1 ) {
		array_unshift( $tax_query, [ 'relation' => 'AND' ] );
	}

	return $tax_query;
}


function ajax_update_posts_archive_results() {
	// Get filter values from POST request
	$user_id      = intval( $_POST['userId'] );
	$current_favs = get_user_favs( $user_id );

	$cornerlabels = isset( $_POST['cornerLabels'] ) ? $_POST['cornerLabels'] : '';
	$cornerlabels = convert_string_to_array( $cornerlabels );

	$categories = isset( $_POST['categories'] ) ? $_POST['categories'] : '';
	$categories = convert_string_to_array( $categories );

	$post_tags = isset( $_POST['postTags'] ) ? $_POST['postTags'] : '';
	$post_tags = convert_string_to_array( $post_tags );

	$tax_query = set_tax_query( $cornerlabels, $categories, $post_tags );

	$query_args = [
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 15,
	];

	// possibly add tax query
	if ( count( $tax_query ) > 0 ) {
		$query_args['tax_query'] = $tax_query;
	}

	ob_start();

	$query = new \WP_Query( $query_args );

	// Output regular posts after sticky ones
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			$block_args = [
				'post_id'    => get_the_ID(),
				'title'      => get_the_title(),
				'url'        => get_the_permalink(),
				'media_id'   => get_post_thumbnail_id(),
				'excerpt'    => get_the_excerpt(),
				'is_sticky'  => is_sticky(),
				'categories' => get_the_category(),
				'date'       => get_the_date(),
				'is_pinned'  => in_array( get_the_ID(), $current_favs ),
			];

			get_template_part( 'partials/template-blocks/b-post', null, $block_args );
		}
	} else {
		echo '<p>Ei uutisia.</p>';
	}

	$output      = ob_get_clean();
	$total_posts = $query->found_posts; // Get the total number of posts found

	wp_reset_postdata();

	wp_send_json_success( [
		'message'    => 'Uutiset päivitetty',
		'output'     => $output,
		'totalPosts' => $total_posts, // Include total number of posts
	] );
}

add_action( 'wp_ajax_update_posts_archive_results', __NAMESPACE__ . '\\ajax_update_posts_archive_results' );
add_action( 'wp_ajax_nopriv_update_posts_archive_results', __NAMESPACE__ . '\\ajax_update_posts_archive_results' );
