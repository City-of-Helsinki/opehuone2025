<?php

/**
 * Class Oppiaste_settings_updater
 */
class Oppiaste_settings_updater {
    /**
     * ID for term "Yhteiset"
     *
     * @var int
     */
    private static $yhteisetId = 679;

    /**
     * Function to be run via ajax that will update user taxonomy settings
     */
    public static function update_user_settings_by_oppiaste() {
        $users = get_users( [ 'orderby' => 'ID' ] );

        foreach ( $users as $user ) {
            $user_settings = get_user_meta( $user->ID, 'user_opehuone_settings', true ) ? get_user_meta( $user->ID, 'user_opehuone_settings', true ) : false;

            $term_key = Oppiaste_checker::get_oppiaste_options_key();

            // If oppiaste is not found ==> skip user
            if ( 'oppiaste_term_default' === $term_key ) {
                continue;
            }

            $oppiaste_category_id = Oppiaste_checker::get_oppiaste_options_term_value();

            // In case category does not have value ==> skip user
            if ( empty( $oppiaste_category_id ) ) {
                continue;
            }

            // Set yhteiset term id and just found oppiaste category id into new array
            $new_array                                                = self::get_oppiaste_array();
            $user_settings['what_to_show_categories']['cornerlabels'] = $new_array;

            // Set also both category and article_lang taxonomies to all
            $user_settings['what_to_show_categories']['category']     = Utils()->get_all_term_ids_of_taxonomy( 'category' );
            $user_settings['what_to_show_categories']['article_lang'] = Utils()->get_all_term_ids_of_taxonomy( 'article_lang' );

            // Then just save all to user meta
            update_user_meta( $user->ID, 'user_opehuone_settings', $user_settings );
        }
    }

    public static function get_oppiaste_array() {

        if ( ! is_user_logged_in() ) {
            return Utils()->get_all_term_ids_of_taxonomy( 'cornerlabels' );
        }

        $term_key = Oppiaste_checker::get_oppiaste_options_key();

        // If oppiaste is not found ==> set only yhteiset
        if ( 'oppiaste_term_default' === $term_key ) {
            return [ self::$yhteisetId ];
        }

        $oppiaste_category_id = Oppiaste_checker::get_oppiaste_options_term_value();

        // In case category does not have value, set only yhteiset
        if ( empty( $oppiaste_category_id ) ) {
            return [ self::$yhteisetId ];
        }

        return [ $oppiaste_category_id, self::$yhteisetId ];
    }
}
