<?php

class Time_until {

    /**
     * Get string like "56 päivää ja 10 tuntia"
     *
     * @param string $season ACF field suffix, e.g., 'autumn', 'summer'
     * @return string|null
     */
    public static function get_days_until_string( $season ): string | null {
        $array = self::get_data_array( $season );

        if ( empty( $array ) ) {
            return null;
        }

        $format = '%d %s ja %d %s';

        return sprintf(
            $format,
            $array['days_until'],
            $array['days_text'],
            $array['hours_until'],
            $array['hours_text']
        );
    }

    private static function get_data_array( $season ): array {
        $now  = new DateTime( date_i18n( 'Y-m-d H:i' ) );
        $when = self::get_date_by_season( $season );

        $dateEnd = new DateTime( $when );

        $interval = $now->diff( $dateEnd );

        $days_until   = (int) str_replace( '+', '', $interval->format( '%R%a' ) );

        if ($days_until < 0) {
            return [];
        }

        $hours_until  = (int) str_replace( '+', '', $interval->format( '%H' ) );

        $days_text  = $days_until === 1 ? 'päivä' : 'päivää';
        $hours_text = $hours_until === 1 ? 'tunti' : 'tuntia';

        return [
            'days_until'  => $days_until,
            'days_text'   => $days_text,
            'hours_until' => $hours_until,
            'hours_text'  => $hours_text,
        ];
    }

    private static function get_date_by_season( $season ): string {
        $field_name = 'holiday_starts_' . $season;
        $date       = get_field( $field_name, 'option' ); // e.g. '2025-10-24'
        return $date . ' 00:00'; // set midnight to have a proper DateTime
    }

    public static function date_in_past( $date ): bool {
        $date_now = new DateTime();
        $date_obj = new DateTime( $date );
        return $date_now >= $date_obj;
    }
}