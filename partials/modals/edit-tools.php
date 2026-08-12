<?php
use function Opehuone\Helpers\the_svg;

use Opehuone\Utils;

$user_tools  = new User_tools();
?>

<div class="modal fade" id="edit-tools-modal" tabindex="-1" role="dialog" aria-labelledby="edit-tools-modal"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="edit-tools-modal__title">
                    <?php esc_html_e( 'Omat työkalut' ); ?>
                </h2>
                <span class="edit-tools-modal__info" >
                    <?php esc_html_e( 'Järjestä työkaluriviäsi raahaamalla työkalu haluttuun kohtaan.' ); ?>
                </span>
                <button type="button" class="close" data-dismiss="modal" aria-label="<?php esc_html_e( 'Sulje omien työkalujen muokkaaminen' ); ?>">
                    <?php esc_html_e( 'Sulje' ); ?>
                    <span aria-hidden="true"><?php the_svg( 'icons/close-icon' ); ?></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-tools-form">
                    <!-- Show all active tools -->
                    <div id="active-tools" class="tools-row tools-row--active connected-sortable-tools">
                       <?php Utils\the_tools_row( true ); ?>
                    </div>

                    <div class="modal-header">
                        <h3 class="edit-tools-modal__title edit-tools-modal__title--inactive">
                            <?php esc_html_e( 'Kaikki työkalut' ); ?>
                        </h3>
                        <span class="edit-tools-modal__info" >
                            <?php esc_html_e( 'Valitse lisää työkaluja raahaamalla niitä Omat työkalut -näkymään.' ); ?>
                        </span>
                    </div>

                    <!-- Container that holds all "inactive" tools -->
                    <div id="inactive-tools"  class="tools-row tools-row--inactive connected-sortable-tools">
                        <?php Utils\the_tools_row( false ); ?>
                    </div>
                    <button type="submit" class="edit-tools-form__btn edit-tools-form__btn--submit" id="submit-edit-tools-form">
                        <?php esc_html_e( 'Tallenna muutokset' ); ?>
                    </button>
                    <span class="edit-tools-modal__info" >
                        <?php esc_html_e( 'Etkö löydä tarvitsemaasi työkalua? Ota yhteyttä opehuone@hel.fi' ); ?>
                    </span>
                    <div class="edit-tools-form__notifications"></div>
                </form>
            </div>
        </div>
    </div>
</div>
