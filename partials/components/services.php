<?php
use function \Opehuone\Helpers\the_svg;

use Opehuone\Utils;

$user_services  = new User_services();
?>
<div class="services-wrapper">
    <h3 class="services-wrapper__dock-title"><?php esc_html_e( 'Omat työkalut' ); ?></h3>
    <!-- Show all active services -->
    <div class="services-row services-row--active">
        <?php
        Utils\the_own_services_row( true );
        Utils\the_services_row( true, $user_services );
        ?>
    </div>
    <div class="services-actions-row">
        <div class="services-actions-row__item">

        </div>

        <!-- Show/hide services button -->
        <div class="services-actions-row__item">
            <button class="all-services-toggler" aria-haspopup="true" aria-expanded="false"
                    aria-label="<?php esc_html_e( 'Avaa tai sulje loput palvelut' ); ?>">
                <?php the_svg('icons/arrow-down'); ?>
            </button>
        </div>

        <!-- Add new service button that opens a modal -->
        <div class="services-actions-row__item">
            <?php if ( is_user_logged_in() ) : ?>
                <button class="add-new-service-toggler"
                        title="<?php esc_html_e( 'Avaa uuden palvelun lisääminen' ); ?>"
                        data-toggle="modal" data-target="#add-new-service-modal">
                    <span><?php esc_html_e( 'Lisää oma palvelu' ); ?></span>
                    <?php the_svg( 'icons/add-new-icon' ); ?>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Container that holds all "inactive" services -->
    <div class="services-row services-row--inactive">
        <?php
        Utils\the_services_row( false, $user_services );
        Utils\the_own_services_row( false );
        ?>
    </div>
</div>
