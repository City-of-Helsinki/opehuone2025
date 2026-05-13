const articlesList = jQuery('.wikipedia-opener');

const accordionToggler = () => {
	const button = jQuery('.actions-wrapper__list-item--wikipedia');

	button.on('click', (e) => {
		e.preventDefault();
		button.toggleClass('open'); // Toggle chevron icon styles
		articlesList.toggle();
	});
};

export const breakCornerBoxFunctions = () => {
	accordionToggler();
};
