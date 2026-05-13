<?php

use Opehuone\Utils;
use function \Opehuone\TemplateFunctions\display_time_until_holidays;

$day = new \DateTime();
$day->setTimezone( new \DateTimeZone( 'Europe/Helsinki' ) );
$week_number = (int) $day->format( 'W' );
$weekday_number = (int) $day->format('N');
$days = array(
    'fi' => array(
        1 => 'maanantai',
        2 => 'tiistai',
        3 => 'keskiviikko',
        4 => 'torstai',
        5 => 'perjantai',
        6 => 'lauantai',
        7 => 'sunnuntai',
    ),
    'sv' => array(
        1 => 'måndag',
        2 => 'tisdag',
        3 => 'onsdag',
        4 => 'torsdag',
        5 => 'fredag',
        6 => 'lördag',
        7 => 'söndag',
    )
);

$weekday = $days['fi'][$weekday_number] . ' ' . $days['sv'][$weekday_number];

$day_service = new DayInfoService();
$day_info_service_data = $day_service->get_today_info();

$finnish_names_list = $day_service->get_names_by_type( $day_info_service_data, 'suomi' );
$swedish_names_list = $day_service->get_names_by_type( $day_info_service_data, 'ruotsi' );


?>
<div class="sidebar-box date-box sidebar-box--suomenlinna-light">
    <div class="date-box-col date-box-texts">
        <span class="date-box-month">
            <span class="date-box-month-fi"><?php echo Utils\get_month_info()['month_finnish']; ?></span>
            <span class="date-box-month-sv date-box-sv"><?php echo Utils\get_month_info()['month_swedish']; ?></span>
        </span>
        <span class="date-box-day-number"><?php echo Utils\get_month_info()['day']; ?></span>

        <div class="date-box-info">
            <?php if ( ! empty( $finnish_names_list ) ): ?>
                <span class="date-box-info__name-day-fi"><?php echo esc_html( implode( ', ', $finnish_names_list ) ); ?></span>
            <?php endif; ?>

            <?php if ( ! empty( $swedish_names_list ) ): ?>
                <span class="date-box-info__name-day-sv"><?php echo esc_html( implode( ', ', $swedish_names_list ) ); ?></span>
            <?php endif; ?>
        </div>
        <?php if ( ! empty( $day_info_service_data['flag_day'] ) ): ?>
            <div class="date-box-info date-box-info__flag-days">
                <div>
                    <?php \Opehuone\Helpers\the_svg( 'icons/flag'); ?>
                </div>
                <div>
                    <span class="date-box-info__name-day-fi"><?php echo $day_info_service_data['flag_day']['fi']; ?></span>
                    <span class="date-box-info__name-day-sv date-box-sv"><?php echo $day_info_service_data['flag_day']['sv']; ?></span>
                </div>
            </div>
        <?php endif; ?>
	</div>
    <div class="date-box-col date-box-weather">
        <?php echo Utils\get_current_weather_minified(); ?>
    </div>
    <div class="date-box-col date-box-texts">
        <span class="date-box-day">
            <span class="date-box-day-fi"><?php echo esc_html( $days['fi'][$weekday_number] ); ?></span>
            <span class="date-box-day-sv date-box-sv"><?php echo esc_html( $days['sv'][$weekday_number] ); ?></span>
        </span>
        <span class="date-box-week"><?php echo esc_html( $week_number ); ?>. viikko <span class="date-box-sv">vecka</span></span>
    </div>
    <div class="date-box-col holiday-countdown">
        <?php get_template_part( 'partials/time-until' ); ?>
    </div>
</div>
