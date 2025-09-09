<?php

class User_services {
    private $user;

    public function __construct() {
        $this->user = wp_get_current_user();
    }

    public function get_user_services() {
        $user_services = get_user_meta( $this->user->ID, 'user_services', true ) ? get_user_meta( $this->user->ID, 'user_services', true ) : false;

        if ( false === $user_services ) {
            // Need to get default services set
            $new_user_settings = [];
            $response          = $this->get_services_api_response();

            if ( $response ) {
                foreach ( $response as $row ) {
                    if ( $row->is_default ) {
                        $new_user_settings[] = $row->id;
                    }
                }
            }

            // Save to meta only when logged in
            if ( is_user_logged_in() ) {
                update_user_meta( $this->user->ID, 'user_services', $new_user_settings );
            }

            return $new_user_settings;
        } else {
            return $user_services;
        }
    }

    public function get_services_api_response() {
        $oppiaste_checker  = new Oppiaste_checker();

        $api_response      = wp_remote_get( get_rest_url() . 'wp/v2/services?cornerlabels=' . $oppiaste_checker::get_oppiaste_options_term_value() );

        $api_response_body = wp_remote_retrieve_body( $api_response );

        error_log( 'get_services_api_response' );
        error_log( json_encode( $api_response_body ) );

        var_dump( $api_response_body );

        return json_decode( $api_response_body );
//        if ( ! is_wp_error( $api_response_body ) ) {
//            return json_decode( $api_response_body );
//        } else {
//            return false;
//        }
    }
}
