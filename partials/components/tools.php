<?php
use function \Opehuone\Helpers\the_svg;

use Opehuone\Utils;

$user_tools  = new User_tools();
?>
<div class="tools-wrapper">
    <div class="tools-wrapper__header">
        <h3 class="tools-wrapper__dock-title"><?php esc_html_e( 'Omat työkalut' ); ?></h3>
        <!-- Edit tools button that opens a modal -->
        <div class="tools-actions-row__item">
            <?php if ( is_user_logged_in() ) : ?>
                <button class="edit-tools-toggler"
                        title="<?php esc_html_e( 'Avaa uuden palvelun lisääminen' ); ?>"
                        data-toggle="modal" data-target="#edit-tools-modal">
                    <?php esc_html_e( 'Muokkaa' ); ?>
                    <?php the_svg( 'icons/settings' ); ?>
                </button>
            <?php endif; ?>
        </div>
    </div>

    <!-- Show all active tools -->
    <div class="tools-row tools-row--active" id="front-active-tools">
        <?php Utils\the_tools_row( true ); ?>
    </div>
    
    <div class="tools-actions-row">
        <!-- Show/hide tools button -->
        <div class="tools-actions-row__item">
            <button class="all-tools-toggler" aria-haspopup="true" aria-expanded="false"
                    aria-label="<?php esc_html_e( 'Avaa tai sulje loput työkalut' ); ?>">
                <?php the_svg('icons/arrow-down'); ?>
            </button>
        </div>
    </div>

    <!-- Container that holds all "inactive" tools -->
    <div class="tools-row-wrapper tools-row-wrapper--inactive">
        <div class="tools-wrapper__header">
            <h3 class="tools-wrapper__dock-title"><?php esc_html_e( 'Kaikki työkalut' ); ?></h3>
        </div>
        <div class="tools-row tools-row--inactive" id="front-inactive-tools">
            <?php Utils\the_tools_row( false ); ?>
        </div>
    </div>
</div>
