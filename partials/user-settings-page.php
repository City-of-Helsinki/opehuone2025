<?php

if ( ! is_user_logged_in() ) {
	return;
}

$current_user = wp_get_current_user();

$highlight_theme_colors = [
	'metro',
	'fog',
	'copper',
	'suomenlinna',
	'coat',
	'engel',
];

$user_settings    = new User_settings();
$current_settings = $user_settings->get_user_settings();
$user_data        = get_user_meta( $current_user->ID, 'user_data', true );
$school_name      = OppiSchoolPicker\get_school_name( $user_data );
?>
<article class="content">
	<div class="hero has-default-style has-koros">
		<div class="hds-container hero__container">
			<div class="hero__content">
				<h1 class="hero__title"><?php echo esc_html( sprintf( 'Moi %s!', $current_user->user_firstname ) ); ?></h1>
				<p class="hero__excerpt excerpt size-xl">
					<?php get_template_part( 'partials/time-until' ); ?>
				</p>
			</div>
		</div>

		<div class="hds-koros hds-koros--basic hds-koros--flip-horizontal">
			<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="100%" height="42">
				<defs>
					<pattern id="koros_basic-page_hero" x="0" y="0" width="53" height="42"
							 patternUnits="userSpaceOnUse">
						<path transform="scale(2.65)" d="M0,800h20V0c-4.9,0-5,2.6-9.9,2.6S5,0,0,0V800z"></path>
					</pattern>
				</defs>
				<rect fill="url(#koros_basic-page_hero)" width="100%" height="42"></rect>
			</svg>
		</div>

	</div>
	<div class="content__container hds-container">
		<div class="user-settings-page">
			<h2 class="user-settings-page__main-title">
				<?php esc_html_e( 'Omat tiedot', 'helsinki-universal' ); ?>
			</h2>
			<div class="user-settings-page__settings-row">
				<div>
					<p>
						<?php echo esc_html( $current_user->user_firstname . ' ' . $current_user->user_lastname ); ?>
						<br>
						<?php echo esc_html( $current_user->user_email ); ?>
					</p>
					<p>
						Olet kirjatunut sisään koulutiedolla: <?php echo esc_html( $school_name ); ?>
					</p>
				</div>
				<div>

				</div>
			</div>
		</div>
	</div>
</article>
