export const toggleAria = (el, ariaLabel) => {
	const currentAttr = el.getAttribute(ariaLabel);
	el.setAttribute(ariaLabel, currentAttr === 'true' ? 'false' : 'true');
};

export const toggleTabIndex = (el) => {
	const currentAttr = el.getAttribute('tabindex');
	el.setAttribute('tabindex', currentAttr === '-1' ? '0' : '-1');
};
