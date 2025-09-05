/* eslint-disable no-console,no-undef */
import { updateInactiveTogglersVisibility } from './services';

const activeRow = jQuery('.services-row--active');
const inactiveRow = jQuery('.services-row--inactive');
const nameInput = jQuery('#service-name-input');
const urlInput = jQuery('#service-url-input');

const setServicesRow = (setVisible, content) => {
    if(setVisible === 1) {
        activeRow.html(content);
    } else {
        inactiveRow.html(content);
    }
};

const clearInputs = () => {
    nameInput.val('');
    urlInput.val('');
};

const addNewOwnService = () => {
    const form = jQuery("#add-new-service-form");
    const notifications = jQuery('.add-new-service-form__notifications');
    form.submit(function (event) {
        event.preventDefault();
        notifications.hide();
        const serviceName = nameInput.val();
        const serviceUrl = urlInput.val();

        if (!serviceName || !serviceUrl) {
            notifications.show();
            notifications.text(opehuone_js.add_new_form_errors);
            return;
        }

        form.addClass('form-loading');

        jQuery.ajax({
            url: opehuone_js.ajax_url,
            type: 'POST',
            data: ({
                action: 'add_new_own_service',
                service_details: {
                    serviceName: serviceName,
                    serviceUrl: serviceUrl
                },
                user_id: opehuone_js.user_id,
                nonce: opehuone_js.opehuone_nonce
            }),
            success: function (content) {
                notifications.show();
                form.removeClass('form-loading');
                notifications.text(opehuone_js.new_service_added);
                notifications.addClass('success');
                inactiveRow.html(content);
                clearInputs();

                setTimeout(() => {
                    notifications.fadeOut();
                    notifications.removeClass('success');
                },5000);

            }
        });
    });
};

const removeOwnService = () => {
    jQuery(document).on('click', '.services-item-dropdown__link--remove-own', (e) => {
        e.preventDefault();
        const target = jQuery(e.currentTarget);
        const ownId = target.attr('data-own-service-id');
        const ownIdentifier = target.attr('data-own-service-identifier');
        const closestColumn = target.closest('.services-column');

        jQuery.ajax({
            url: opehuone_js.ajax_url,
            type: 'POST',
            data: ({
                action: 'remove_own_service',
                serviceId: ownId,
                serviceIdentifier: ownIdentifier,
                userId: opehuone_js.user_id,
                nonce: opehuone_js.opehuone_nonce
            }),
            success: function (content) {
                alert(content);
                closestColumn.fadeOut('fast', function() {
                    updateInactiveTogglersVisibility();
                });
            }
        });
    });
}

const pinOwnService = () => {
    jQuery(document).on('click', '.services-item-dropdown__link--pin-own', (e) => {
        e.preventDefault();

        const target = jQuery(e.currentTarget);
        const ownId = target.attr('data-own-service-id');
        const setVisible = target.attr('data-own-service-set-visible');
        const ownIdentifier = target.attr('data-own-service-identifier');
        const closestColumn = target.closest('.services-column');

        jQuery.ajax({
            url: opehuone_js.ajax_url,
            type: 'POST',
            data: ({
                action: 'pin_own_service',
                serviceId: ownId,
                serviceIdentifier: ownIdentifier,
                setVisible: setVisible,
                userId: opehuone_js.user_id,
                nonce: opehuone_js.opehuone_nonce
            }),
            success: function (content) {
                setServicesRow(parseInt(setVisible), content);
                closestColumn.fadeOut();
                updateInactiveTogglersVisibility();
            }
        });
    });
}

const toggler = document.querySelector('.add-new-service-toggler');
const modal = document.querySelector('#add-new-service-modal');
const closeBtn = modal?.querySelector('.close');
const cancelBtn = modal?.querySelector('.add-new-service-form__btn--cancel');

toggler?.addEventListener('click', () => {
    modal.classList.add('is-visible');
});

if (closeBtn && cancelBtn) {
    [closeBtn, cancelBtn].forEach(btn =>
        btn.addEventListener('click', () => {
            modal.classList.remove('is-visible');
        })
    );
}

modal?.addEventListener('click', (event) => {
    // If the click is directly on the backdrop (not inside the dialog)
    if (event.target === modal) {
        modal.classList.remove('is-visible');
    }
});


export const ownServices = () => {
    addNewOwnService();
    removeOwnService();
    pinOwnService();
}
