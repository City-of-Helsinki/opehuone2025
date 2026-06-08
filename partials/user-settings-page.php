<?php

if ( ! is_user_logged_in() ) {
	return;
}

use function \Opehuone\TemplateFunctions\get_cornerlabels_without_default_value;
use function \Opehuone\TemplateFunctions\displayBannerWaveLineSvg;
use function Opehuone\TemplateFunctions\displayHeroAngledKorosSvg;

$current_user = wp_get_current_user();
$cornerlabels = Opehuone_user_settings_reader::get_user_settings_key( 'cornerlabels' );
$user_data    = get_user_meta( $current_user->ID, 'user_data', true );
$school_name   = OppiSchoolPicker\get_school_name( $user_data );
$profile_pic_url = "";

$user_favs = get_user_meta( get_current_user_id(), 'opehuone_favs', true );

if ( ! $user_favs ) {
	$user_favs = [];
}

$theme_image = get_field('profile_hero_image', 'options');
?>
<article class="content">
	<div class="hero has-default-style has-koros">
		<div class="hds-container hds-container--wide hero__container">
			<div class="hero__content">
                <div class="hero-text-content">
                    <?php get_template_part( 'partials/breadcrumbs' ); ?>
                    <h1 class="hero__title"><?php esc_html_e( 'Oma profiili', 'helsinki-universal' ); ?></h1>
                    <h2 class="hero__title"><?php echo esc_html( sprintf( 'Moi %s!', $current_user->user_firstname ) ); ?></h2>
                    <?php displayBannerWaveLineSvg(); ?>
                </div>
                <?php if( !empty( $theme_image ) ): ?>
                    <?php displayHeroAngledKorosSvg(); ?>
                    <div class="hero-image-content"><img src="<?php echo esc_url($theme_image['sizes']['large']); ?>" alt="<?php echo esc_attr($theme_image['alt'] ?: 'hero-image'); ?>" /></div>
                <?php endif; ?>
			</div>
		</div>
	</div>
    
	<div class="content__container hds-container">
		<div class="user-settings-page">            <p><?php esc_html_e('Oma profiili -sivulla voit vaihtaa koulutusastettasi, jonka mukaan Opehuoneen sisällöt sinulle ensisijaisesti suodatetaan.', 'helsinki-universal'); ?></p>
            <div class="user-settings-page__settings">
                <div class="user-settings-page__settings-row">
                    <div class="user-settings-page__settings-row__left-col">
                        <h3 class="user-settings-page__settings-title"><?php esc_html_e('Omat tiedot', 'helsinki-universal'); ?></h3>
                        <div class="o365-profile-picture">
                            <?php
                            if ( $profile_pic_url ) {
                                ?>
                                <img src="<?php echo esc_url( $profile_pic_url ); ?>"
                                        alt="<?php pll_esc_html_e( 'Microsoft-tilin profiilikuva' ); ?>">
                                <?php
                            } else {
                                pll_esc_html_e( 'Sinulla ei ole Microsoft-tiliin tallennettua profiilikuvaa. Tähän joku ohje käyttäjille, miten sen saa käyttöön?!?' );
                            }
                            ?>
                        </div>
                        <p>
                            <?php echo esc_html( $current_user->user_firstname . ' ' . $current_user->user_lastname ); ?>
                            <br>
                            <?php echo esc_html( $current_user->user_email ); ?>
                        </p>
                        <p>
                            <?php echo $school_name; ?>
                        </p>
                        <p>
                            <?php echo esc_html__( 'Koulutusasteesi:', 'helsinki-universal' ) . ' ' . ( ! empty( $cornerlabels ) ? implode( ', ', array_map( function ( $term_id ) {
                                $term = get_term( $term_id );
                                return $term ? $term->name : '';
                            }, $cornerlabels ) ) : esc_html__( 'Ei määritetty', 'helsinki-universal' ) ); ?>
                        </p>
                    </div>
                    <div class="user-settings-page__settings-row__right-col">
                        <form class="user-settings-form" id="user-settings">
                            <p><?php esc_html_e( 'Muokkaa koulutusastettasi', 'helsinki-universal' ); ?></p>
                            <span><?php esc_html_e('Valitse koulutusaste tai -asteet, joiden sisällöt haluat nähdä ensisijaisesti. Voit muokata koulutusastetta aina halutessasi.', 'helsinki-universal'); ?></span>
                            <div class="front-page-posts-filter__checkboxes-row">
                                <?php
                                $terms = get_cornerlabels_without_default_value();

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
            </div>
		</div>
	</div>
</article>
