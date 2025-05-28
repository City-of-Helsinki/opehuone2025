import { setAriaExpanded } from './toggleStates';

/**
 * Helper to set sub-menu-opened class to all ancestors
 */
const setCurrentsOpen = () => {
	const ancestors = document.querySelectorAll(
		'#sidebar-nav .sidemenu-current-page-ancestor, #sidebar-nav .sidemenu-current-page'
	);

	ancestors.forEach((el) => {
		el.classList.add('sidemenu-page-item--opened');
		const button = el.querySelector(
			'button[data-page-nav-toggle="sub-menu"]'
		);
		if (button) {
			setAriaExpanded(button);
		}
	});
};

export const sidemenuToggler = () => {
	setCurrentsOpen();

	const buttons = document.querySelectorAll('.sidemenu-toggle');

	buttons.forEach((button) => {
		button.addEventListener('click', (e) => {
			e.preventDefault();
			const target = e.currentTarget;
			const closestLi = target.closest('li.sidemenu-page-item');
			if (closestLi) {
				closestLi.classList.toggle('sidemenu-page-item--opened');
			}
			setAriaExpanded(target);
		});
	});
};
