/* eslint-disable no-console, no-unused-vars, no-undef */

export const updateButtonClicks = () => {
	const linkItem = jQuery('.services-column__link');

	linkItem.on('click', (e) => {
		const element = jQuery(e.currentTarget);
		const PostId = element.attr('data-post-id');

		if(!PostId) {
			return;
		}

		jQuery.ajax({
			url: opehuone_js.ajax_url,
			type: 'POST',
			data: ({
				action: 'update_service_open_count',
				postId: PostId,
				metaKey: '_service_clicks',
			}),
			success: function () {

			}
		});
	});
};

export const allServicesToggler = () => {
	jQuery('.all-services-toggler').on('click', (e) => {
		e.preventDefault();
		const target = jQuery(e.currentTarget);
		const inactiveServices = jQuery('.services-row--inactive');
		target.toggleClass('all-services-toggler--open');
		target.attr('aria-expanded', (i, attr) => {
			return attr === 'true' ? 'false' : 'true'
		});
		inactiveServices.slideToggle(800);
	});
};

export const addToServices = () => {
	jQuery(document).on('click', '.services-item-dropdown__link--add', (e) => {
		e.preventDefault();
		const target = jQuery(e.currentTarget);
		target.addClass('disabled');
		const serviceId = target.attr('data-item-id');
		const activeServices = jQuery('.services-row--active');
		const closestColumn = target.closest('.services-column');

		jQuery.ajax({
			url: opehuone_js.ajax_url,
			type: 'POST',
			data: ({
				action: 'add_service_to_favorites',
				service_id: serviceId,
				user_id: opehuone_js.user_id
			}),
			success: function (content) {
				closestColumn.fadeOut();
				activeServices.html(content);
				updateInactiveTogglersVisibility();
			}
		});
	});
}

export const removeFromServices = () => {
	const inactiveServices = jQuery('.services-row--inactive');
	inactiveServices.hide();

	jQuery(document).on('click', '.services-item-dropdown__link--remove', (e) => {
		e.preventDefault();
		const target = jQuery(e.currentTarget);
		target.addClass('disabled');
		const serviceId = target.attr('data-item-id');
		const closestColumn = target.closest('.services-column');

		jQuery.ajax({
			url: opehuone_js.ajax_url,
			type: 'POST',
			data: ({
				action: 'remove_service_from_favorites',
				service_id: serviceId,
				user_id: opehuone_js.user_id
			}),
			success: function (content) {
				closestColumn.fadeOut();
				inactiveServices.html(content);
			}
		});
		updateInactiveTogglersVisibility();

	});
}

export const servicesToggler = () => {
// Toggle the pin dropdown, hide any others if there are any already opened
	jQuery(document).on('click', '.services-column__toggler', (e) => {
		e.preventDefault();
		const $clickedToggler = jQuery(e.currentTarget);

		jQuery('.services-column__toggler').each(function() {
			const $toggler = jQuery(this);
			const $dropdown = $toggler.next('.services-item-dropdown');

			if ($toggler.is($clickedToggler)) {
				// Toggle the clicked one
				const isOpen = $dropdown.hasClass('services-item-dropdown--open');
				$dropdown.toggleClass('services-item-dropdown--open', !isOpen);
				$toggler.attr('aria-expanded', String(!isOpen));

				if (!isOpen) {
					// Reset positioning
					$dropdown.css({ left: '', right: '' });

					// Check if dropdown overflows viewport
					const rect = $dropdown[0].getBoundingClientRect();
					if (rect.left < 0) {
						// Dropdown would overflow on left, flip it
						$dropdown.css({ left: '0', right: 'auto' });
					} else if (rect.right > window.innerWidth) {
						// Dropdown would overflow on right, flip it
						$dropdown.css({ right: '0', left: 'auto' });
					}
				} else {
					// Reset styles when closing
					$dropdown.css({ left: '', right: '' });
				}
			} else {
				// Close all others
				$dropdown.removeClass('services-item-dropdown--open');
				$toggler.attr('aria-expanded', 'false');
				$dropdown.css({ left: '', right: '' });
			}
		});
	});

	// Click outside to close the dropdown
	jQuery(document).on('click', function(e) {
		if (
			!jQuery(e.target).closest('.services-column__toggler').length &&
			!jQuery(e.target).closest('.services-item-dropdown').length
		) {
			jQuery('.services-item-dropdown').removeClass('services-item-dropdown--open');
			jQuery('.services-column__toggler').attr('aria-expanded', 'false');
			jQuery('.services-item-dropdown').css({ left: '', right: '' });
		}
	});

	updateInactiveTogglersVisibility();
}

export function updateInactiveTogglersVisibility() {
	// how many services are currently active
	const activeCount = jQuery('.services-row--active .services-column:visible').length;
	// all togglers inside the inactive row
	const $inactiveTogglers = jQuery('.services-row--inactive .services-column__toggler');

	if (activeCount >= 10) {
		// hide/disable add buttons in inactive section
		$inactiveTogglers
			.prop('disabled', true)
			.attr('aria-hidden', 'true')
			.css('display', 'none');
	} else {
		// show/enable them again
		$inactiveTogglers
			.prop('disabled', false)
			.removeAttr('aria-hidden')
			.css('display', '');
	}
}