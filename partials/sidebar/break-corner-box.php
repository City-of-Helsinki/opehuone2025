<?php

use function \Opehuone\TemplateFunctions\fetchWikipediaFeaturedArticles;


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
        <svg class="break-corner-box__svg" width="28" height="28" viewBox="0 0 28 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M13.9998 0.667969C21.3636 0.667969 27.3332 6.63751 27.3332 14.0013C27.3332 21.3651 21.3636 27.3346 13.9998 27.3346C6.63604 27.3346 0.666504 21.3651 0.666504 14.0013C0.666504 6.63751 6.63604 0.667969 13.9998 0.667969ZM13.9998 3.33464C8.1088 3.33464 3.33317 8.11026 3.33317 14.0013C3.33317 19.8923 8.1088 24.668 13.9998 24.668C19.8909 24.668 24.6665 19.8923 24.6665 14.0013C24.6665 8.11026 19.8909 3.33464 13.9998 3.33464ZM9.8628 16.0792C10.6371 17.6525 12.2316 18.668 14.0071 18.668C15.7126 18.668 17.2515 17.731 18.0567 16.2613L18.1401 16.1018L20.5262 17.2924C19.3007 19.7485 16.7953 21.3346 14.0071 21.3346C11.2747 21.3346 8.81455 19.8114 7.564 17.4408L7.47021 17.2567L9.8628 16.0792ZM9.33317 8.66797C10.4377 8.66797 11.3332 9.5634 11.3332 10.668C11.3332 11.7725 10.4377 12.668 9.33317 12.668C8.2286 12.668 7.33317 11.7725 7.33317 10.668C7.33317 9.5634 8.2286 8.66797 9.33317 8.66797ZM18.6665 8.66797C19.7711 8.66797 20.6665 9.5634 20.6665 10.668C20.6665 11.7725 19.7711 12.668 18.6665 12.668C17.5619 12.668 16.6665 11.7725 16.6665 10.668C16.6665 9.5634 17.5619 8.66797 18.6665 8.66797Z" fill="black"/>
        </svg>

        <span>
            <?php esc_html_e('Aloita rauhoittumishetki'); ?>
        </span>
    </button>
    <div class="concentration-opener">
        <div class="concentration-opener__wrapper">
            <div class="concentration-opener__items">
                <h2 class="concentration-opener__title">
                    <?php pll_esc_html_e( 'Valitse rauhoittumishetken pituus' ); ?>
                </h2>
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
                                <span class="concentration-opener__list-item-button__name">
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


    <div class="break-corner-box__wikipedia">
        <div class="break-corner-box__wikipedia-header">
            <?php \Opehuone\Helpers\the_svg('icons/' . 'wiki'); ?>
            <span class="break-corner-box__wikipedia-title"><?php esc_html_e('Wikipedia viikon suosituimmat artikkelit', 'helsinki-universal'); ?></span>

        </div>
        <?php fetchWikipediaFeaturedArticles(); ?>
    </div>
</div>