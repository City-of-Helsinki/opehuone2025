<?php

namespace Opehuone\AjaxHelpers;

use function Opehuone\TemplateFunctions\display_sticky_and_regular_posts;
use function Opehuone\TemplateFunctions\get_post_archive_query;
use function Opehuone\TemplateFunctions\get_training_posts_query;
use function Opehuone\Utils\the_own_services_row;
use function Opehuone\Utils\the_services_row;
use function \Opehuone\Utils\get_user_favs;

/**
 * AJAX related stuff
 */


/**
 * Verify helper to verify that user is logged in and nonce is valid
 *
 * @param $nonce
 *
 * @return true
 */
function verify_logged_in_request( $nonce ) {
	// Check if user is logged in
	if ( ! is_user_logged_in() ) {
		wp_send_json_error( [ 'message' => 'Unauthorized request' ], 403 );
	}

	// Verify nonce
	if ( ! wp_verify_nonce( $nonce, 'opehuone_nonce' ) ) {
		wp_send_json_error( [ 'message' => 'Invalid nonce' ], 403 );
	}
}

/**
 * Get weather info
 */
function ajax_get_weather_content() {
	echo UTILS()->get_current_weather_minified();
	die();
}

add_action( 'wp_ajax_get_weather_content', __NAMESPACE__ . '\\ajax_get_weather_content' );
add_action( 'wp_ajax_nopriv_get_weather_content', __NAMESPACE__ . '\\ajax_get_weather_content' );

/**
 * Update dock items
 */
function ajax_update_dock_items() {
	Dock_updater::update_all_docks();
	die();
}

add_action( 'wp_ajax_update_dock_items', __NAMESPACE__ . '\\ajax_update_dock_items' );

/**
 * Update user oppiaste settings
 */
function ajax_update_user_oppiaste_settings() {
	\Oppiaste_settings_updater::update_user_settings_by_oppiaste();
	die();
}

add_action( 'wp_ajax_update_users_oppiaste_settings', __NAMESPACE__ . '\\ajax_update_user_oppiaste_settings' );

/**
 * Add post to favs
 */
function ajax_favs_add() {
	verify_logged_in_request( $_POST['nonce'] );

	$post_id = $_POST['postId'];
	$user_id = $_POST['userId'];

	$posts = \Opehuone\Utils\get_user_favs();

	if ( count( $posts ) === 0 ) {
		$array = [ $post_id ];
		update_user_meta( $user_id, 'opehuone_favs', $array );
	} else {
		if ( ! in_array( $post_id, $posts ) ) {
			array_push( $posts, $post_id );
		}
		update_user_meta( $user_id, 'opehuone_favs', $posts );
	}

	wp_send_json_success( [ 'message' => 'suosikki lisätty', 'postID' => $post_id ] );
}

add_action( 'wp_ajax_favs_add', __NAMESPACE__ . '\\ajax_favs_add' );

/**
 * Remove post to favs
 */
function ajax_favs_remove() {
	verify_logged_in_request( $_POST['nonce'] );

	$post_id = $_POST['postId'];
	$user_id = $_POST['userId'];

	$posts = \Opehuone\Utils\get_user_favs();

	//remove from user meta
	$new_array = [];

	foreach ( $posts as $post ) {
		if ( $post_id === $post ) {
			continue;
		}
		array_push( $new_array, $post );
	}

	update_user_meta( $user_id, 'opehuone_favs', $new_array );

	wp_send_json_success( [ 'message' => 'Suosikki poistettu', 'postID' => $post_id ] );
}

add_action( 'wp_ajax_favs_remove', __NAMESPACE__ . '\\ajax_favs_remove' );

/**
 * Update tutor page schools
 */
function ajax_update_tutor_page_schools() {
	$term_id = $_POST['term_id'];

	$terms = Utils()->get_terms_with_meta_value( 'school_area', $term_id, 'tutor-teacher-school' );

	if ( ! is_wp_error( $terms ) ) {
		?>
		<option selected="true"
				disabled="disabled"><?php _e( 'Valitse koulu', TEXT_DOMAIN ); ?></option>
		<?php
		foreach ( $terms as $term ) {
			?>
			<option value="<?php echo $term->term_id; ?>"><?php echo $term->name; ?></option>
			<?php
		}
	}

	die();
}

