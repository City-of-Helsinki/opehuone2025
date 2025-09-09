<?php

namespace Opehuone\Utils;

function get_user_favs() {
	$user = wp_get_current_user();

	$posts = get_user_meta( $user->ID, 'opehuone_favs', true ) ? get_user_meta( $user->ID, 'opehuone_favs', true ) : [];

	if ( ! $posts ) {
		return [];
	}

	return $posts;
}

function get_current_weather_minified() {
	$day = new \DateTime();
	$day->setTimezone( new \DateTimeZone( 'Europe/Helsinki' ) );
	$week_number = ' (' . $day->format( 'W' ) . ') ';

	$weather         = new \HelsinkiWeather();
	$weather_details = $weather->get_weather_details();

	$weather_details['temperature'] = round( (float) $weather_details['temperature'] );

	$string = '<img class="front-side__weather__weather-symbol" alt="Säätiedot" src="https://openweathermap.org/img/wn/' . $weather_details['weather_code'] . '@2x.png"><span class="front-side__weather__location">' . $weather_details['temperature'] . '&#176; Helsinki</span>';

	return $string;
}

function get_user_data_meta() {
	$user = wp_get_current_user();
	$meta = get_user_meta( $user->ID, 'user_data', true ) ? get_user_meta( $user->ID, 'user_data', true ) : 'not found';

	return $meta;
}

function get_month_info() {
	// Set the date to the current time
	$timestamp = new \DateTime();

	// Get the day as an integer
	$day = $timestamp->format( 'j' );

	// Format month name in Finnish
	$formatter_fi  = new \IntlDateFormatter( 'fi_FI', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE );
	$month_finnish = $formatter_fi->format( $timestamp );
	$month_finnish = explode( ' ', $month_finnish )[1]; // Extract only the month name
	$month_finnish = str_replace( 'ta', '', $month_finnish ); // Remove helmikuuTA (ta-part)

	// Format month name in Swedish
	$formatter_sv  = new \IntlDateFormatter( 'sv_SE', \IntlDateFormatter::LONG, \IntlDateFormatter::NONE );
	$month_swedish = $formatter_sv->format( $timestamp );
	$month_swedish = explode( ' ', $month_swedish )[1]; // Extract only the month name

	// Output formatted date
	return "{$day}. {$month_finnish} {$month_swedish}";
}

function user_data_meta_exists() {
	if ( ! is_user_logged_in() ) {
		return false;
	}

	$user = wp_get_current_user();
	$meta = get_user_meta( $user->ID, 'user_data', true );

	if ( ! empty( $meta ) ) {
		return true;
	}

	return false;
}

function get_cornerlabels_term_ids( $post_id = null ) {
	$post_id = $post_id ?: get_the_ID();

	$terms = get_the_terms( $post_id, 'cornerlabels' );

	if ( empty( $terms ) || is_wp_error( $terms ) ) {
		return '';
	}

	$term_ids = wp_list_pluck( $terms, 'term_id' );

	return implode( ',', $term_ids );
}

/**
 * Function to get user own services from DB
 *
 * @param int $visible Is service visible or not 0/1 (tinyint in db)
 *
 * @return array|object|null Results from SQL-query
 */
function get_user_own_services( $visible = 1 ) {
    global $wpdb;
    $user_id = get_current_user_id();

    $table_name = "{$wpdb->prefix}user_own_services";
    $sql        = $wpdb->prepare( "SELECT * FROM `$table_name` WHERE visible = %d AND user_id = %d", $visible, $user_id );

    $results = $wpdb->get_results( $sql, OBJECT );

    return $results;
}

/**
 * Helper to echo own services list
 *
 * @param bool $is_active To echo active or non active own services
 */
function the_own_services_row( $is_active ) {
    $visible             = true === $is_active ? 1 : 0;
    $active_own_services = get_user_own_services( $visible );

    foreach ( $active_own_services as $own_service ) {

        $args = [
            'title'                  => $own_service->service_name,
            'url'                    => $own_service->service_url,
            'own_service_id'         => $own_service->id,
            'own_service_identifier' => $own_service->identifier,
            'description'            => $own_service->service_description,
            'icon_url'               => '',
            'icon_alt'               => '',
            'active_service'         => $is_active,
            'is_own_service'         => true,
        ];

        get_template_part( 'partials/blocks/b-service-item', '', $args );
    }
}

/**
 * To echo general services and filter by user settings
 *
 * @param bool $is_active Active or non active services
 * @param Object $user_services User_services class
 */
function the_services_row( bool $is_active, \User_services $user_services ) {
    $all_services   = $user_services->get_services_api_response();
    $users_services = $user_services->get_user_services();

    error_log('the_services_row');
    error_log( json_encode( $all_services ) );
    var_dump( $all_services );

    // Check that JSON response has been valid
    if ( $all_services !== false ) {
        if ( is_array( $all_services ) ) {
            foreach ( $all_services as $row ) {

                $service_title       = $row->title;
                $service_post_id     = $row->post_id;
                $service_url         = $row->url;
                $service_icon_url    = $row->icon_url;
                $service_icon_alt    = '';
                $service_description = $row->description;

                $args = [
                    'post_id'        => $service_post_id,
                    'title'          => $service_title,
                    'url'            => $service_url,
                    'description'    => $service_description,
                    'icon_url'       => $service_icon_url,
                    'icon_alt'       => $service_icon_alt,
                    'active_service' => $is_active,
                ];

                if ( true === $is_active ) {
                    if ( in_array( $row->id, $users_services ) ) {
                        get_template_part( 'partials/blocks/b-service-item', '', $args );
                    }
                } else {
                    if ( ! in_array( $row->id, $users_services ) ) {
                        get_template_part( 'partials/blocks/b-service-item', '', $args );
                    }
                }
            }
        }
    }
}

function get_open_new_tab_text() {
//    return esc_html( ' ' . pll__( '( Linkki avautuu uuteen ikkunaan )' ) );
    return 'Linkki avautuu uuteen ikkunaan';
}