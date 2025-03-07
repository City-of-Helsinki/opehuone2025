<?php

class TrainingHelpers {
	/**
	 * Function to return nicely formatted date string
	 *
	 * if start and end are on same day, then return start_date date like 'd.m.Y h:i - h:i of the end date', example 02.02.2025 12:00 - 14:00
	 * if starte and end date are not on same day, then return d.m. - d.m.Y, example 02.02. - 05.02.2025
	 *
	 * @param $start_datetime datetimezone string example 2025-03-23T17:15:00
	 * @param $end_datetime datetimezone string example 2025-03-23T17:15:00
	 *
	 * @return string
	 */
	public static function get_training_date( $start_datetime, $end_datetime ) {
		// Convert input datetime strings to DateTime objects
		$start = new DateTime( $start_datetime );
		$end   = new DateTime( $end_datetime );

		// Check if the start and end dates are on the same day
		if ( $start->format( 'Y-m-d' ) === $end->format( 'Y-m-d' ) ) {
			// Format: d.m.Y h:i - h:i
			return $start->format( 'd.m.Y H:i' ) . ' - ' . $end->format( 'H:i' );
		} else {
			// Format: d.m. - d.m.Y
			return $start->format( 'd.m.' ) . ' - ' . $end->format( 'd.m.Y' );
		}
	}

	public static function get_training_type( $type ) {
		if ( empty( $type ) ) {
			return 'Lähikoulutus';
		}

		if ( $type === 'onsite' ) {
			return 'Lähikoulutus';
		}

		if ( $type === 'online' ) {
			return 'Verkkokoulutus';
		}

		if ( $type === 'hybrid' ) {
			return 'Hybridikoulutus';
		}

		return 'Lähikoulutus';
	}

	public static function get_training_theme( $theme ) {
		if ( empty( $theme ) ) {
			return 'suomenlinna';
		}

		return $theme;
	}

	public static function get_training_type_svg( $type ) {
		if ( empty( $type ) ) {
			return 'company';
		}

		if ( $type === 'onsite' ) {
			return 'company';
		}

		if ( $type === 'online' ) {
			return 'globe';
		}

		if ( $type === 'hybrid' ) {
			return 'hybrid';
		}

		return 'company';
	}
}
