const profileOpenerButton = document.querySelector('.profile-opener');
const profileOpenerDropdown = document.querySelector(
	'.profile-opener-dropdown'
);
/**
 * Function to handle toggling the dropdown, when pressin profileOpenerButton
 * When button is clicked, look for parent class .profile-opener-wrapper--dropdown-open and toggle class .profile-opener-wrapper--dropdown-open for it
 *
 * Also change button aria-expanded to true, when dropdown is open, false when closed
 *
 * Also change aria-label for button to "Sulje profiilivalinnat", when aria-expanded is true
 */
export const profileOpener = () => {
	if (!profileOpenerButton || !profileOpenerDropdown) return;

	profileOpenerButton.addEventListener('click', () => {
		const wrapper = profileOpenerButton.closest('.profile-opener-wrapper');

		if (wrapper) {
			const isOpen = wrapper.classList.toggle(
				'profile-opener-wrapper--dropdown-open'
			);
			profileOpenerButton.setAttribute('aria-expanded', isOpen);
			profileOpenerButton.setAttribute(
				'aria-label',
				isOpen ? 'Sulje profiilivalinnat' : 'Avaa profiilivalinnat'
			);
		}
	});
};
