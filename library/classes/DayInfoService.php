<?php

class DayInfoService {
    private string $name_day_API;
    private string $flag_day_API;
    private int $cache_ttl = 3600; // 1 hour

    public function __construct() {
        $this->name_day_API = 'https://nimipaivarajapinta.fi/api/namedays/today';
        $this->flag_day_API = get_stylesheet_directory() . '/resources/json/flag_days.json'; // Real API can be used in the future
    }

    private function fetch_name_day( ): mixed {
        $token = defined('NAME_DAYS_API') ? NAME_DAYS_API : null;

        if ( ! $token ) {
            return null;
        }

        $response = wp_remote_get( $this->name_day_API, [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Accept'        => 'application/json',
            ],
            'timeout' => 5,
            'sslverify' => false,
        ]);

        error_log( print_r( 'Response to API:', true ) );
        error_log( print_r( $response, true ) );

        if ( is_wp_error( $response ) ) {
            return null;
        }

        $data = json_decode( wp_remote_retrieve_body( $response ), true );

        if ( ! $data ) {
            return null;
        }

        return $data;
    }

    private function fetch_flag_day(): ?array {
        if ( ! file_exists( $this->flag_day_API ) ) {
            return null;
        }

        $data = json_decode( file_get_contents( $this->flag_day_API ), true );

        if ( ! $data || ! isset( $data['flag_days'] ) ) {
            return null;
        }

        $current_year = date( 'Y' );

        if ( ! isset( $data['flag_days'][$current_year] ) ) {
            return null;
        }

        $current_date = date( 'Y-m-d' );

        foreach( $data['flag_days'][$current_year] as $flag_day ) {
            if ( $flag_day['date'] === $current_date ) {
                return $flag_day;
            }
        }

        return null;
    }

    /**
     * @param string $cache_key
     * @param callable $fetch_callback
     * @return array|null
     *
     * Get cached value, use callback function to fetch new data if there is no cached data
     */
    private function get_cached_values( string $cache_key, callable $fetch_callback ): ?array {
        $cached = get_transient( $cache_key );

        if ( false !== $cached ) {
            error_log( print_r( 'Cached found for ' . $cache_key, true ) );
            return $cached;
        }
        
        error_log( print_r( 'Fetching new data for ' . $cache_key, true ) );

        $fresh_data = $fetch_callback();

        if ( $fresh_data ) {
            set_transient( $cache_key, $fresh_data, $this->cache_ttl );
            return $fresh_data;
        }

        return null;
    }


    public function get_today_info(): array {
        $today = date( 'Y-m-d' );

        $name_day_cache_key = "name_day_{$today}";
        $flag_day_cache_key = "flag_day_{$today}";

        $name_day = $this->get_cached_values( $name_day_cache_key, fn() => $this->fetch_name_day() );
        $flag_day = $this->get_cached_values( $flag_day_cache_key, fn() => $this->fetch_flag_day() );

        $data = [
            'date'     => $today,
            'name_day' => $name_day,
            'flag_day' => $flag_day
        ];

        error_log( print_r( $data, true ) );

        return $data;
    }

    /**
     * Function that gets names by type
     * For example we can get names by language (suomi, ruotsi) or by animal (kissa, hevonen)
     * @param string $type
     * @return array
     */
    public function get_names_by_type( array $data, string $type ): array {
        return ! empty( $data['name_day']['name_days_by_type'][$type] )
            ? array_column( $data['name_day']['name_days_by_type'][$type], 'name' )
            : [];
    }

}