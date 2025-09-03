<?php
use Opehuone\Utils;
use function Opehuone\Helpers\the_svg;

$service_post_id     = isset( $args['post_id'] ) ? $args['post_id'] : null;
$is_own_service      = isset( $args['is_own_service'] ) ? $args['is_own_service'] : false;
$own_id              = isset( $args['own_service_id'] ) ? $args['own_service_id'] : null;
$own_identifier      = isset( $args['own_service_identifier'] ) ? $args['own_service_identifier'] : null;
$service_description = isset( $args['description'] ) ? $args['description'] : null;

$hover_args = [
    'name'        => $args['title'],
    'url'         => $args['url'],
    'description' => $service_description,
];


?>
<div class="services-column">
    <div class="services-column__content">
        <a href="<?php echo esc_url( $args['url'] ); ?>" class="services-column__link"
           data-post-id="<?php echo esc_attr( $service_post_id ); ?>" target="_blank"
           aria-label="<?php echo esc_html( $args['title'] . Utils\get_open_new_tab_text() ); ?>">
            <?php if ( true === $is_own_service ) : ?>
                <span class="services-column__first-letter">
					<?php echo esc_html( substr( $args['title'], 0, 1 ) ); ?>
				</span>
            <?php else : ?>
                <img src="<?php echo esc_url( $args['icon_url'] ); ?>"
                     alt="<?php echo esc_attr( $args['icon_alt'] ); ?>"
                     class="services-column__image"/>
            <?php endif; ?>
        </a>
        <?php if ( is_user_logged_in() ) : ?>
            <button class="services-column__toggler"
                    aria-label="<?php echo esc_html__( 'Avaa tai sulje toiminnot palvelulle:' ) . ' ' . esc_html( $args['title'] ); ?>"
                    aria-haspopup="true"
                    aria-expanded="false">
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <g fill="none" fill-rule="evenodd">
                        <path d="M0 0h24v24H0z"></path>
                        <path
                            d="M12 10a2 2 0 110 4 2 2 0 010-4zm7 0a2 2 0 110 4 2 2 0 010-4zM5 10a2 2 0 110 4 2 2 0 010-4z"
                            fill="currentColor"></path>
                    </g>
                </svg>
            </button>
            <ul class="services-item-dropdown" tabindex="-1">
                <?php if ( $args['active_service'] ) : ?>
                    <?php if ( true === $is_own_service ) : // If Pinned own service ?>
                        <li class="services-item-dropdown__item">
                            <a href="#" class="services-item-dropdown__link services-item-dropdown__link--pin-own"
                               data-own-service-id="<?php echo esc_attr( $own_id ); ?>"
                               data-own-service-set-visible="0"
                               data-own-service-identifier="<?php echo esc_attr( $own_identifier ); ?>"
                               aria-label="<?php esc_html_e( 'Irroita palveluista' ); ?>">
                                <?php the_svg( 'icons/pin-off-icon' ); ?>
                                <?php esc_html_e( 'Irroita palveluista' ); ?>
                            </a>
                        </li>
                        <li class="services-item-dropdown__item">
                            <a href="#" class="services-item-dropdown__link services-item-dropdown__link--remove-own"
                               data-own-service-id="<?php echo esc_attr( $own_id ); ?>"
                               data-own-service-identifier="<?php echo esc_attr( $own_identifier ); ?>"
                               aria-label="<?php esc_html_e( 'Poista' ); ?>">
                                <?php the_svg( 'icons/trash-icon' ); ?>
                                <?php esc_html_e( 'Poista' ); ?>
                            </a>
                        </li>
                    <?php else : // If pinned general service ?>
                        <li class="services-item-dropdown__item">
                            <a href="#" class="services-item-dropdown__link services-item-dropdown__link--remove"
                               data-item-id="<?php echo esc_attr( sanitize_title( $args['title'] ) ); ?>"
                               aria-label="<?php esc_html_e( 'Irroita palveluista' ); ?>">
                                <?php the_svg( 'icons/pin-off-icon' ); ?>
                                <?php esc_html_e( 'Irroita palveluista' ); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php else : ?>
                    <?php if ( true === $is_own_service ) : // If unpinned own service ?>
                        <li class="services-item-dropdown__item">
                            <a href="#" class="services-item-dropdown__link services-item-dropdown__link--pin-own"
                               data-own-service-id="<?php echo esc_attr( $own_id ); ?>"
                               data-own-service-set-visible="1"
                               data-own-service-identifier="<?php echo esc_attr( $own_identifier ); ?>"
                               aria-label="<?php esc_html_e( 'Kiinnitä palveluihin' ); ?>">
                                <?php the_svg( 'icons/pin-on-icon' ); ?>
                                <?php esc_html_e( 'Kiinnitä palveluihin' ); ?>
                            </a>
                        </li>
                        <li class="services-item-dropdown__item">
                            <a href="#" class="services-item-dropdown__link services-item-dropdown__link--remove-own"
                               data-own-service-id="<?php echo esc_attr( $own_id ); ?>"
                               data-own-service-identifier="<?php echo esc_attr( $own_identifier ); ?>"
                               aria-label="<?php esc_html_e( 'Poista' ); ?>">
                                <?php the_svg( 'icons/trash-icon' ); ?>
                                <?php esc_html_e( 'Poista' ); ?>
                            </a>
                        </li>
                    <?php else : // If unpinned general service ?>
                        <li class="services-item-dropdown__item">
                            <a href="#" class="services-item-dropdown__link services-item-dropdown__link--add"
                               data-item-id="<?php echo esc_attr( sanitize_title( $args['title'] ) ); ?>"
                               aria-label="<?php esc_html_e( 'Kiinnitä palveluihin' ); ?>">
                                <?php the_svg( 'icons/pin-on-icon' ); ?>
                                <?php esc_html_e( 'Kiinnitä palveluihin' ); ?>
                            </a>
                        </li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        <?php endif; ?>
        <?php get_template_part( 'partials/blocks/b-service-hover-item', '', $hover_args ); ?>
    </div>
</div>
