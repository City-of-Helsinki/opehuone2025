/* eslint-disable no-console, no-unused-vars, no-undef */

import Sortable from 'sortablejs';

export const allToolsToggler = () => {
	jQuery('.all-tools-toggler').on('click', (e) => {
		e.preventDefault();

		// Open inactive tools
		const target = jQuery(e.currentTarget);
		const inactiveServices = jQuery('.tools-row-wrapper--inactive');
		target.toggleClass('all-tools-toggler--open');
		target.attr('aria-expanded', (i, attr) => {
			return attr === 'true' ? 'false' : 'true';
		});
		inactiveServices.slideToggle(500);

		// Show rest of the active tools
		const activeToolsRow = document.getElementById('front-active-tools');
		if (!activeToolsRow) {
			return;
		}
		const isExpanded = activeToolsRow.classList.contains(
			'tools-row--expanded'
		);

		if (isExpanded) {
			// Back to one row
			activeToolsRow.style.maxHeight = '127px';
			activeToolsRow.classList.remove('tools-row--expanded');
		} else {
			// Show rest of the hidden rows
			activeToolsRow.style.maxHeight = activeToolsRow.scrollHeight + 'px';
			activeToolsRow.classList.add('tools-row--expanded');
		}
	});
};

const toggler = document.querySelector('.edit-tools-toggler');
const modal = document.querySelector('#edit-tools-modal');
const closeBtn = modal?.querySelector('.close');

toggler?.addEventListener('click', () => {
	modal.classList.add('is-visible');
});

if (closeBtn) {
	closeBtn.addEventListener('click', () => {
		modal.classList.remove('is-visible');
		enableSubmitButton();
	});
}

modal?.addEventListener('click', (event) => {
	// If the click is directly on the backdrop (not inside the dialog)
	if (event.target === modal) {
		modal.classList.remove('is-visible');
		enableSubmitButton();
	}
});

export const saveOwnTools = () => {
	const form = jQuery('#edit-tools-form');
	const notifications = jQuery('.edit-tools-form__notifications');

	form.on('submit', function (event) {
		event.preventDefault();
		notifications.hide();

		const activeToolIds = jQuery('#active-tools [data-tool-id]')
			.map(function () {
				return jQuery(this).data('tool-id');
			})
			.get();

		form.addClass('form-loading');

		jQuery.ajax({
			url: opehuone_js.ajax_url,
			type: 'POST',
			data: {
				action: 'save_own_tools',
				activeToolIds: activeToolIds,
				nonce: opehuone_js.opehuone_nonce,
			},
			success: function (response) {
				notifications.show();
				notifications.text(opehuone_js.tools_saved_message);
				notifications.addClass('success');

				if (response.success && response.data) {
					jQuery('#active-tools').html(response.data.active_html);
					jQuery('#inactive-tools').html(response.data.inactive_html);
					jQuery('#front-active-tools').html(
						response.data.active_html
					);
					jQuery('#front-inactive-tools').html(
						response.data.inactive_html
					);
					// Reinitialize the toggler, form submit button and sortable after updating the HTML
					togglerDefaults();
					disableSubmitButton();
					initSortable();
				}

				setTimeout(() => {
					notifications.fadeOut();
					notifications.removeClass('success');
				}, 5000);
			},
			error: function () {
				notifications.show();
				notifications.text(opehuone_js.tools_save_error);
				notifications.addClass('error');
			},
			complete: function () {
				form.removeClass('form-loading');
			},
		});
	});
};
const submitButton = jQuery('#edit-tools-form button[type="submit"]');

// Check submit button state
const disableSubmitButton = () => {
	submitButton.prop('disabled', true);
	submitButton.addClass('is-disabled');
	submitButton.text('Muutokset tallennettu');
};

const enableSubmitButton = () => {
	if (submitButton.hasClass('is-disabled')) {
		submitButton.prop('disabled', false);
		submitButton.removeClass('is-disabled');
		submitButton.text('Tallenna muutokset');
	}
};

// Turn toggler functionality to default state after saving tools
const togglerDefaults = () => {
	const target = jQuery('.all-tools-toggler');
	target.removeClass('all-tools-toggler--open');
	target.attr('aria-expanded', 'false');
	jQuery('.tools-row-wrapper--inactive').slideUp(500);

	const activeToolsRow = document.getElementById('front-active-tools');
	activeToolsRow.style.maxHeight = '127px';

	const isExpanded = activeToolsRow.classList.contains('tools-row--expanded');

	if (isExpanded) {
		// Back to one row
		activeToolsRow.style.maxHeight = '127px';
		activeToolsRow.classList.remove('tools-row--expanded');
	}
};

let activeSortableInstance = null;
let inactiveSortableInstance = null;

const initSortable = () => {
	const activeSortableServices = document.getElementById('active-tools');
	const inactiveSortableServices = document.getElementById('inactive-tools');

	if (!activeSortableServices || !inactiveSortableServices) {
		return;
	}

	if (activeSortableInstance) activeSortableInstance.destroy();
	if (inactiveSortableInstance) inactiveSortableInstance.destroy();

	activeSortableInstance = new Sortable(activeSortableServices, {
		group: 'shared-tools',
		animation: 150,
		ghostClass: 'tool-item--ghost',
		sort: true,
	});
	inactiveSortableInstance = new Sortable(inactiveSortableServices, {
		group: 'shared-tools',
		animation: 150,
		ghostClass: 'tool-item--ghost',
		sort: false,
	});
};

initSortable();