add_action( 'wp_ajax_update_tutor_page_schools', __NAMESPACE__ . '\\ajax_update_tutor_page_schools' );
add_action( 'wp_ajax_nopriv_update_tutor_page_schools', __NAMESPACE__ . '\\ajax_update_tutor_page_schools' );

/**
 * Update tutor page teachers
 */
function ajax_update_tutor_page_teachers() {
	$school_id            = ! empty( $_POST['school_id'] ) ? $_POST['school_id'] : null;
	$area_id              = ! empty( $_POST['area_id'] ) ? $_POST['area_id'] : null;
	$area_of_expertise_id = ! empty( $_POST['area_of_expertise_id'] ) ? $_POST['area_of_expertise_id'] : null;

	$tax_query_array = [ 'relation' => 'AND' ];

	if ( $area_id !== null ) {
		array_push( $tax_query_array, [
			'taxonomy' => 'tutor-teacher-area',
			'field'    => 'term_id',
			'terms'    => $area_id,
		] );
	}

	if ( $school_id !== null ) {
		array_push( $tax_query_array, [
			'taxonomy' => 'tutor-teacher-school',
			'field'    => 'term_id',
			'terms'    => $school_id,
		] );
	}

	if ( $area_of_expertise_id !== null ) {
		array_push( $tax_query_array, [
			'taxonomy' => 'area-of-expertise-category',
			'field'    => 'term_id',
			'terms'    => $area_of_expertise_id,
		] );
	}

	$args = [
		'post_type'      => 'tutor-teachers',
		'posts_per_page' => - 1,
		'orderby'        => 'title',
		'order'          => 'ASC',
		'tax_query'      => $tax_query_array,

	];

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			get_template_part( 'partials/components/teacher-lift' );
		}
	} else {
		?>
		<p>
			<?php _e( 'Näytää siltä, että opettajia ei löytynyt...', TEXT_DOMAIN ); ?>
		</p>
		<?php
	}

	wp_reset_postdata();

	die();
}

add_action( 'wp_ajax_update_tutor_page_teachers', __NAMESPACE__ . '\\ajax_update_tutor_page_teachers' );
add_action( 'wp_ajax_nopriv_update_tutor_page_teachers', __NAMESPACE__ . '\\ajax_update_tutor_page_teachers' );

function ajax_update_user_dock() {
	$list_comma_separated = $_POST['newDock'];
	$list                 = explode( ',', $list_comma_separated );
	$user_id              = $_POST['userId'];
	\User_settings::update_dock_sorting( $list, $user_id );
	die();
}

add_action( 'wp_ajax_update_user_dock', __NAMESPACE__ . '\\ajax_update_user_dock' );
add_action( 'wp_ajax_nopriv_update_user_dock', __NAMESPACE__ . '\\ajax_update_user_dock' );

function ajax_update_user_settings() {
	// Call the verification function and pass the nonce from $_POST
	verify_logged_in_request( $_POST['nonce'] );

	$user_id = $_POST['userId'];

	// WP sets array from javascript to comma separated string
	// So changing that to array
	$cornerlabels = explode( ',', $_POST['cornerLabels'] );

	$new_data = [
		'what_to_show_categories' => [
			'cornerlabels' => $cornerlabels,
		],
	];

	\User_settings::update_user_settings( $new_data, $user_id );

	// Send a success response with the added link
	wp_send_json_success( [ 'message' => 'Asetukset päivitetty' ] );
}

add_action( 'wp_ajax_update_user_settings', __NAMESPACE__ . '\\ajax_update_user_settings' );

