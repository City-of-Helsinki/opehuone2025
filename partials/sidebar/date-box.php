<?php

use Opehuone\Utils;

$day = new \DateTime();
$day->setTimezone( new \DateTimeZone( 'Europe/Helsinki' ) );
$week_number = (int) $day->format( 'W' );


$day_info_service = new DayInfoService();

$finnish_names_list = $day_info_service->get_names_by_type('suomi');
$swedish_names_list = $day_info_service->get_names_by_type('ruotsi');


?>
<div class="sidebar-box sidebar-box--coat-of-arms-light">
	<div class="date-box-row">
		<div class="date-box-row-texts">
			<span class="date-box-week"><?php echo esc_html( $week_number ); ?>. viikko vecka</span>
			<span class="date-box-month"><?php echo Utils\get_month_info() ?></span>
            <div class="date-box-info">
                <?php if ( ! empty( $finnish_names_list ) ): ?>
                    <span class="date-box-info__name-day-fi">
            <?php echo esc_html( implode( ', ', $finnish_names_list ) ); ?>
        </span>
                <?php endif; ?>

                <?php if ( ! empty( $swedish_names_list ) ): ?>
                    <span class="date-box-info__name-day-sv">
            <?php echo esc_html( implode( ', ', $swedish_names_list ) ); ?>
        </span>
                <?php endif; ?>
            </div>

		</div>
		<div class="date-box-row-weather">
			<?php echo Utils\get_current_weather_minified(); ?>
		</div>
	</div>
</div>
