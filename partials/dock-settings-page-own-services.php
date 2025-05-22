<?php
$own_pinned       = Utils()->get_user_own_services( 1 );
$own_not_pinned   = Utils()->get_user_own_services( 0 );
$all_own_services = array_merge( $own_pinned, $own_not_pinned );

if ( count( $all_own_services ) === 0 ) {
    return;
}

?>
<div class="row">
    <div class="dock-settings-column dock-settings-column--title">
        <h2 class="dock-settings__subtitle">
            <?php _e( 'Omat palvelut', TEXT_DOMAIN ); ?>
        </h2>
    </div>
    <div class="dock-settings-column dock-settings-column--list">
        <ul class="dock-settings-list dock-settings-list--own-services">
            <?php
            foreach ( $all_own_services as $own_service ) {
                $dock_title      = $own_service->service_name;
                $item_hash       = $own_service->identifier;
                $item_id         = $own_service->id;
                $item_active     = $own_service->visible;
                $first_char      = substr( $own_service->service_name, 0, 1 );
                $dock_url        = $own_service->service_url;
                $icon_url        = null;
                $li_classes      = $item_active === '0' ? 'dock-settings-list__item' : 'dock-settings-list__item dock-settings-list__item--active';
                $star_aria_label = $item_active === '0' ? __( 'Aseta tämä palvelu suosikiksi', TEXT_DOMAIN ) : __( 'Poista tämä palvelu suosikeista', TEXT_DOMAIN );
                ?>
                <li class="<?php echo esc_attr( $li_classes ); ?>" data-id="<?php echo esc_attr( $item_id ); ?>"
                    data-hash="<?php echo esc_attr( $item_hash ); ?>"
                    data-active="<?php echo esc_attr( $item_active ); ?>">
                    <a href="<?php echo $dock_url ?>" target="_blank"
                       class="dock-settings-list__link"
                       aria-label="<?php printf( __( 'Avaa palvelu: %s', TEXT_DOMAIN ), $dock_title ); ?>">
                        <?php if ( ! empty( $icon_url ) ) : ?>
                            <img src="<?php echo $icon_url; ?>"
                                 class="dock-settings-list__dock-icon"/>
                        <?php endif; ?>
                        <?php if ( empty( $icon_url ) ) : ?>
                            <div class="dock-settings-list__dock-icon-char">
                                <?php echo $first_char; ?>
                            </div>
                        <?php endif; ?>
                        <span class="dock-settings-list__title">
                                                <?php echo $dock_title; ?>
                                            </span>
                    </a>
                    <div class="dock-settings-list__item-actions">
                        <button class="dock-settings-list__star"
                                aria-label="<?php echo esc_attr( $star_aria_label ); ?>">
                            <?php Utils()->the_svg( 'star-fill' ); ?>
                        </button>
                        <button class="dock-settings-list__remove"
                                aria-label="<?php _e( 'Poista tämä palvelu', TEXT_DOMAIN ); ?>">
                            <?php Utils()->the_svg( 'cross' ); ?>
                        </button>
                    </div>
                </li>
                <?php
            }
            ?>
        </ul>
    </div>
</div>
