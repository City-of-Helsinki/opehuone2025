<?php
namespace Opehuone\AjaxHelpers;

/**
 * AJAX related stuff
 */

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
function ajax_add_post_to_favorites() {
	$post_id = $_POST['id'];
	$user_id = $_POST['user_id'];

	// is_user_logged_in() not working inside ajax, so need to check if logged in via user_id that was passed...
	if ( empty( $user_id ) ) {
		if ( isset( $_COOKIE['opehuone_favs'] ) ) {
			$posts_array = Utils()->get_favs_from_cache();

			if ( ! in_array( $post_id, $posts_array ) ) {
				array_push( $posts_array, $post_id );
			}

			setcookie( 'opehuone_favs', json_encode( $posts_array ), time() + ( 86400 * 365 ), '/' );

		} else {
			$posts_array = [ $post_id ];
			setcookie( 'opehuone_favs', json_encode( $posts_array ), time() + ( 86400 * 365 ), '/' );
		}
	} else {
		//save to user meta
		$posts = Utils()->get_favs_from_user_meta();

		if ( count( $posts ) === 0 ) {
			$array = [ $post_id ];
			update_user_meta( $user_id, 'opehuone_favs', $array );
		} else {
			$posts = get_user_meta( $user_id, 'opehuone_favs', true );
			if ( ! in_array( $post_id, $posts ) ) {
				array_push( $posts, $post_id );
			}
			update_user_meta( $user_id, 'opehuone_favs', $posts );
		}
	}

	\Opehuone\Helpers\the_svg( 'icons/pinned' );

	die();
}

add_action( 'wp_ajax_add_post_to_favorites', __NAMESPACE__ . '\\ajax_add_post_to_favorites' );
add_action( 'wp_ajax_nopriv_add_post_to_favorites', __NAMESPACE__ . '\\ajax_add_post_to_favorites' );

/**
 * Add post to favs
 */
function ajax_remove_post_from_favorites() {
	$post_id = $_POST['id'];
	$user_id = $_POST['user_id'];

	// is_user_logged_in() not working inside ajax, so need to check if logged in via user_id that was passed...
	if ( empty( $user_id ) ) {
		if ( isset( $_COOKIE['opehuone_favs'] ) ) {
			$new_array   = [];
			$posts_array = Utils()->get_favs_from_cache();

			foreach ( $posts_array as $post ) {
				if ( $post_id === $post ) {
					continue;
				}
				array_push( $new_array, $post );
			}

			setcookie( 'opehuone_favs', json_encode( $new_array ), time() + ( 86400 * 365 ), '/' );

		}
	} else {
		//remove from user meta
		$new_array = [];

		$posts_array = Utils()->get_favs_from_user_meta();

		foreach ( $posts_array as $post ) {
			if ( $post_id === $post ) {
				continue;
			}
			array_push( $new_array, $post );
		}

		update_user_meta( $user_id, 'opehuone_favs', $new_array );
	}

	Utils()->the_svg( 'favorite_border-24px' );

	die();
}

add_action( 'wp_ajax_remove_post_from_favorites', __NAMESPACE__ . '\\ajax_remove_post_from_favorites' );
add_action( 'wp_ajax_nopriv_remove_post_from_favorites', __NAMESPACE__ . '\\ajax_remove_post_from_favorites' );

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
	$form_data                            = $_POST['formData'];
	$user_id                              = $_POST['userId'];
	$theme                                = null;
	$highlight_color                      = null;
	$header_color                         = null;
	$what_to_show_categories_category     = [];
	$what_to_show_categories_article_lang = [];
	$what_to_show_categories_cornerlabels = [];

	/**
	 * Example of passed form data, need to modify that
	 *
	 * array(4) { [0]=> array(2) { ["name"]=> string(5) "theme" ["value"]=> string(5) "light" } [1]=> array(2) { ["name"]=> string(15) "highlight-color" ["value"]=> string(5) "metro" } [2]=> array(2) { ["name"]=> string(12) "header-color" ["value"]=> string(5) "light" } [3]=> array(2) { ["name"]=> string(23) "what-to-show-categories" ["value"]=> string(7) "varhais" } }
	 *
	 */

	foreach ( $form_data as $item ) {
		if ( $item['name'] === 'theme' ) {
			$theme = $item['value'];
		}

		if ( $item['name'] === 'highlight-color' ) {
			$highlight_color = $item['value'];
		}

		if ( $item['name'] === 'header-color' ) {
			$header_color = $item['value'];
		}

		if ( $item['name'] === 'what-to-show-categories--category' ) {
			$what_to_show_categories_category[] = $item['value'];
		}

		if ( $item['name'] === 'what-to-show-categories--cornerlabels' ) {
			$what_to_show_categories_cornerlabels[] = $item['value'];
		}

		if ( $item['name'] === 'what-to-show-categories--article_lang' ) {
			$what_to_show_categories_article_lang[] = $item['value'];
		}
	}

	$what_to_show_categories[] = [
		'category' => $what_to_show_categories_category,
	];

	$what_to_show_categories[] = [
		'cornerlabels' => $what_to_show_categories_cornerlabels,
	];

	$what_to_show_categories[] = [
		'article_lang' => $what_to_show_categories_article_lang,
	];

	$new_data = [
		'theme'                   => $theme,
		'highlight_color'         => $highlight_color,
		'header_color'            => $header_color,
		'what_to_show_categories' => [
			'category'     => $what_to_show_categories_category,
			'cornerlabels' => $what_to_show_categories_cornerlabels,
			'article_lang' => $what_to_show_categories_article_lang,
		],
	];

	\User_settings::update_user_settings( $new_data, $user_id );

	die();
}

