<?php
if ( ! is_user_logged_in() ) {
    return;
}

require_once get_stylesheet_directory() . '/library/classes/Utils.php';
use LuuptekWP\Utils;
use Opehuone\Helpers;

$user_settings = new User_settings();
$dock_items = $user_settings::get_user_dock();

$utils = new Utils();
$own_active_services = $utils->get_user_own_services( 1 );
$own_non_active_services = $utils->get_user_own_services( 0 );
$i = 0;

if ( count( $dock_items ) > 0 ) {
    ?>
    <div class="dock" role="navigation" aria-hidden="false" tabindex="0">
        <div class="row">
            <h3 class="dock-title">Omat työkalut</h3>
            <div class="dock-item--toggler">
                <a href="#" target="_blank" class="dock-link dock-toggler"
                    aria-label="<?php _e( 'Avaa dock asetukset', TEXT_DOMAIN ); ?>" role="button">
                    <?php _e( 'Muokkaa', TEXT_DOMAIN ); ?><?php Helpers\the_svg( 'icons/dock' ); ?>
                </a>
            </div>
        </div>
        <div class="row">
            <ul class="dock-list">
                <?php

                foreach ( $own_active_services as $own_service ) {
                    $dock_item_class = 'dock-item';
                    if ( $i > 10 ) {
                        $dock_item_class = 'dock-item dock-item--desktop-hidden';
                    }
                    $dock_title = $own_service->service_name;
                    $item_id    = $own_service->id;
                    $first_char = substr( $own_service->service_name, 0, 1 );
                    $dock_url   = $own_service->service_url;
                    $icon_url   = null;
                    ?>
                    <li class="<?php echo esc_attr( $dock_item_class ); ?>">
                        <a href="<?php echo $dock_url ?>" target="_blank" class="dock-link"
                        data-placement="top" data-toggle="tooltip" title="<?php echo $dock_title; ?>"
                        aria-label="<?php echo $dock_title; ?>">
                            <?php if ( ! empty( $icon_url ) ) : ?>
                                <img src="<?php echo $icon_url; ?>" class="dock-icon"/>
                            <?php endif; ?>
                            <?php if ( empty( $icon_url ) ) : ?>
                                <div class="dock-icon-char">
                                    <?php echo $first_char; ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php
                    $i ++;
                }


                foreach ( $dock_items as $dock_item ) {
                    $dock_item_class = 'dock-item';
                    if ( $i > 10 ) {
                        $dock_item_class = 'dock-item dock-item--desktop-hidden';
                    }
                    $dock_title = $dock_item['title'];
                    $item_id    = $dock_item['id'];
                    $first_char = $dock_item['first_char'];
                    $dock_url   = $dock_item['url'];
                    $icon_url   = $dock_item['icon_url'];
                    ?>
                    <li class="<?php echo esc_attr( $dock_item_class ); ?>">
                        <a href="<?php echo $dock_url ?>" target="_blank" class="dock-link"
                        data-placement="top" data-toggle="tooltip" title="<?php echo $dock_title; ?>"
                        aria-label="<?php echo $dock_title; ?>">
                            <?php if ( ! empty( $icon_url ) ) : ?>
                                <img src="<?php echo $icon_url; ?>" class="dock-icon"/>
                            <?php endif; ?>
                            <?php if ( empty( $icon_url ) ) : ?>
                                <div class="dock-icon-char">
                                    <?php echo $first_char; ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php
                    $i ++;
                }

                foreach ( $own_non_active_services as $own_service ) {
                    $dock_item_class = 'dock-item';
                    if ( $i > 10 ) {
                        $dock_item_class = 'dock-item dock-item--desktop-hidden';
                    }
                    $dock_title = $own_service->service_name;
                    $item_id    = $own_service->id;
                    $first_char = substr( $own_service->service_name, 0, 1 );
                    $dock_url   = $own_service->service_url;
                    $icon_url   = null;
                    ?>
                    <li class="<?php echo esc_attr( $dock_item_class ); ?>">
                        <a href="<?php echo $dock_url ?>" target="_blank" class="dock-link"
                        data-placement="top" data-toggle="tooltip" title="<?php echo $dock_title; ?>"
                        aria-label="<?php echo $dock_title; ?>">
                            <?php if ( ! empty( $icon_url ) ) : ?>
                                <img src="<?php echo $icon_url; ?>" class="dock-icon"/>
                            <?php endif; ?>
                            <?php if ( empty( $icon_url ) ) : ?>
                                <div class="dock-icon-char">
                                    <?php echo $first_char; ?>
                                </div>
                            <?php endif; ?>
                        </a>
                    </li>
                    <?php
                    $i ++;
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="whole-dock" role="navigation" aria-hidden="true" tabindex="-1">
        <div class="whole-dock__content">
            <div class="whole-dock__actions whole-dock__action--spacebetween">
                <div class="whole-dock__actions__buttons">
                    <a href="#" class="open-new-service-wrapper"
                       aria-label="<?php _e( 'Lisää uusi oma palvelu', TEXT_DOMAIN ); ?>">
                        <?php _e( 'Lisää uusi', TEXT_DOMAIN ); ?><?php Helpers\the_svg( 'icons/rounded-plus' ); ?>
                    </a>
                    <a href="<?php echo get_permalink( get_field( 'dock_settings_page', 'option' ) ); ?>"
                       aria-label="<?php _e( 'Muokkaa valikon asetuksia omassa näkymässä', TEXT_DOMAIN ); ?>">
                        <?php _e( 'Muokkaa', TEXT_DOMAIN ); ?><?php Helpers\the_svg( 'icons/settings' ); ?>
                    </a>
                </div>
                <a href="#" class="dock-toggler dock-toggler-close" role="button"
                   aria-label="<?php _e( 'Sulje dock-asetukset', TEXT_DOMAIN ); ?>">
                   <?php Helpers\the_svg( 'icons/close-24px' ); ?>
                </a>
            </div>
            <?php get_template_part( 'partials/components/dock-new-service' ); ?>
            <ul class="whole-dock-list">
                <?php
                $utils->the_own_services_row( true );

                foreach ( $dock_items as $dock_item ) {
                    $dock_title = $dock_item['title'];
                    $item_id    = $dock_item['id'];
                    $first_char = $dock_item['first_char'];
                    $dock_url   = $dock_item['url'];
                    $icon_url   = $dock_item['icon_url'];
                    ?>
                    <li class="whole-dock-item">
                        <a href="<?php echo $dock_url ?>" target="_blank" class="whole-dock-link"
                           aria-label="<?php echo esc_attr( $dock_title ); ?>">
                            <?php if ( ! empty( $icon_url ) ) : ?>
                                <img src="<?php echo $icon_url; ?>" class="whole-dock-icon"/>
                            <?php endif; ?>
                            <?php if ( empty( $icon_url ) ) : ?>
                                <div class="whole-dock-icon-char">
                                    <?php echo $first_char; ?>
                                </div>
                            <?php endif; ?>
                            <span class="whole-dock-item-title">
                                <?php echo $dock_title; ?>
                            </span>
                        </a>
                    </li>
                    <?php
                }

                $utils->the_own_services_row( false );
                ?>
            </ul>
        </div>
    </div>
    <?php
}
