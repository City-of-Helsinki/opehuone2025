export const getTranslations = (elementId) => {
	const el = document.getElementById(elementId);

	if (!el) {
		throw Error('Cannot find ' + elementId);
	}

	return JSON.parse(el.innerHTML);
};
