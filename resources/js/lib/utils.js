export const fadeIn = (element, duration = 400) => {
	element.style.display = 'block';
	element.style.opacity = 0;

	const startTime = performance.now();

	const animate = (time) => {
		const elapsedTime = time - startTime;
		const progress = Math.min(elapsedTime / duration, 1);
		element.style.opacity = progress;

		if (progress < 1) {
			requestAnimationFrame(animate);
		}
	};

	requestAnimationFrame(animate);
};

export const fadeOut = (element, duration = 400) => {
	const startTime = performance.now();

	const animate = (time) => {
		const elapsedTime = time - startTime;
		const progress = Math.min(elapsedTime / duration, 1);
		element.style.opacity = 1 - progress;

		if (progress < 1) {
			requestAnimationFrame(animate);
		} else {
			element.style.display = 'none';
		}
	};

	requestAnimationFrame(animate);
};

/**
 * Helper function, return url parameters as an array
 * @param param
 * @returns {string[]|*[]}
 */
export const getUrlParameterAsArray = (param) => {
	const params = new URLSearchParams(window.location.search);
	const value = params.get(param);
	return value ? value.split(',') : [];
};

export const createAjaxParameters = (action, pageFilters) => {
	const params = new URLSearchParams();

	params.append('action', action);

	pageFilters.forEach((filter) => {
		const urlValues = getUrlParameterAsArray(filter);
		urlValues.forEach((value) => params.append(filter, value));
	});

	return params;
};
