<?php

use Opehuone\Utils;

$day = new \DateTime();
$day->setTimezone( new \DateTimeZone( 'Europe/Helsinki' ) );
$week_number = (int) $day->format( 'W' );


$day_service = new DayInfoService();
$day_info_service_data = $day_service->get_today_info();

$finnish_names_list = $day_service->get_names_by_type( $day_info_service_data, 'suomi' );
$swedish_names_list = $day_service->get_names_by_type( $day_info_service_data, 'ruotsi' );


?>
<div class="sidebar-box sidebar-box--suomenlinna-light">
	<div class="date-box-row">
		<div class="date-box-row-texts">
			<span class="date-box-week"><?php echo esc_html( $week_number ); ?>. viikko vecka</span>
			<span class="date-box-month"><?php echo Utils\get_month_info() ?></span>
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
                    <span class="date-box-info__name-day-sv"><?php echo $day_info_service_data['flag_day']['sv']; ?></span>
                </div>
            </div>
            <?php endif; ?>

		</div>
		<div class="date-box-row-weather">
			<?php echo Utils\get_current_weather_minified(); ?>
		</div>
	</div>
</div>
