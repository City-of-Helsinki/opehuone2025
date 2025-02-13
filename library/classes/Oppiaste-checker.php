<?php

/**
 * Class Oppiaste_checker
 */
class Oppiaste_checker {

    /**
     * Current user
     *
     * @var WP_User|null WP_User object or null if not logged in
     */
    public static $current_user;

    /**
     * Oppiaste_checker constructor.
     */
    public function __construct() {
        self::$current_user = wp_get_current_user();
    }


    public static function get_oppiaste_options_term_value() {
        $key   = self::get_oppiaste_options_key();
        $value = get_field( $key, 'option' );

        return $value;
    }

    public static function get_own_links_title_by_oppiaste() {
        $school_abbrevation = self::get_user_school_data();

        if ( OppiSchoolPicker\is_peruskoulu( $school_abbrevation ) ) {
            return __( 'Peruskoulujen linkit', TEXT_DOMAIN );
        }

        if ( OppiSchoolPicker\is_lukio( $school_abbrevation ) ) {
            return __( 'Lukioiden linkit', TEXT_DOMAIN );
        }

        if ( OppiSchoolPicker\is_ammattikoulu( $school_abbrevation ) ) {
            return __( 'Stadin AO:n linkit', TEXT_DOMAIN );
        }

        if ( OppiSchoolPicker\is_virasto( $school_abbrevation ) ) {
            return __( 'Kasvatuksen ja koulutuksen linkit', TEXT_DOMAIN );
        }

        if ( OppiSchoolPicker\is_varhaiskasvatus( $school_abbrevation ) ) {
            return __( 'Päiväkotien linkit', TEXT_DOMAIN );
        }

        if ( OppiSchoolPicker\is_vapaa_sivistystyo( $school_abbrevation ) ) {
            return __( 'Vapaan sivistystyön linkit', TEXT_DOMAIN );
        }

        return __( 'Omat linkit', TEXT_DOMAIN );
    }

    public static function get_oppiaste_options_key() {
        $school_abbrevation = self::get_user_school_data();

        if ( OppiSchoolPicker\is_peruskoulu( $school_abbrevation ) ) {
            return 'oppiaste_term_peruskoulu';
        }

        if ( OppiSchoolPicker\is_lukio( $school_abbrevation ) ) {
            return 'oppiaste_term_lukio';
        }

        if ( OppiSchoolPicker\is_ammattikoulu( $school_abbrevation ) ) {
            return 'oppiaste_term_ammattikoulu';
        }

        if ( OppiSchoolPicker\is_virasto( $school_abbrevation ) ) {
            return 'oppiaste_term_virasto';
        }

        if ( OppiSchoolPicker\is_varhaiskasvatus( $school_abbrevation ) ) {
            return 'oppiaste_term_varhaiskasvatus';
        }

        if ( OppiSchoolPicker\is_vapaa_sivistystyo( $school_abbrevation ) ) {
            return 'oppiaste_term_vapaa_sivistystyo';
        }

        return 'oppiaste_term_default';
    }

    private static function get_user_school_data() {
        return Opehuone\Utils\get_user_data_meta();
    }
}
