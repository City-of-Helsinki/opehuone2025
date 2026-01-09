<?php

use function \Opehuone\Helpers\the_svg;

// Fetch concentration CPT posts
$query_args = [
'post_type'      => 'concentration',
'orderby'        => 'meta_value_num',
'meta_key'       => 'concentration_duration',
'order'          => 'ASC',
'posts_per_page' => - 1,
];

$query = new WP_Query( $query_args );

if ( ! $query->have_posts() ) {
    wp_reset_postdata();

    return;
}

?>

<div class="break-corner-box sidebar-box sidebar-box--coat-of-arms-light">
    <h3 class="sidebar-box__sub-title"><?php esc_html_e( 'Taukonurkka', 'helsinki-universal' ); ?></h3>
    <button class="break-corner-box__button actions-wrapper__list-item--concentration">
        <div class="break-corner-box__button-face-svg">
            <?php the_svg('icons/face') ?>
        </div>

        <span>
            <?php esc_html_e('Aloita rauhoittumishetki'); ?>
        </span>
        <div class="break-corner-box__button-chevron-down-svg">
            <?php the_svg('icons/angle-down') ?>
        </div>
        <div class="break-corner-box__button-chevron-up-svg">
            <?php the_svg('icons/angle-up') ?>

        </div>


    </button>
    <div class="concentration-opener">
        <div class="concentration-opener__wrapper">
            <div class="concentration-opener__items">
                <h4 class="concentration-opener__title">
                    <?php pll_esc_html_e( 'Valitse rauhoittumishetki' ); ?>
                </h4>
                <ul class="concentration-opener__list">
                    <?php
                    while ( $query->have_posts() ) {
                        $query->the_post();
                        $duration = get_post_meta( get_the_ID(), 'concentration_duration', true );
                        $track    = get_field( 'concentration_music' );
                        ?>
                        <li class="concentration-opener__list-item">
                            <button class="concentration-opener__list-item-button" aria-pressed="false"
                                    data-duration="<?php echo esc_attr( $duration ); ?>"
                                    data-post-id="<?php echo esc_attr( get_the_ID() ); ?>"
                                    data-track-url="<?php echo esc_url( $track ); ?>">
							<span class="concentration-opener__list-item-button__time">
								<span class="concentration-opener__list-item-button__time__center">
									<?php echo esc_html( $duration . 'min' ); ?>
								</span>
							</span>
                                <span class="concentration-opener__list-item-button__name concentration-opener__list-item-button__time__center">
								<?php echo esc_html( get_the_title() ); ?>
							</span>
                            </button>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <button class="concentration-opener__button start-concentration" disabled>
                    <?php pll_esc_html_e( 'Käynnistä rauhoittumishetki' ); ?>
                </button>
            </div>
        </div>
    </div>


    <div class="concentration" id="concentration">
    <div class="concentration__content">
        <div class="concentration__actions">
            <div class="concentration__actions-row">
                <button class="concentration__action-button stop-concentration">
                    <?php the_svg('icons/' . 'close-24px'); ?>
                </button>
                <button class="concentration__action-button mute-concentration">
                    <?php the_svg('icons/' . 'audio-mute'); ?>
                </button>
            </div>
        </div>
        <div class="concentration__loading">
            <?php the_svg('icons/' . 'refresh'); ?>
        </div>
        <div class="concentration__text">
            <div class="concentration__text-close-wrapper">
                <button class="concentration__text-close">
                    <?php the_svg('icons/' . 'close-24px'); ?>
                </button>
            </div>
            <div class="concentration__text-wrapper">

            </div>
        </div>
    </div>
</div>