import { getTranslations } from './translations';

const T = getTranslations('opehuone-variables');
const loadMoreButton = document.querySelector('.posts-archive__load-more-btn');

/**
 * Convert array to comma separated string
 *
 * @param {Array} array - The array to be converted.
 * @returns {string} - Comma-separated string or an empty string if input is not an array.
 */
const convertArrayToString = (value) => {
	return Array.isArray(value) ? value.join(',') : (value?.toString() ?? '');
};

const setLoadMoreButtonOffSet = (currentOffSet) => {
	const totalPosts = parseInt(
		loadMoreButton.getAttribute('data-total-posts')
	);
	const newOffset = 15 + currentOffSet;

	loadMoreButton.setAttribute('data-posts-offset', newOffset);

	if (newOffset < totalPosts) {
		loadMoreButton.classList.remove('is-disabled');
	}
};

export const setLoadmoreButtonAttributes = (
	totalPosts,
	cornerLabel,
	category,
	postTheme
) => {
	loadMoreButton.classList.remove('is-disabled');
	loadMoreButton.setAttribute('data-total-posts', totalPosts);
	loadMoreButton.setAttribute('data-posts-offset', 15);
	loadMoreButton.setAttribute(
		'data-cornerlabel',
		convertArrayToString(cornerLabel)
	);
	loadMoreButton.setAttribute(
		'data-category',
		convertArrayToString(category)
	);
	loadMoreButton.setAttribute(
		'data-posttheme',
		convertArrayToString(postTheme)
	);

	if (totalPosts <= 15) {
		loadMoreButton.classList.add('is-disabled');
	}
};

export const loadMorePosts = (ajaxAction, container) => {
	loadMoreButton.addEventListener('click', (event) => {
		event.preventDefault();
		loadMoreButton.classList.add('is-disabled');

		const currentOffSet = parseInt(
			loadMoreButton.getAttribute('data-posts-offset')
		);
		const cornerLabel = loadMoreButton.getAttribute('data-cornerlabel');
		const category = loadMoreButton.getAttribute('data-category');
		const postTheme = loadMoreButton.getAttribute('data-posttheme');

		fetch(T.ajaxUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: new URLSearchParams({
				action: ajaxAction,
				cornerLabel: cornerLabel,
				category: category,
				postTheme: postTheme,
				userId: T.userId,
				offset: currentOffSet,
			}),
		})
			.then((response) => {
				if (!response.ok) {
					throw new Error(`HTTP error! Status: ${response.status}`);
				}
				return response.json();
			}) // Assuming the response is JSON
			.then((response) => {
				if (container) {
					container.insertAdjacentHTML(
						'beforeend',
						response.data.output
					);
				}
				// Set load more button properties
				setLoadMoreButtonOffSet(currentOffSet);
			})
			.catch((error) => console.error('AJAX Error:', error));
	});
};
