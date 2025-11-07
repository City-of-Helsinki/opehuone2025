<?php

use Opehuone\Helpers;
use function \Opehuone\TemplateFunctions\display_time_until_holidays;


// Display the login button if the user is not logged in
if ( ! is_user_logged_in() ) { ?>

    <div id="wpo365OpenIdRedirect" class="login-wrapper">
        <a href="javascript:void(0)" onclick="window.wpo365.pintraRedirect.toMsOnline()" class="login-button"
           title="<?php echo esc_html__('Kirjaudu edu.hel.fi' ); ?>">

            <?php helsinki_svg_icon('user'); ?>
            <span><?php echo esc_html( 'Kirjaudu edu.hel.fi' ); ?></span>
        </a>
    </div>

    <?php return;
}

$current_user = wp_get_current_user();

// get current user first and last name: FL
$first_name = $current_user->user_firstname;
$last_name  = $current_user->user_lastname;

// Get the first character of each name
$first_initial = substr( $first_name, 0, 1 );
$last_initial  = substr( $last_name, 0, 1 );

// Combine initials
$profile_name = strtoupper( $first_initial . $last_initial );
?>
<div class="profile-opener-wrapper">
	<button class="profile-opener" aria-expanded="false"
			aria-label="<?php esc_attr_e( 'Avaa profiilivalinnat', 'helsinki-universal' ); ?>">
		<span><?php echo esc_html( $profile_name ); ?></span>
	</button>
	<div class="profile-opener-dropdown">
		<div class="profile-opener-dropdown__name-row">
			<div class="profile-opener-dropdown__name-circle">
				<?php echo esc_html( $profile_name ); ?>
			</div>
			<div>
				<p class="profile-opener-dropdown__full-name">
					<?php echo esc_html( $first_name . ' ' . $last_name ); ?>
				</p>
				<p class="profile-opener-dropdown__email">
					<?php echo esc_html( $current_user->user_email ); ?>
				</p>
			</div>
		</div>

        <?php display_time_until_holidays(); ?>

		<nav aria-label="<?php esc_attr_e( 'Profiiliasetusten navigointi', 'helsinki-universal' ); ?>">
			<ul class="profile-opener-dropdown__links">
				<li class="profile-opener-dropdown__link-item">
					<a class="profile-opener-dropdown__link"
					   href="<?php echo esc_url( get_permalink( get_field( 'user_settings_page', 'option' ) ) ); ?>">
						<?php Helpers\the_svg( 'icons/face' ); ?>
						<?php esc_html_e( 'Oma profiili', 'helsinki-universal' ); ?>
					</a>
				</li>
				<li class="profile-opener-dropdown__link-item">
					<a class="profile-opener-dropdown__link"
					   href="<?php echo esc_url( get_permalink( get_field( 'contact_page', 'option' ) ) ); ?>">
						<?php Helpers\the_svg( 'icons/envelope' ); ?>
						<?php esc_html_e( 'Ota yhteyttä Opehuoneeseen', 'helsinki-universal' ); ?>
					</a>
				</li>
				<li class="profile-opener-dropdown__link-item">
					<a class="profile-opener-dropdown__link" href="<?php echo esc_url( wp_logout_url() ); ?>">
						<?php Helpers\the_svg( 'icons/sign-out' ); ?>
						<?php esc_html_e( 'Kirjaudu ulos', 'helsinki-universal' ); ?>
					</a>
				</li>
			</ul>
		</nav>
	</div>
</div>
