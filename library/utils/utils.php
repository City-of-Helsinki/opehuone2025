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
