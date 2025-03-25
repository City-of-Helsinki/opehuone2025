<?php

if ( ! is_user_logged_in() ) {
	return;
}

$current_user = wp_get_current_user();
$cornerlabels = Opehuone_user_settings_reader::get_user_settings_key( 'cornerlabels' );
$user_data    = get_user_meta( $current_user->ID, 'user_data', true );
$school_name  = OppiSchoolPicker\get_school_name( $user_data );

$user_favs = get_user_meta( get_current_user_id(), 'opehuone_favs', true );

if ( ! $user_favs ) {
	$user_favs = [];
}
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
						<?php echo esc_html( sprintf( 'Olet kirjatunut sisään koulutiedolla: %s', $school_name ) ); ?>
					</p>
				</div>
				<div>
					<form class="user-settings-form" id="user-settings">
						<p><?php esc_html_e( 'Muuta koulutusastetta', 'helsinki-universal' ); ?></p>
						<div class="front-page-posts-filter__checkboxes-row">
							<?php
							$terms = get_terms( [
								'taxonomy'   => 'cornerlabels',
								'hide_empty' => true,
							] );

							if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
								foreach ( $terms as $term ) {
									?>
									<label class="front-page-posts-filter__checkbox-label">
										<input type="checkbox" class="front-page-posts-filter__checkbox-input"
											   name="cornerlabels[]"
											   value="<?php echo esc_attr( $term->term_id ); ?>" <?php echo in_array( $term->term_id, $cornerlabels ) ? ' checked' : ''; ?>>
										<?php echo esc_html( $term->name ); ?>
									</label>
									<?php
								}
							}
							?>
						</div>
						<button type="submit"
								class="user-settings-form__submit-button"><?php esc_html_e( 'Tallenna muutos' ); ?></button>
					</form>
				</div>
			</div>
			<div class="user-settings-page__favorites-wrapper">
				<h2 class="user-settings-page__secondary-title">
					<?php esc_html_e( 'Tallennetut sisällöt', 'helsinki-universal' ); ?>
				</h2>
				<ul class="user-favs-list user-favs-list--grid">
					<?php
					// Loop through favs
					foreach ( $user_favs as $fav_post_id ) {
						$category_name = esc_html__( 'Sivut', 'helsinki-universal' );

						if ( get_post_type( $fav_post_id ) === 'post' ) {
							$category_name = esc_html__( 'Uutiset', 'helsinki-universal' );
						}
						?>
						<li class="user-favs-list__item">
							<a href="<?php echo esc_url( get_permalink( $fav_post_id ) ); ?>" class="user-favs-list__link">
						<span
							class="user-favs-list__link-category"><?php echo esc_html( $category_name ); ?></span>
								<span
									class="user-favs-list__link-title"><?php echo esc_html( get_the_title( $fav_post_id ) ); ?></span>
							</a>
						</li>
						<?php
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</article>
