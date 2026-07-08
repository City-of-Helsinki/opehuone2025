<?php
if ( ! is_user_logged_in() ) {
	return;
}
use Opehuone\Helpers;

$current_user          = wp_get_current_user();
$cornerlabels          = Opehuone_user_settings_reader::get_user_settings_key( 'cornerlabels' );
$user_data             = get_user_meta( $current_user->ID, 'user_data', true );
$school_name           = OppiSchoolPicker\get_school_name( $user_data );
$school_url            = OppiSchoolPicker\get_school_url( $user_data );
$school_lunch_url      = OppiSchoolPicker\get_lounas_url ( $user_data );
$school_sharepoint_url = ""; // This needs to be added. Atm. can't get from School-picker plugin?

if ( empty( $user_data ) ) {
	return;
}
?>

<div class="sidebar-box sidebar-box--coat-of-arms-light school-info-box">

	<h3 class="sidebar-box__sub-title">
		<?php if ( ! empty( $school_name ) ) : ?>
			<?php echo $school_name; ?>
		<?php endif; ?>
	</h3>
	<div class="sidebar-box__sub-button">
		<?php if ( ! empty( $school_lunch_url ) ) : ?>
			<a href="<?php echo $school_lunch_url; ?>" class="button button--secondary" target="_blank">
				<?php Helpers\the_svg( 'icons/restaurant' ); ?>
				<?php esc_html_e( 'Ruokalista', 'helsinki-universal' ); ?>
				<?php Helpers\the_svg( 'icons/arrow-top-right' ); ?>
			</a>
		<?php endif; ?>
		<?php if ( ! empty( $school_url ) ) : ?>
			<a href="<?php echo $school_url; ?>" class="button button--secondary" target="_blank">
				<?php Helpers\the_svg( 'icons/company' ); ?>
				<?php esc_html_e( 'Oppilaitoksen verkkosivut', 'helsinki-universal' ); ?>
				<?php Helpers\the_svg( 'icons/arrow-top-right' ); ?>
			</a>
		<?php endif; ?>
		<?php if ( ! empty( $school_sharepoint_url ) ) : ?>
			<a href="<?php echo $school_sharepoint_url; ?>" class="button button--secondary" target="_blank">
				<?php Helpers\the_svg( 'icons/speechbubble-text' ); ?>
				<?php esc_html_e( 'Sisäinen sivusto', 'helsinki-universal' ); ?>
				<?php Helpers\the_svg( 'icons/arrow-top-right' ); ?>
			</a>
		<?php endif; ?>
	</div>
</div>