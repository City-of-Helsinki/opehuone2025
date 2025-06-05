<?php 
require_once get_stylesheet_directory() . '/library/classes/Utils.php';
use LuuptekWP\Utils;
use Opehuone\Helpers;

$utils = new Utils();
?>

<div class="add-new-service-form__wrapper">
    <form id="add-new-service-form">
        <h2 class="add-new-service-form__title">
            <?php _e( 'Lisää oma palvelu', TEXT_DOMAIN ); ?>
        </h2>
        <div class="add-new-service-form__field-group">
            <label for="service-name-input" class="add-new-service-form__label">
                <?php _e( 'Palvelun nimi', TEXT_DOMAIN ); ?>
            </label>
            <input type="text" id="service-name-input" class="add-new-service-form__form-field">
        </div>
        <div class="add-new-service-form__field-group">
            <label for="service-url-input" class="add-new-service-form__label">
                <?php _e( 'Palvelun osoite', TEXT_DOMAIN ); ?>
            </label>
            <input type="url" id="service-url-input" class="add-new-service-form__form-field"
                   aria-describedby="urlHelp">
            <small id="urlHelp" class="form-text text-muted">
                <?php _e( 'Aloitathan osoitteen https:// tai http://...', TEXT_DOMAIN ); ?>
            </small>
        </div>
        <button type="submit" class="add-new-service-form__btn add-new-service-form__btn--submit">
            <?php _e( 'Tallenna', TEXT_DOMAIN ); ?>
            <?php //Helpers\the_svg( 'loop-icon' ); ?>
        </button>
        <div class="add-new-service-form__notifications"></div>
    </form>
</div>
