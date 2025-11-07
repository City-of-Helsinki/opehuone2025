<?php
use function Opehuone\Helpers\the_svg;
?>

<div class="modal fade" id="add-new-service-modal" tabindex="-1" role="dialog" aria-labelledby="add-new-service-modal"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"
                        aria-label="<?php esc_html_e( 'Sulje oman palvelun lisääminen' ); ?>">
                    <span aria-hidden="true"><?php the_svg( 'icons/close-icon' ); ?></span>
                </button>
            </div>
            <div class="modal-body">
                <form id="add-new-service-form">
                    <h2 class="add-new-service-form__title">
                        <?php esc_html_e( 'Lisää oma palvelu' ); ?>
                    </h2>
                    <div class="add-new-service-form__field-group">
                        <label for="service-name-input" class="add-new-service-form__label">
                            <?php esc_html_e( 'Palvelun nimi' ); ?>
                        </label>
                        <input type="text" id="service-name-input" class="add-new-service-form__form-field">
                    </div>
                    <div class="add-new-service-form__field-group">
                        <label for="service-url-input" class="add-new-service-form__label">
                            <?php esc_html_e( 'Palvelun osoite' ); ?>
                        </label>
                        <input type="url" id="service-url-input" class="add-new-service-form__form-field"
                               aria-describedby="urlHelp">
                        <small id="urlHelp" class="form-text text-muted">
                            <?php esc_html_e( 'Aloitathan osoitteen https://...' ); ?>
                        </small>
                    </div>
                    <button type="submit" class="add-new-service-form__btn add-new-service-form__btn--submit">
                        <?php esc_html_e( 'Tallenna' ); ?>
                    </button>
                    <button type="button" class="add-new-service-form__btn add-new-service-form__btn--cancel" data-dismiss="modal">
                        <?php esc_html_e( 'Peruuta' ); ?>
                    </button>
                    <div class="add-new-service-form__notifications"></div>
                </form>
            </div>
        </div>
    </div>
</div>
