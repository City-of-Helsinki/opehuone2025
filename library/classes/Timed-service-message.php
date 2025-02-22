<?php

/**
 * Helper class to check the current time versus timed service message settings
 *
 * Code will compare timestamp in unix format
 * => Will convert value from settings to unix format
 *
 * Class Timed_service_message
 */

class Timed_service_message {

	private $time_now_unix;
	private $service_start_unix;
	private $service_end_unix;

	/**
	 * Will set unix stamps
	 *
	 * Timed_service_message constructor.
	 */
	public function __construct() {
		$this->set_time_now();
		$this->service_start_unix = $this->get_c_date();
		$this->service_end_unix   = $this->get_c_date( 'end' );
	}

	private function set_time_now() {
		$this->time_now_unix = time();
	}

	/**
	 * Returns a timestamp from the settings
	 *
	 * @param string $when
	 *
	 * @return int
	 */
	private function get_c_date( $when = 'start' ) {
	    $options_field_date = 'timed_service_fault_time_' . $when . '_date';
        $options_field_time = 'timed_service_fault_time_' . $when . '_time';
		$date       = ! empty( get_field($options_field_date, 'option' ) ) ? get_field( $options_field_date, 'option' ) : '1970-01-01';
		$time       = ! empty( get_field($options_field_time, 'option' ) ) ? get_field( $options_field_time, 'option' ) : '00:00';
		$date       = $date . 'T' . $time . '+03:00';

		$dt = new DateTime( $date );

		return $dt->getTimestamp();
	}

	/**
	 * to check if service alert is ON (time is between current time)
	 *
	 * @return bool
	 */
	public function is_service_active() {
		if ( $this->time_now_unix < $this->service_end_unix && $this->time_now_unix > $this->service_start_unix ) {
			return true;
		} else {
			return false;
		}
	}
}
