<?php

use Opehuone\Helpers;

if ( ! function_exists( 'get_field' ) ) {
    return;
}

$service_notices_repeater = get_field( 'service_notices', 'option' );

if ( empty( $service_notices_repeater ) ) {
    return;
}

$user_cornerlabels        = \Opehuone_user_settings_reader::get_user_settings_key('cornerlabels');
$user_cornerlabels_to_int = array_map( 'absint', $user_cornerlabels );
?>

<div class="service-notices">
    <div class="hds-container">

        <?php 
        foreach ( $service_notices_repeater as $notice ) :
            $title       = $notice['service_notice_title'] ? $notice['service_notice_title'] : '';
            $text        = $notice['service_notice_text'] ? $notice['service_notice_text'] : '';
            $read_more   = $notice['service_notice_text_read_more'] ? $notice['service_notice_text_read_more'] : '';
            $link        = $notice['service_notice_link'] ? $notice['service_notice_link'] : '';
            $type        = $notice['service_notice_type'] ? $notice['service_notice_type'] : '';
            $assigned_to = $notice['service_notice_assigned'] ? $notice['service_notice_assigned'] : ''; // These are cornerlabel ID:s (int)

            // Create hash for the notice to use as a unique ID in the HTML and localStorage key.
            // If hash (=notices content) changes --> it will be treated as a new notice and shown again even if the previous one was closed.
            $notice_id   = md5( $title . $text . $read_more . $type );

            // Check if notice is assigned to any of the user's cornerlabels (if not, skip this notice). 
            // Empty $assigned_to means it's not restricted to any cornerlabel so its visible to all users.
            if ( ! empty( $assigned_to ) ) {
                if ( empty( array_intersect( $assigned_to, $user_cornerlabels_to_int ) ) ) {
                    continue;
                }
            }

            $is_timed = $notice['timed_service_notice'] ? $notice['timed_service_notice'] : false;

            // If timed notice = true and start time has not been reached or end time has passed, skip this notice.
            if ( $is_timed ) {
                $now   = current_datetime();
                $start = ! empty( $notice['timed_service_notice_start'] ) ? date_create( $notice['timed_service_notice_start'], wp_timezone() ) : false;
                $end   = ! empty( $notice['timed_service_notice_end'] ) ? date_create( $notice['timed_service_notice_end'], wp_timezone() ) : false;

                if ( ( $start && $now < $start ) || ( $end && $now > $end ) ) {
                    continue;
                }
            }
            ?>
            <section class="service-notice service-notice--<?php echo esc_attr( $type ); ?>" data-notice-id="<?php echo esc_attr( $notice_id ); ?>">

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
                
                <?php if ( $type !== 'error' ) : ?>
                    <button class="service-notice__close" type="button">
                        <span class="screen-reader-text"><?php echo esc_html( $title ) . ' - '; esc_html_e( 'Sulje ilmoitus', 'helsinki-universal' ); ?></span>
                        <?php Helpers\the_svg( 'icons/cross' ); ?>
                    </button>
                <?php endif; ?>
                
            </section>

        <?php endforeach; ?>	

    </div>
</div>