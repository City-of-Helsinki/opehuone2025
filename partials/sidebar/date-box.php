<?php

use Opehuone\Utils;

$day = new \DateTime();
$day->setTimezone( new \DateTimeZone( 'Europe/Helsinki' ) );
$week_number = (int) $day->format( 'W' );
?>
<div class="sidebar-box sidebar-box--coat-of-arms-light">
	<div class="date-box-row">
		<div class="date-box-row-texts">
			<span class="date-box-week"><?php echo esc_html( $week_number ); ?>. viikko vecka</span>
			<span class="date-box-month"><?php echo Utils\get_month_info() ?></span>
			<span class="date-box-info">Nimipäivät, liputuspäivät</span>
		</div>
		<div class="date-box-row-weather">
			<?php echo Utils\get_current_weather_minified(); ?>
		</div>
	</div>
</div>
