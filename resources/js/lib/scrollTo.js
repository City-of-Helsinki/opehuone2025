/* eslint-disable no-console,no-unused-vars */

const scrollTo = (selector) => {
	const mainNav = document.querySelector('.main-nav');
	const mainNavHeight = mainNav ? mainNav.offsetHeight : 0;
	const element =
		typeof selector === 'string'
			? document.querySelector(selector)
			: selector;

	if (element) {
		window.scrollTo({
			top: element.offsetTop - mainNavHeight,
			behavior: 'smooth',
		});
	}
};

const scrollingLink = () => {
	const contentLinks = document.querySelectorAll('.wp-block-post-content a');

	contentLinks.forEach((link) => {
		link.addEventListener('click', (e) => {
			const href = link.getAttribute('href');

			if (href && href.indexOf('#') !== -1) {
				e.preventDefault();
				// There is a hash
				const hash = '#' + href.split('#')[1];
				const targetElement = document.querySelector(hash);

				if (targetElement) {
					scrollTo(targetElement);
				}
			}
		});
	});
};

const detectHashInUrl = () => {
	const hash = window.location.hash;

	if (hash) {
		const targetElement = document.querySelector(hash);
		if (targetElement) {
			scrollTo(targetElement);
		}
	}
};

export const hashTagFunctions = () => {
	detectHashInUrl();
	scrollingLink();
};
