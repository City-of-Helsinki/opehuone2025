<?php

/**
 * Helper class to check time until selected holiday
 *
 * Class Days_until
 */
class Time_until {

    /**
     * Main method to be called to get string example "x päivää y tuntia kesälomaan"
     *
     * @param $season
     *
     * @return string
     */
    public static function get_days_until_string( $season, $is_sv = false ) {
        $array = self::get_data_array( $season, $is_sv );

        $format = '%d %s ja %d %s.';

        if ( $is_sv ) {
            $format = '%d %s och %d %s.';
        }

        return sprintf( $format, $array['days_until'], $array['days_text'], $array['hours_until'], $array['hours_text'] );
    }

    private static function get_data_array( $season, $is_sv ) {
        $now  = date_i18n( 'Y-m-d H:i' );
        $when = self::get_date_by_season( $season, false, $is_sv );

        $dateStart = new DateTime( $now );
        $dateEnd   = new DateTime( $when );

        $interval = $dateStart->diff( $dateEnd );

        $days_until   = str_replace( '+', '', $interval->format( '%R%a' ) );
        $hours_until  = str_replace( '+', '', $interval->format( '%H' ) );
        $days_string  = $is_sv === true ? 'dagar' : __( 'päivää', TEXT_DOMAIN );
        $hours_string = $is_sv === true ? 'timmar' : __( 'tuntia', TEXT_DOMAIN );

        if ( $days_until === 1 ) {
            $days_string = $is_sv === true ? 'dag' : __( 'päivä', TEXT_DOMAIN );
        }

        if ( $hours_until === 1 ) {
            $hours_string = $is_sv === true ? 'timme' : __( 'tunti', TEXT_DOMAIN );
        }

        return [
            'days_until'  => $days_until,
            'days_text'   => $days_string,
            'hours_until' => $hours_until,
            'hours_text'  => $hours_string,
        ];
    }

    public static function get_date_by_season( $season, $only_date = false, $is_sv = false ) {

        $field_name = $is_sv === true ? 'holiday_starts_' . $season . '_sv' : 'holiday_starts_' . $season;

        $date = get_field( $field_name, 'option' );

        if ( $only_date ) {
            return $date;
        } else {
            return $date . ' 00:00';
        }
    }

    public static function date_in_past( $date ) {
        $date_now = new DateTime();
        $date     = new DateTime( $date );

        if ( $date_now >= $date ) {
            return true;
        } else {
            return false;
        }
    }
}