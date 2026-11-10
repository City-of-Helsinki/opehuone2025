/**
 * Function to toggle read more div visibility
 *
 * Looks for click event for button .service-notice__toggler
 * When clicked, look for parent .service-notice and toggle class .service-notice--read-more-open for that element
 *
 */
const toggleReadMore = () => {
	document.addEventListener('click', (event) => {
		const toggler = event.target.closest('.service-notice__toggler');

		if (!toggler) return;

		const serviceNoticeElement = toggler.closest('.service-notice');
		if (serviceNoticeElement) {
			serviceNoticeElement.classList.toggle(
				'service-notice--read-more-open'
			);
			const togglerText = toggler.querySelector(
				'.service-notice__toggler-text'
			);
			if (togglerText) {
				togglerText.textContent =
					serviceNoticeElement.classList.contains(
						'service-notice--read-more-open'
					)
						? 'Sulje'
						: 'Lue lisää';
			}
		}
	});
};

const closeServiceNotice = () => {
	const getKey = (notice) =>
		'opehuone_service_notice_' + notice.dataset.noticeId;

	// Remove empty notices wrapper if there are no more service notices inside it
	const removeEmptyWrapper = () => {
		const wrapper = document.querySelector('.service-notices');
		if (wrapper && !wrapper.querySelector('.service-notice')) {
			console.log('Removing empty service notices wrapper');
			wrapper.remove();
		}
	};

	// On page load: remove already closed notices
	document.querySelectorAll('.service-notice').forEach((notice) => {
		if (localStorage.getItem(getKey(notice)) === 'closed') {
			notice.remove();
		}
	});

	removeEmptyWrapper();

	// On close button click: save and remove the notice
	document.addEventListener('click', (event) => {
		const closeButton = event.target.closest('.service-notice__close');
		if (!closeButton) return;

		const notice = closeButton.closest('.service-notice');
		localStorage.setItem(getKey(notice), 'closed');
		notice.remove();
		removeEmptyWrapper();
	});
};

export const serviceNotice = () => {
	toggleReadMore();
	closeServiceNotice();
};
