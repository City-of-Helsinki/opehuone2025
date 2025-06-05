import { getTranslations } from './translations';
const T = getTranslations('opehuone-variables');

const numberOfPostsSpan = document.querySelector(
	'#training-archive-number-of-posts'
);

const doFiltering = (event) => {
	if (event) {
		event.preventDefault(); // Prevent the default form submission
	}

	const cornerLabels = document.querySelector(
		'#training-archive-cornerlabels'
	).value;
	const trainingTheme = document.querySelector(
		'#training-archive-training_theme'
	).value;

	fetch(T.ajaxUrl, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: new URLSearchParams({
			action: `update_training_archive_results`,
			cornerLabel: cornerLabels,
			trainingTheme: trainingTheme,
		}),
	})
		.then((response) => {
			if (!response.ok) {
				throw new Error(`HTTP error! Status: ${response.status}`);
			}
			return response.json();
		}) // Assuming the response is JSON
		.then((response) => {
			const postsContainer = document.querySelector(
				'#training-archive-results'
			);
			if (postsContainer) {
				postsContainer.innerHTML = response.data.output;
			}
			if (numberOfPostsSpan) {
			numberOfPostsSpan.innerHTML = response.data.totalPosts;
			}
		})
		.catch((error) => console.error('AJAX Error:', error));
};

/**
 * This function fetches the query parameters from URL and checks if it matches
 * from the filter key array. If they match, filter the results and scroll into view.
 * @param form
 */
const triggerFormUpdateOnPageLoad = (form) => {
	const urlParams = new URLSearchParams(window.location.search);
	let shouldTrigger = false;

	// Define which query keys you're using (matching your PHP form logic)
	const filterKeys = ['filter_cornerlabels', 'filter_training_theme'];

	filterKeys.forEach((key) => {
		if (urlParams.has(key) && urlParams.get(key) !== '') {
			shouldTrigger = true;
		}
	});

	if (!shouldTrigger) {
		return;
	}

	doFiltering();

	form.scrollIntoView({
		behavior: 'smooth',
	});

	// Clean up the query string in the browser address bar
	const url = new URL(window.location);
	url.search = ''; // Remove all query params
	window.history.replaceState({}, document.title, url);
};

export const trainingFiltering = () => {
	const form = document.querySelector('.training-archive-filtering');
	if (!form) {
		return;
	}

	// Add event listener for the form submit button
	form.addEventListener('submit', doFiltering);

	// If a query parameter is found (from a redirect), filter the results and scroll into view
	triggerFormUpdateOnPageLoad(form);
};