function ajax_add_new_own_link() {
    // Check nonce
    $nonce = $_POST['nonce'] ?? '';
    if ( ! wp_verify_nonce( $nonce, 'opehuone_nonce' ) ) {
        wp_send_json_error( 'Virheellinen nonce' );
    }

    $user_id  = intval( $_POST['userId'] ?? 0 );
    $url      = esc_url_raw( $_POST['url'] ?? '' );
    $url_name = sanitize_text_field( $_POST['urlName'] ?? '' );

    if ( ! $user_id || ! $url || ! $url_name ) {
        wp_send_json_error( 'Puuttuvat tiedot' );
    }

    // Get existing user links
    $own_links = get_user_meta( $user_id, 'user_opehuone_own_links', true );

    if ( ! is_array( $own_links ) ) {
        $own_links = array(
            'removed_default_urls' => array(),
            'added_custom_links'   => array(),
		);
    }

    if ( ! isset( $own_links['added_custom_links'] ) || ! is_array( $own_links['added_custom_links'] ) ) {
        $own_links['added_custom_links'] = array();
    }

    // Add new link
    $own_links['added_custom_links'][] = array(
        'url_name' => $url_name,
        'url'      => $url,
	);

    update_user_meta( $user_id, 'user_opehuone_own_links', $own_links );

    $added_link = array(
        'title' => $url_name,
        'url'   => $url,
        'type'  => 'custom',
	);

    wp_send_json_success( $added_link );
}
add_action( 'wp_ajax_add_new_own_link', __NAMESPACE__ . '\\ajax_add_new_own_link' );

function ajax_remove_default_link() {
	verify_logged_in_request( $_POST['nonce'] );
	$url     = esc_url( $_POST['url'] );
	$user_id = esc_attr( $_POST['userId'] );

	$user_links = \User_settings::get_user_own_links( $user_id );

	$user_links['removed_default_urls'][] = $url;

	update_user_meta( $user_id, 'user_opehuone_own_links', $user_links );

	// Send a success response with the added link
	wp_send_json_success( [ 'message' => 'Linkki poistettu', 'url' => $url ] );

	die();
}

add_action( 'wp_ajax_remove_default_link', __NAMESPACE__ . '\\ajax_remove_default_link' );

function ajax_remove_custom_link() {
	verify_logged_in_request( $_POST['nonce'] );

	$url      = esc_url( $_POST['url'] );
	$url_name = esc_attr( $_POST['urlName'] );
	$user_id  = esc_attr( $_POST['userId'] );

	$user_links = \User_settings::get_user_own_links( $user_id );

	$new_custom_links_array = [];

	foreach ( $user_links['added_custom_links'] as $link ) {
		if ( (string) $link['url'] === (string) $url && (string) $link['url_name'] === (string) $url_name ) {
			continue;
		}

		$new_custom_links_array[] = [
			'url'      => $link['url'],
			'url_name' => $link['url_name'],
		];
	}

	$new_meta_array = [
		'added_custom_links'   => $new_custom_links_array,
		'removed_default_urls' => $user_links['removed_default_urls'],
	];

	update_user_meta( $user_id, 'user_opehuone_own_links', $new_meta_array );

	// Send a success response with the added link
	wp_send_json_success( [ 'message' => 'Linkki poistettu', 'urlName' => $url_name, 'url' => $url ] );
}

add_action( 'wp_ajax_remove_custom_link', __NAMESPACE__ . '\\ajax_remove_custom_link' );

function ajax_reset_own_links() {
	verify_logged_in_request( $_POST['nonce'] );

	$user_id = esc_attr( $_POST['user_id'] );

	$default_user_own_links = [
		'removed_default_urls' => [],
		'added_custom_links'   => [],
	];

	update_user_meta( $user_id, 'user_opehuone_own_links', $default_user_own_links );

	// Send a success response
	wp_send_json_success( [ 'message' => 'Linkit resetoitu.' ] );
}

add_action( 'wp_ajax_reset_own_links', __NAMESPACE__ . '\\ajax_reset_own_links' );

