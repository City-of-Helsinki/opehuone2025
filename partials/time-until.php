<?php
// Check if we wanna show holidays...hide from ammattikoulu & varhaiskasvatus
$current_user       = wp_get_current_user();
$school_abbrevation = get_user_meta( $current_user->ID, 'user_data', true );

if ( OppiSchoolPicker\is_ammattikoulu( $school_abbrevation ) || OppiSchoolPicker\is_varhaiskasvatus( $school_abbrevation ) || OppiSchoolPicker\is_virasto( $school_abbrevation ) ) {
	return;
}

$is_sv = OppiSchoolPicker\is_ruotsinkielinen( $school_abbrevation );

function cmp_dates( $a, $b ) {
	if ( $a['start_date'] == $b['start_date'] ) {
		return 0;
	}

	return ( $a['start_date'] < $b['start_date'] ) ? - 1 : 1;
}

$array = [
	[
		'holiday'    => 'autumn',
		'starts_txt' => esc_html__( 'Aikaa syyslomaan:', 'helsinki-universal' ),
		'start_date' => Time_until::get_date_by_season( 'autumn', true ),
	],
	[
		'holiday'    => 'christmas',
		'starts_txt' => esc_html__( 'Aikaa joululomaan:', 'helsinki-universal' ),
		'start_date' => Time_until::get_date_by_season( 'christmas', true ),
	],
	[
		'holiday'    => 'winter',
		'starts_txt' => esc_html__( 'Aikaa talvilomaan:', 'helsinki-universal' ),
		'start_date' => Time_until::get_date_by_season( 'winter', true ),
	],
	[
		'holiday'    => 'summer',
		'starts_txt' => esc_html__( 'Aikaa kesälomaan:', 'helsinki-universal' ),
		'start_date' => Time_until::get_date_by_season( 'summer', true ),
	]
];

// Sorts the array according to start_date
// We will only want to show the first item of the array (next holiday)
usort( $array, 'cmp_dates' );

$array_sv = [
	[
		'holiday'    => 'autumn',
		'starts_txt' => 'Tid till höstlovet',
		'start_date' => Time_until::get_date_by_season( 'autumn', true ),
	],
	[
		'holiday'    => 'christmas',
		'starts_txt' => 'Tid till jullovet',
		'start_date' => Time_until::get_date_by_season( 'christmas', true ),
	],
	[
		'holiday'    => 'winter',
		'starts_txt' => 'Tid till sportlovet',
		'start_date' => Time_until::get_date_by_season( 'winter', true ),
	],
	[
		'holiday'    => 'summer',
		'starts_txt' => 'Tid till sommarlovet',
		'start_date' => Time_until::get_date_by_season( 'summer', true ),
	]
];

// Sorts the array according to start_date
// We will only want to show the first item of the array (next holiday)
usort( $array_sv, 'cmp_dates' );

$i = 1;

// Show suomenkieliset
?>

<?php if ( ! $is_sv ) : ?>
	<?php foreach ( $array as $row ): ?>
		<?php
		if ( Time_until::date_in_past( $row['start_date'] ) ) {
			continue;
		}
		?>
		<?php if ( get_field( 'show_until_' . $row['holiday'], 'option' ) ): ?>
			<?php if ( $i === 1 ): ?>
				<?php if ( ! empty( get_field( 'holiday_starts_' . $row['holiday'], 'option' ) ) ): ?>
					<?php echo $row['starts_txt'] . ' ' . Time_until::get_days_until_string( $row['holiday'] ) ?>
				<?php endif; ?>
				<?php $i ++; ?>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
<?php

// Show ruotsinkieliset
$i = 1;
?>
<?php if ( $is_sv ) : ?>
	<?php foreach ( $array_sv as $row ): ?>
		<?php
		if ( Time_until::date_in_past( $row['start_date'] ) ) {
			continue;
		}
		?>
		<?php if ( get_field( 'show_until_' . $row['holiday'] . '_sv', 'option' ) ): ?>
			<?php if ( $i === 1 ): ?>
				<?php if ( ! empty( get_field( 'holiday_starts_' . $row['holiday'] . '_sv', 'option' ) ) ): ?>
					<?php echo $row['starts_txt'] . ' ' . Time_until::get_days_until_string( $row['holiday'], true ) ?>
				<?php endif; ?>
				<?php $i ++; ?>
			<?php endif; ?>
		<?php endif; ?>
	<?php endforeach; ?>
<?php endif; ?>