add_action( 'wp_ajax_update_user_settings', __NAMESPACE__ . '\\ajax_update_user_settings' );
add_action( 'wp_ajax_nopriv_update_user_settings', __NAMESPACE__ . '\\ajax_update_user_settings' );

function ajax_add_new_own_link() {
	$url_name = esc_html( $_POST['urlName'] );
	$url      = esc_url( $_POST['url'] );
	$user_id  = esc_attr( $_POST['userId'] );

	$user_links = \User_settings::get_user_own_links( $user_id );

	$user_links['added_custom_links'][] = [
		'url_name' => $url_name,
		'url'      => $url,
	];

	update_user_meta( $user_id, 'user_opehuone_own_links', $user_links );

	// Send a success response with the added link
	wp_send_json_success( [ 'message' => 'Linkki lisätty', 'urlName' => $url_name, 'url' => $url ] );
}

add_action( 'wp_ajax_add_new_own_link', __NAMESPACE__ . '\\ajax_add_new_own_link' );
add_action( 'wp_ajax_nopriv_add_new_own_link', __NAMESPACE__ . '\\ajax_add_new_own_link' );

function ajax_remove_default_link() {
	$url     = esc_url( $_POST['url'] );
	$user_id = esc_attr( $_POST['user_id'] );

	$user_links = \User_settings::get_user_own_links( $user_id );

	$user_links['removed_default_urls'][] = $url;

	update_user_meta( $user_id, 'user_opehuone_own_links', $user_links );

	die();
}

add_action( 'wp_ajax_remove_default_link', __NAMESPACE__ . '\\ajax_remove_default_link' );
add_action( 'wp_ajax_nopriv_remove_default_link', __NAMESPACE__ . '\\ajax_remove_default_link' );

function ajax_remove_custom_link() {
	$url      = esc_url( $_POST['url'] );
	$url_name = esc_attr( $_POST['url_name'] );
	$user_id  = esc_attr( $_POST['user_id'] );

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

	die();
}

add_action( 'wp_ajax_remove_custom_link', __NAMESPACE__ . '\\ajax_remove_custom_link' );
add_action( 'wp_ajax_nopriv_remove_custom_link', __NAMESPACE__ . '\\ajax_remove_custom_link' );

function ajax_reset_own_links() {
	$user_id = esc_attr( $_POST['user_id'] );

	$default_user_own_links = [
		'removed_default_urls' => [],
		'added_custom_links'   => [],
	];

	update_user_meta( $user_id, 'user_opehuone_own_links', $default_user_own_links );

	die();
}

add_action( 'wp_ajax_reset_own_links', __NAMESPACE__ . '\\ajax_reset_own_links' );
add_action( 'wp_ajax_nopriv_reset_own_links', __NAMESPACE__ . '\\ajax_reset_own_links' );

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
		die( esc_html__( 'Käyttäjää ei pystytty tunnistamaan.', TEXT_DOMAIN ) );
	}

	$service_details = $_POST['service_details'];
	$user_id         = isset( $_POST['user_id'] ) ? wp_unslash( $_POST['user_id'] ) : null;

	$service_name = isset( $service_details['serviceName'] ) ? sanitize_text_field( $service_details['serviceName'] ) : null;
	$service_url  = isset( $service_details['serviceUrl'] ) ? esc_url_raw( $service_details['serviceUrl'] ) : null;
	$identifier   = md5( $service_name . $service_url );

	global $wpdb;
	$table  = $wpdb->prefix . 'user_own_services';
	$data   = array(
		'identifier'   => $identifier,
		'user_id'      => $user_id,
		'service_name' => $service_name,
		'service_url'  => $service_url,
		'visible'      => 1,
	);
	$format = array( '%s', '%d', '%s', '%s', '%d' );
	$wpdb->insert( $table, $data, $format );

	die();
}

add_action( 'wp_ajax_add_new_own_service', __NAMESPACE__ . '\\ajax_add_new_own_service' );

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
		die( esc_html__( 'Käyttäjää ei pystytty tunnistamaan.', TEXT_DOMAIN ) );
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

	die();
}

add_action( 'wp_ajax_pin_own_service', __NAMESPACE__ . '\\ajax_pin_own_service' );
