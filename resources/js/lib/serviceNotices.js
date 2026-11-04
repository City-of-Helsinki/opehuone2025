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

export const serviceNotice = () => {
	toggleReadMore();
};
