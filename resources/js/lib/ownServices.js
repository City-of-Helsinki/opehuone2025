/* eslint-disable no-console,no-undef */
const nameInput = jQuery('#service-name-input');
const urlInput = jQuery('#service-url-input');
const newServiceWrapper = jQuery('.add-new-service-form__wrapper');
const newServiceOpenBtn = jQuery('.open-new-service-wrapper');

const clearInputs = () => {
	nameInput.val('');
	urlInput.val('');
};

const removeOwnService = () => {
	jQuery(document).on('click', '.dock-settings-list__remove', (e) => {
		e.preventDefault();
		const target = jQuery(e.currentTarget);
		const li = target.closest('.dock-settings-list__item');
		const ownId = li.attr('data-id');
		const ownIdentifier = li.attr('data-hash');

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
				li.fadeOut();
			}
		});
	});
}

const pinOwnService = () => {
	jQuery(document).on('click', '.dock-settings-list__star', (e) => {
		e.preventDefault();
		const target = jQuery(e.currentTarget);
    const li = target.closest('.dock-settings-list__item');
		const ownId = li.attr('data-id');
		let setVisible = li.attr('data-active');
		const ownIdentifier = li.attr('data-hash');

		if(setVisible === '0') {
		  setVisible = '1';
    } else {
		  setVisible = '0';
    }

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
			success: function () {
			  if(setVisible === '1') {
			    li.addClass('dock-settings-list__item--active');
			    li.attr('data-active', '1');
          target.attr('aria-label', 'Poista tämä palvelu suosikeista');
        } else {
          li.removeClass('dock-settings-list__item--active');
          li.attr('data-active', '0');
          target.attr('aria-label', 'Aseta tämä palvelu suosikiksi');
        }
			}
		});
	});
}

export const ownServices = () => {
	removeOwnService();
	pinOwnService();
}

export const addNewOwnService = () => {
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
      success: function () {
        notifications.show();
        form.removeClass('form-loading');
        notifications.text(opehuone_js.new_service_added);
        clearInputs();

        setTimeout(() => {
          notifications.fadeOut();
        },5000);
      }
    });
  });
};

export const handleAddNewServiceClick = () => {
  newServiceOpenBtn.on('click', (e) => {
    e.preventDefault();
    newServiceWrapper.slideToggle();
  });
};