function ajax_copy_training_to_articles() {
	$post_id = esc_attr( $_POST['postID'] );

	echo $post_id;

	if ( empty( $post_id ) ) {
		die();
	}

	$new_post_array = [
		'post_title'    => 'Koulutus: ' . get_the_title( $post_id ),
		'post_status'   => 'publish',
		'post_type'     => 'post',
		'post_excerpt'  => get_the_excerpt( $post_id ),
		'post_content'  => get_the_content( null, false, $post_id ) . ' <a href="' . get_the_permalink( $post_id ) . '">Lue lisää koulutussivulta.</a>',
		'post_category' => [ 28 ], // Koulutus category
		'tax_input'     => [
			'article_lang' => wp_get_post_terms( $post_id, 'article_lang', [ 'fields' => 'ids' ] ),
			'cornerlabels' => wp_get_post_terms( $post_id, 'cornerlabels', [ 'fields' => 'ids' ] ),
		],
		'meta_input'    => [
			'_original_koulutus_post_id' => $post_id,
			'_thumbnail_id'              => ! empty( get_post_thumbnail_id( $post_id ) ) ? get_post_thumbnail_id( $post_id ) : null,
		],
	];

	wp_insert_post( $new_post_array );

	die();
}

add_action( 'wp_ajax_copy_training_to_articles', __NAMESPACE__ . '\\ajax_copy_training_to_articles' );
add_action( 'wp_ajax_nopriv_copy_training_to_articles', __NAMESPACE__ . '\\ajax_copy_training_to_articles' );

/**
 * Ajax function to add new service
 */
function ajax_add_new_own_service() {
    $nonce = $_POST['nonce'];

    if ( ! wp_verify_nonce( $nonce, 'opehuone_nonce' ) ) {
        die( esc_html__( 'Käyttäjää ei pystytty tunnistamaan.' ) );
    }

    $service_details = $_POST['service_details'];
    $user_id         = isset( $_POST['user_id'] ) ? wp_unslash( $_POST['user_id'] ) : null;

    $service_name        = isset( $service_details['serviceName'] ) ? sanitize_text_field( $service_details['serviceName'] ) : null;
    $service_url         = isset( $service_details['serviceUrl'] ) ? esc_url_raw( $service_details['serviceUrl'] ) : null;
    $identifier          = md5( $service_name . $service_url );

    global $wpdb;
    $table  = $wpdb->prefix . 'user_own_services';
    $data   = array(
        'identifier'          => $identifier,
        'user_id'             => $user_id,
        'service_name'        => $service_name,
        'service_url'         => $service_url,
    );
    $format = array( '%s', '%d', '%s', '%s' );
    $insert = $wpdb->insert( $table, $data, $format );

    if ( false !== $insert ) {
        $user_services = new \User_services();
        the_services_row( false, $user_services );
        the_own_services_row( false );
    }

    die();
}

add_action( 'wp_ajax_add_new_own_service', __NAMESPACE__ . '\\ajax_add_new_own_service' );
add_action( 'wp_ajax_nopriv_add_new_own_service', __NAMESPACE__ . '\\ajax_add_new_own_service' );

/**
 * Add service to favs
 */
function ajax_add_service_to_favorites() {
    $user_services  = new \User_services();
    $users_services = $user_services->get_user_services();
    $service_id     = isset( $_POST['service_id'] ) ? wp_unslash( $_POST['service_id'] ) : null;
    $user_id        = isset( $_POST['user_id'] ) ? wp_unslash( $_POST['user_id'] ) : null;

    if ( $service_id && $user_id ) {
        $users_services[] = $service_id;
        update_user_meta( $user_id, 'user_services', $users_services );

        the_own_services_row( true );
        the_services_row( true, $user_services );
    }

    die();
}

add_action( 'wp_ajax_add_service_to_favorites', __NAMESPACE__ . '\\ajax_add_service_to_favorites' );
add_action( 'wp_ajax_nopriv_add_service_to_favorites', __NAMESPACE__ . '\\ajax_add_service_to_favorites' );


/**
 * Remove service from favs
 */
