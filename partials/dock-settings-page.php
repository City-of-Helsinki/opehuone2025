<?php



require_once get_stylesheet_directory() . '/library/classes/Utils.php';
use LuuptekWP\Utils;
use Opehuone\Helpers;

$utils = new Utils();
?>

<section>
    <div class="container">
        <div
            class="directory-page-inner-content<?php echo $utils->has_post_cornerlabels() ? ' page-inner-content--has-cornerlabels' : ''; ?>">
            <?php get_template_part( 'partials/corner-labels' ); ?>
            <article>
                <form id="dock-settings">
                    <div class="page-inner-content__title-row">
                        <h1 class="page-inner-content__title">
                            <?php the_title(); ?>
                        </h1>
                        <div class="">
                            <button class="is-highlight-button" id="settings-submit">
                                <?php _e( 'Tallenna muutokset', TEXT_DOMAIN ); ?>
                            </button>
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="is-highlight-button">
                                <?php esc_html_e( 'Sulje', TEXT_DOMAIN ); ?>
                            </a>
                        </div>
                    </div>
                    <div class="user-settings-notifications"
                         id="user-settings-notifications"><?php _e( 'Asetuksia päivitetään...', TEXT_DOMAIN ); ?></div>
                    <?php
                    $user_settings = new User_settings();
                    $dock_items    = $user_settings::get_user_dock();
                    if ( count( $dock_items ) > 0 ) {
                        ?>
                        <div class="row">
                            <div class="dock-settings-column dock-settings-column--title">
                                <h2 class="dock-settings__subtitle">
                                    <?php _e( 'Pikalinkit', TEXT_DOMAIN ); ?>
                                </h2>
                            </div>
                            <div class="dock-settings-column dock-settings-column--list">
                                <ul class="dock-settings-list" id="dock-settings-list">
                                    <?php
                                    $hidden_field_value = '';
                                    $dock_items_count   = count( $dock_items );
                                    $i                  = 1;
                                    foreach ( $dock_items as $dock_item ) {
                                        $dock_title = $dock_item['title'];
                                        $item_id    = $dock_item['id'];
                                        $first_char = $dock_item['first_char'];
                                        $dock_url   = $dock_item['url'];
                                        $icon_url   = $dock_item['icon_url'];

                                        if ( $i < $dock_items_count ) {
                                            $hidden_field_value .= $item_id . ',';
                                        } else {
                                            $hidden_field_value .= $item_id;
                                        }
                                        ?>
                                        <li class="dock-settings-list__item" data-id="<?php echo $item_id; ?>">
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
                                                <button class="dock-settings-list__move dock-settings-list__move-down"
                                                        aria-label="<?php _e( 'Siirrä alas', TEXT_DOMAIN ); ?>">
                                                        <?php Helpers\the_svg( 'icons/angle-down' ); ?>
                                                </button>
                                                <button class="dock-settings-list__move dock-settings-list__move-up"
                                                        aria-label="<?php _e( 'Siirrä ylös', TEXT_DOMAIN ); ?>">
                                                    <?php Helpers\the_svg( 'icons/angle-up' ); ?>
                                                </button>
                                                <button class="dock-settings-list__drag" aria-hidden="true"
                                                        tabindex="-1">
                                                    <?php Helpers\the_svg( 'icons/drag-icon' ); ?>
                                                </button>
                                            </div>
                                        </li>
                                        <?php
                                        $i ++;
                                    }
                                    ?>
                                </ul>
                                <input type="hidden" id="new-dock-list"
                                       value="<?php echo esc_attr( $hidden_field_value ); ?>">
                            </div>
                            <div class="dock-settings-column dock-settings-column--helper">
                                <?php the_content(); ?>
                            </div>
                        </div>
                        <?php
                    }
                    get_template_part( 'partials/dock-settings-page-own-services' );
                    ?>
                </form>
            </article>
        </div>
    </div>
</section>
<?php
