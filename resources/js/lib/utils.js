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
