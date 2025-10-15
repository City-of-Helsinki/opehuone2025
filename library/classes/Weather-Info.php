<?php

/**
 * Used to get weather values from openweather API
 *
 * Class HelsinkiWeather
 */
class HelsinkiWeather {

	private $feed_url = 'https://api.openweathermap.org/data/2.5/weather?id=658225&units=metric&appid=';
    private $appid;

    /**
     * Constructor
     */
    public function __construct() {
        $this->appid = defined( 'OWM_APPID' ) ? OWM_APPID : null;
    }

	/**
	 * Returns weather icon and current temperature of location
	 *
	 * @return array
	 */
	public function get_weather_details() {
		$obj = json_decode( $this->get_output_json() );

        if ( ! $obj ) {
            return null;
        }

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
        $transient = get_transient( 'opehuone_weather_json' );

        if ( ! empty( $transient ) ) {
            return $transient;
        }

        if ( empty( $this->appid ) ) {
            error_log( 'HelsinkiWeather: Missing OpenWeather API key.' );
            return false;
        }

        $url = $this->feed_url . $this->appid;

        $arr_context_options = [
            "ssl" => [
                "verify_peer"      => false,
                "verify_peer_name" => false,
            ],
        ];

        $out = @file_get_contents( $url, false, stream_context_create( $arr_context_options ) );

        if ( $out ) {
            set_transient( 'opehuone_weather_json', $out, MINUTE_IN_SECONDS * 15 );
        }

        return $out;
    }
}