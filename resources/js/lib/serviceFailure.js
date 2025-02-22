/**
 * Function to toggle read more div visibility
 *
 * Looks for click event for button .service-failure__toggler
 * When clicked, look for parent .service-failure and toggle class .service-failure--read-more-open for that element
 *
 */
const toggleReadMore = () => {
	document.addEventListener('click', (event) => {
		const toggler = event.target.closest('.service-failure__toggler');

		if (!toggler) return;

		const serviceFailureElement = toggler.closest('.service-failure');
		if (serviceFailureElement) {
			serviceFailureElement.classList.toggle(
				'service-failure--read-more-open'
			);
		}
	});
};

export const serviceFailure = () => {
	toggleReadMore();
};
