<?php

/**
 * Used to get weather values from openweather API
 *
 * Class HelsinkiWeather
 */
class HelsinkiWeather {

	private $feed_url = 'https://api.openweathermap.org/data/2.5/weather?id=658225&units=metric&appid=d8d1cbf5d638c700896cce8af6a5a40d';

	/**
	 * Returns weather icon and current temperature of location
	 *
	 * @return array
	 */
	public function get_weather_details() {
		$obj = json_decode( $this->get_output_json() );

		return [
			'weather_code' => $obj->weather[0]->icon,
			'temperature'  => round( (float) $obj->main->temp, 1 ),
		];
	}

	/**
	 * Get the output json
	 *
	 * store value to db, value expires after 15 minutes
	 *
	 */
	private function get_output_json() {

		// Do we have this information in our transients already?
		$transient = get_transient( 'opehuone_weather_json' );

		// Yep!  Just return it and we're done.
		if ( ! empty( $transient ) ) {

			// The function will return here every time after the first time it is run, until the transient expires.
			return $transient;

			// Nope!  We gotta make a call.
		} else {

			// Call the API.

			//Lets debug SSL problem
			$arr_context_options=array(
				"ssl"=>array(
					"verify_peer"=>false,
					"verify_peer_name"=>false,
				),
			);

			$out = file_get_contents( $this->feed_url, false, stream_context_create($arr_context_options) );

			// Save the API response so we don't have to call again until next 15 minutes
			set_transient( 'opehuone_weather_json', $out, MINUTE_IN_SECONDS * 15 );

			return $out;

		}

	}
}