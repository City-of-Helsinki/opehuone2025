<?php

use Opehuone\Helpers;

if ( ! function_exists( 'get_field' ) ) {
    return;
}

$service_notices_repeater = get_field( 'service_notices', 'option' );

if ( empty( $service_notices_repeater ) ) {
    return;
}

foreach ( $service_notices_repeater as $notice ) :
    $title       = $notice['service_notice_title'] ? $notice['service_notice_title'] : '';
    $text        = $notice['service_notice_text'] ? $notice['service_notice_text'] : '';
    $read_more   = $notice['service_notice_text_read_more'] ? $notice['service_notice_text_read_more'] : '';
    $link        = $notice['service_notice_link'] ? $notice['service_notice_link'] : '';
    $type        = $notice['service_notice_type'] ? $notice['service_notice_type'] : '';
    $assigned_to = $notice['service_notice_assigned'] ? $notice['service_notice_assigned'] : ''; // These are cornerlabels taxonomy ID:s
    $is_timed    = $notice['timed_service_notice'] ? $notice['timed_service_notice'] : false;

    if ( $is_timed ) {
        $start_time = $notice['timed_service_notice_start'] ? $notice['timed_service_notice_start'] : '';
        $end_time   = $notice['timed_service_notice_end'] ? $notice['timed_service_notice_end'] : '';
    }
    ?>
    <section class="service-notice service-notice--<?php echo esc_attr( $type ); ?>">

        <?php if ( $title ) : ?>
            <h2 class="service-notice__title">
                <?php if ( $type === 'info' ) : ?>
                    <?php Helpers\the_svg( 'icons/info-circle' ); ?>
                <?php else : ?>
                    <?php Helpers\the_svg( 'icons/' . $type ); ?>
                <?php endif; ?>
                <?php echo esc_html( $title ); ?>
            </h2>
        <?php endif; ?>

        <?php if ( ! empty( $text ) ) : ?>
            <div class="service-notice__text">
                <?php echo wp_kses_post( $text ); ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $link ) && empty( $read_more ) ) : ?>
            <a href="<?php echo esc_url( $link['url'] ); ?>" target="<?php echo esc_attr( $link['target'] ); ?>" class="service-notice__link">
                <?php echo esc_html( $link['title'] ); ?>
                <?php Helpers\the_svg( 'icons/arrow-right-lg' ); ?>
            </a>
        <?php endif; ?>

        <?php if ( ! empty( $read_more ) ) : ?>
            <button class="service-notice__toggler">
                <span class="service-notice__toggler-text"><?php esc_html_e( 'Lue lisää', 'helsinki-universal' ); ?></span>
                <?php Helpers\the_svg( 'icons/arrow-down' ); ?>
            </button>
            <div class="service-notice__read-more">
                <?php echo wp_kses_post( $read_more ); ?>
                <?php if ( ! empty( $link ) ) : ?>
                    <a href="<?php echo esc_url( $link['url'] ); ?>" target="<?php echo esc_attr( $link['target'] ); ?>" class="service-notice__link">
                        <?php echo esc_html( $link['title'] ); ?>
                        <?php Helpers\the_svg( 'icons/arrow-right-lg' ); ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <button class="service-notice__close" type="button">
            <span class="screen-reader-text"><?php echo esc_html( $title ) . ' - '; esc_html_e( 'Sulje ilmoitus', 'helsinki-universal' ); ?></span>
            <?php Helpers\the_svg( 'icons/cross' ); ?>
        </button>
    </section>

<?php endforeach; ?>