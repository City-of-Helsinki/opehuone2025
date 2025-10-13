<?php

class DayInfoService {
    private string $cache_key;
    private string $name_day_API;
    private string $flag_day_API;

    public function __construct() {
        $this->cache_key = 'flag_and_name_day';

        $this->name_day_API = 'https://nimipaivarajapinta.fi/api/namedays/today';
        $this->flag_day_API = '';
    }

    private function fetch_name_day(): mixed {
        $token = defined('NAME_DAYS_API') ? NAME_DAYS_API : null;

        if ( ! $token ) {
            return null;
        }

        $response = wp_remote_get( $this->name_day_API, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json'
            ],
            'timeout' => 5
        ]);

        if ( is_wp_error( $response ) ) {
            return null;
        }

        return json_decode( wp_remote_retrieve_body( $response ), true );
    }

    private function fetch_flag_day() {
        return '';
    }

    public function get_today_info(): array {
        // Check cache first
        $cached = get_transient( $this->cache_key );
        $today = date( 'Y-m-d' );

        // If we have the value cached and it has current days value, use it
        if ( false !== $cached && $cached['date'] === $today ) {
            return $cached;
        }

        // Fetch from both API's
        $name_day = $this->fetch_name_day();
        $flag_day = $this->fetch_flag_day();

        $data = [
            'date'     => $today,
            'name_day' => $name_day,
            'flag_day' => $flag_day
        ];

        // Cache the results
        set_transient( $this->cache_key, $data );

        return $data;
    }

    /**
     * Function that gets names by type
     * For example we can get names by language (suomi, ruotsi) or by animal (kissa, hevonen)
     * @param string $type
     * @return array
     */
    public function get_names_by_type(string $type): array {
        $info = $this->get_today_info();

        return ! empty( $info['name_day']['name_days_by_type'][$type] )
            ? array_column( $info['name_day']['name_days_by_type'][$type], 'name' )
            : [];
    }

}