function ajax_remove_service_from_favorites() {
    $user_services  = new \User_services();
    $users_services = $user_services->get_user_services();
    $service_id     = isset( $_POST['service_id'] ) ? wp_unslash( $_POST['service_id'] ) : null;
    $user_id        = isset( $_POST['user_id'] ) ? wp_unslash( $_POST['user_id'] ) : null;

    if ( $service_id && $user_id ) {
        $key = array_search( $service_id, $users_services );
        if ( false !== $key ) {
            unset( $users_services[ $key ] );
            update_user_meta( $user_id, 'user_services', $users_services );

            the_own_services_row( false );
            the_services_row( false, $user_services );
        }
    }

    die();
}

add_action( 'wp_ajax_remove_service_from_favorites', __NAMESPACE__ . '\\ajax_remove_service_from_favorites' );
add_action( 'wp_ajax_nopriv_remove_service_from_favorites', __NAMESPACE__ . '\\ajax_remove_service_from_favorites' );

/**
 * Ajax function to delete own service
 */
function ajax_delete_own_service() {
	$nonce = $_POST['nonce'];

	if ( ! wp_verify_nonce( $nonce, 'opehuone_nonce' ) ) {
		die( esc_html__( 'Käyttäjää ei pystytty tunnistamaan.', TEXT_DOMAIN ) );
	}

	$user_id            = isset( $_POST['userId'] ) ? wp_unslash( $_POST['userId'] ) : null;
	$service_id         = isset( $_POST['serviceId'] ) ? wp_unslash( $_POST['serviceId'] ) : null;
	$service_identifier = isset( $_POST['serviceIdentifier'] ) ? wp_unslash( $_POST['serviceIdentifier'] ) : null;

	global $wpdb;
	$table = $wpdb->prefix . 'user_own_services';

	$delete = $wpdb->delete(
		$table,
		[
			'id'         => $service_id,
			'user_id'    => $user_id,
			'identifier' => $service_identifier,
		],
		[ '%d', '%d', '%s' ]
	);

	if ( false !== $delete ) {
		esc_html_e( 'Oma palvelusi on nyt poistettu.', TEXT_DOMAIN );
	} else {
		esc_html_e( 'Oman palvelun poistossa tapahtui virhe.', TEXT_DOMAIN );
	}

	die();
}

add_action( 'wp_ajax_remove_own_service', __NAMESPACE__ . '\\ajax_delete_own_service' );


function ajax_pin_own_service() {
    $nonce = $_POST['nonce'];

    if ( ! wp_verify_nonce( $nonce, 'opehuone_nonce' ) ) {
        die( esc_html__( 'Käyttäjää ei pystytty tunnistamaan.' ) );
    }

    $user_id            = isset( $_POST['userId'] ) ? wp_unslash( $_POST['userId'] ) : null;
    $set_visible        = isset( $_POST['setVisible'] ) ? (int) wp_unslash( $_POST['setVisible'] ) : null;
    $service_id         = isset( $_POST['serviceId'] ) ? wp_unslash( $_POST['serviceId'] ) : null;
    $service_identifier = isset( $_POST['serviceIdentifier'] ) ? wp_unslash( $_POST['serviceIdentifier'] ) : null;

    global $wpdb;
    $table = $wpdb->prefix . 'user_own_services';

    $update = $wpdb->update(
        $table,
        [
            'visible' => $set_visible,
        ],
        [
            'id'         => $service_id,
            'user_id'    => $user_id,
            'identifier' => $service_identifier,
        ],
        [ '%d' ],
        [ '%d', '%d', '%s' ]
    );

    $user_services = new \User_services();

    if ( false !== $update ) {
        if ( 1 === $set_visible ) {
            the_own_services_row( true );
            the_services_row( true, $user_services );
        } else {
            the_services_row( false, $user_services );
            the_own_services_row( false );
        }
    }

    die();
}

add_action( 'wp_ajax_pin_own_service', __NAMESPACE__ . '\\ajax_pin_own_service' );

