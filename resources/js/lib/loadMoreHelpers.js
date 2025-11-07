import { getTranslations } from './translations';
import { createAjaxParameters } from './utils';

const T = getTranslations('opehuone-variables');
const loadMoreButton = document.querySelector('.posts-archive__load-more-btn');

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

export const setLoadmoreButtonAttributes = (totalPosts) => {
	loadMoreButton.classList.remove('is-disabled');
	loadMoreButton.setAttribute('data-total-posts', totalPosts);
	loadMoreButton.setAttribute('data-posts-offset', 15);
	if (totalPosts <= 15) {
		loadMoreButton.classList.add('is-disabled');
	}
};

export const loadMorePosts = (action, pageFilters, container) => {
	loadMoreButton.addEventListener('click', (event) => {
		event.preventDefault();
		loadMoreButton.classList.add('is-disabled');

		const currentOffSet = parseInt(
			loadMoreButton.getAttribute('data-posts-offset')
		);

		const params = createAjaxParameters(action, pageFilters);
		params.append('userId', T.userId);
		params.append('offset', currentOffSet);

		fetch(T.ajaxUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
			},
			body: params,
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