function ajax_update_front_page_posts() {
    $cornerlabel_ids = $_POST['cornerLabels'] ?? [];

    if ( ! is_array( $cornerlabel_ids ) ) {
        $cornerlabel_ids = explode( ',', $cornerlabel_ids );
    }

    $user_id = intval( $_POST['userId'] );

    $current_favs = get_user_meta( $user_id, 'opehuone_favs', true );
    if ( ! $current_favs ) {
        $current_favs = [];
    }

    $max_posts = 8;

    // Base query arguments
    $query_args = [
        'post_type' => 'post',
    ];

    // Tax filter
    if ( ! empty( $cornerlabel_ids ) ) {
        $query_args['tax_query'] = [
            [
                'taxonomy' => 'cornerlabels',
                'field'    => 'term_id',
                'terms'    => $cornerlabel_ids,
            ],
        ];
    }

    // --- Sticky posts ---
    $sticky_posts = get_option( 'sticky_posts' );
    $sticky_posts = ! empty( $sticky_posts ) ? array_map( 'intval', $sticky_posts ) : [];

    $sticky_query = null;
    $sticky_count = 0;

    if ( ! empty( $sticky_posts ) ) {
        $sticky_query_args = wp_parse_args( [
            'post__in'       => $sticky_posts,
            'posts_per_page' => $max_posts, // important: cap stickies to max_posts
            'orderby'        => 'post__in',
        ], $query_args );

        $sticky_query = new \WP_Query( $sticky_query_args );
        $sticky_count = count( $sticky_query->posts );
    }

    // --- Regular posts ---
    $remaining = max( 0, $max_posts - $sticky_count );

    $regular_query_args = wp_parse_args( [
        'post__not_in'   => $sticky_posts,
        'posts_per_page' => $remaining, // only fetch remaining
    ], $query_args );

    $regular_query = new \WP_Query( $regular_query_args );

    // --- Output ---
    ob_start();

    display_sticky_and_regular_posts( $sticky_count, $sticky_query, $current_favs, $remaining, $regular_query );

    if ( $sticky_count + count( $regular_query->posts ) === 0 ) {
        return;
    }

    $output = ob_get_clean();

    wp_reset_postdata();

    wp_send_json_success( [
        'message'        => 'Uutiset päivitetty',
        'output'         => $output,
    ] );
}


add_action( 'wp_ajax_update_front_page_posts', __NAMESPACE__ . '\\ajax_update_front_page_posts' );
add_action( 'wp_ajax_nopriv_update_front_page_posts', __NAMESPACE__ . '\\ajax_update_front_page_posts' );

function ajax_update_front_page_training() {
	// Get cornerLabels from POST request
	$cornerlabel_ids = isset( $_POST['cornerLabels'] ) ? $_POST['cornerLabels'] : '';

	if ( $cornerlabel_ids === '' ) {
		$cornerlabel_ids = [];
	}

	$user_id = intval( $_POST['userId'] );

	if ( ! is_array( $cornerlabel_ids ) ) {
		$cornerlabel_ids = explode( ',', $cornerlabel_ids );
	}

	$query_args = [
		'post_type'      => 'training',
		'posts_per_page' => 8,
		'meta_key'       => 'training_start_datetime', // Define the meta key for ordering
		'orderby'        => 'meta_value', // Order by meta value
		'order'          => 'ASC', // Order in ascending order
		'meta_query'     => [
			[
				'key'     => 'training_end_datetime', // Target the correct meta field
				'value'   => current_time( 'Y-m-d\TH:i:s' ), // Get the current date and time in WordPress timezone
				'compare' => '>=', // Only include posts where the date is in the future
				'type'    => 'DATETIME', // Ensure proper comparison as a date-time value
			],
		],
	];

	// If there are selected filters, add them to query
	if ( ! empty( $cornerlabel_ids ) ) {
		$query_args['tax_query'] = [
			[
				'taxonomy' => 'cornerlabels',
				'field'    => 'term_id',
				'terms'    => $cornerlabel_ids,
			],
		];
	}

	ob_start();

	$query = new \WP_Query( $query_args );

	// Output regular posts after sticky ones
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			$block_args = [
				'url'            => get_the_permalink(),
				'title'          => get_the_title(),
				'type'           => get_post_meta( get_the_ID(), 'training_type', true ),
				'theme'          => get_post_meta( get_the_ID(), 'training_theme_color', true ),
				'start_datetime' => get_post_meta( get_the_ID(), 'training_start_datetime', true ),
				'end_datetime'   => get_post_meta( get_the_ID(), 'training_end_datetime', true ),
				'excerpt'        => get_the_excerpt(),
				'categories'     => get_the_terms( get_the_ID(), 'training_theme' ),
			];

			get_template_part( 'partials/template-blocks/b-training-post', '', $block_args );
		}
	} else {
		echo '<p>Ei koulutuksia.</p>';
	}

	$output = ob_get_clean();
	wp_reset_postdata();

	wp_send_json_success( [
		'message' => 'Koulutukset päivitetty',
		'output'  => $output
	] );
}

add_action( 'wp_ajax_update_front_page_training', __NAMESPACE__ . '\\ajax_update_front_page_training' );
add_action( 'wp_ajax_nopriv_update_front_page_training', __NAMESPACE__ . '\\ajax_update_front_page_training' );

// Side-menu Ajax update for pages only
function ajax_update_front_page_pages() {
    $cornerlabel_ids = $_POST['cornerLabels'] ?? [];
    if (!is_array($cornerlabel_ids)) {
        $cornerlabel_ids = explode(',', $cornerlabel_ids);
    }

    // Get the default option (Kaikille yhteinen) and set it as one of the selected options
    $default_cornerlabel = get_field( 'oppiaste_term_default', 'option' );

    if ( $default_cornerlabel !== false ) {
        $cornerlabel_ids[] = (string) $default_cornerlabel;
    }

    $filter_not_found = empty($cornerlabel_ids) || (count($cornerlabel_ids) === 1 && $cornerlabel_ids[0] === '');

    $current_id = isset($_POST['currentPageId']) ? intval($_POST['currentPageId']) : 0;
    if (!$current_id || !get_post($current_id)) {
        wp_send_json_success(['output' => '<p class="error">Sivun ID puuttuu tai virheellinen.</p>']);
    }

    $ancestors = array_reverse(get_post_ancestors($current_id));
    if (count($ancestors) < 1) {
        wp_send_json_success(['output' => '<p class="error">Sivun hierarkia ei ole riittävä.</p>']);
    }

    $second_level_parent_id = count($ancestors) >= 2 ? $ancestors[1] : $current_id;
    $second_level_page = get_post($second_level_parent_id);
    if (!$second_level_page) {
        wp_send_json_success(['output' => '<p class="error">Valikon muodostaminen epäonnistui – sivua ei löytynyt.</p>']);
    }

    $walker = new \BEM_Page_Walker(); // Default walker

    // If we have filter values, we want to get only relevant posts
    if (! $filter_not_found ) {
        // Get all descendants of the second-level parent
        $all_pages = get_pages([
            'child_of' => $second_level_parent_id,
            'sort_column' => 'menu_order',
            'sort_order' => 'asc',
            'hierarchical' => true,
            'depth' => 3,
        ]);

        $filtered_ids = [];

        foreach ($all_pages as $page) {
            $page_terms = wp_get_post_terms($page->ID, 'cornerlabels', ['fields' => 'ids']);
            if (array_intersect($cornerlabel_ids, $page_terms)) {
                $filtered_ids[] = $page->ID;
            }
        }

        // Add all ancestors of filtered pages to preserve hierarchy
        foreach ($filtered_ids as $page_id) {
            $ancestors_of_page = get_post_ancestors($page_id);
            foreach ($ancestors_of_page as $ancestor_id) {
                if ($ancestor_id >= $second_level_parent_id && !in_array($ancestor_id, $filtered_ids)) {
                    $filtered_ids[] = $ancestor_id;
                }
            }
        }

        $filtered_ids = array_unique($filtered_ids);

        if (empty($filtered_ids)) {
            wp_send_json_success(['output' => '<p>Ei sivuja valituilla suodattimilla.</p>']);
        }

        // Use the Filtered walker
        $walker = new \Filtered_BEM_Page_Walker($filtered_ids, $cornerlabel_ids);
        $walker->current_page = $current_id;
    }

    $args = [
        'title_li'    => '',
        'sort_column' => 'menu_order',
        'order'       => 'asc',
        'child_of'    => $second_level_parent_id,
        'depth'       => 3,
        'walker'      => $walker,
    ];

    ob_start();

    echo '<div class="sidemenu-heading">' . esc_html(get_the_title($second_level_page)) . '</div>';
    echo '<nav aria-label="' . esc_attr__('Sivuvalikko', 'helsinki-universal') . '">';
    echo '<ul class="sidemenu-nav-lvl-1 sidemenu-nav-lvl" id="sidebar-nav">';
    wp_list_pages($args);
    echo '</ul>';
    echo '</nav>';

    $output = ob_get_clean();

    wp_send_json_success([
        'message' => 'Sivuvalikko päivitetty',
        'output' => $output,
    ]);
}

add_action( 'wp_ajax_update_front_page_pages', __NAMESPACE__ . '\\ajax_update_front_page_pages' );
add_action( 'wp_ajax_nopriv_update_front_page_pages', __NAMESPACE__ . '\\ajax_update_front_page_pages' );

function ajax_update_training_archive_results() {
    $query = get_training_posts_query( true );

	ob_start();

	// Output regular posts after sticky ones
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();

			$block_args = [
				'url'            => get_the_permalink(),
				'title'          => get_the_title(),
				'type'           => get_post_meta( get_the_ID(), 'training_type', true ),
				'theme'          => get_post_meta( get_the_ID(), 'training_theme_color', true ),
				'start_datetime' => get_post_meta( get_the_ID(), 'training_start_datetime', true ),
				'end_datetime'   => get_post_meta( get_the_ID(), 'training_end_datetime', true ),
				'excerpt'        => get_the_excerpt(),
				'categories'     => get_the_terms( get_the_ID(), 'training_theme' ),
			];

			get_template_part( 'partials/template-blocks/b-training-post', '', $block_args );
		}
	} else {
		echo '<p>Ei koulutuksia.</p>';
	}

	$output      = ob_get_clean();
	$total_posts = $query->found_posts; // Get the total number of posts found

	wp_reset_postdata();

	wp_send_json_success( [
		'message'     => 'Koulutukset päivitetty',
		'output'      => $output,
		'totalPosts' => $total_posts, // Include total number of posts
	] );
}

add_action( 'wp_ajax_update_training_archive_results', __NAMESPACE__ . '\\ajax_update_training_archive_results' );
add_action( 'wp_ajax_nopriv_update_training_archive_results', __NAMESPACE__ . '\\ajax_update_training_archive_results' );

// Post-archive filters

function ajax_update_post_archive_results() {
    $query = get_post_archive_query(true );

	ob_start();

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
				'is_pinned'  => in_array( get_the_ID(), get_user_favs() ),
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
		'message'     => 'Uutiset päivitetty',
		'output'      => $output,
		'totalPosts' => $total_posts, // Include total number of posts
	] );
}

add_action( 'wp_ajax_update_post_archive_results', __NAMESPACE__ . '\\ajax_update_post_archive_results' );
add_action( 'wp_ajax_nopriv_update_post_archive_results', __NAMESPACE__ . '\\ajax_update_post_archive_results' );

function ajax_load_concentration() {
    $post_id = isset( $_POST['postId'] ) ? wp_unslash( $_POST['postId'] ) : null;

    if ( ! $post_id ) {
        die();
    }

    $content   = apply_filters( 'the_content', get_the_content( null, false, $post_id ) );
    $bg_url    = get_the_post_thumbnail_url( $post_id, 'full' );
    $duration  = get_field( 'concentration_duration', $post_id );
    $track_url = get_field( 'concentration_music', $post_id );

    $array = [
        'content'   => $content,
        'bg_url'    => $bg_url,
        'duration'  => $duration,
        'track_url' => $track_url,
    ];

    die( json_encode( $array ) );
}

add_action( 'wp_ajax_load_concentration', __NAMESPACE__ . '\\ajax_load_concentration' );
add_action( 'wp_ajax_nopriv_load_concentration', __NAMESPACE__ . '\\ajax_load_concentration' );
