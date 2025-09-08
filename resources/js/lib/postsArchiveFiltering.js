import { getTranslations } from './translations';
import { setLoadmoreButtonAttributes, loadMorePosts } from './loadMoreHelpers';

const T = getTranslations('opehuone-variables');

const numberOfPostsSpan = document.querySelector('#posts-archive-number-of-posts');
const postsContainer = document.querySelector('#posts-archive-results');

const doFiltering = (event) => {
	if (event) {
		event.preventDefault();
	}

	const cornerLabel = document.querySelector('#posts-archive-cornerlabels')?.value ?? '';
	const category = document.querySelector('#posts-archive-category')?.value ?? '';
	const postTheme = document.querySelector('#posts-archive-post_theme')?.value ?? '';

	fetch(T.ajaxUrl, {
		method: 'POST',
		headers: {
			'Content-Type': 'application/x-www-form-urlencoded',
		},
		body: new URLSearchParams({
			action: 'update_post_archive_results',
			cornerLabel: cornerLabel,
			category: category,
			postTheme: postTheme,
			userId: T.userId,
		}),
	})
		.then((response) => {
			if (!response.ok) {
				throw new Error(`HTTP error! Status: ${response.status}`);
			}
			return response.json();
		})
		.then((response) => {
			if (postsContainer) {
				postsContainer.innerHTML = response.data.output;
			}
			if (numberOfPostsSpan) {
				numberOfPostsSpan.innerHTML = response.data.totalPosts;
			}

            // Update URL parameters
			const url = new URL(window.location);
			url.searchParams.set('filter_cornerlabels', cornerLabel);
			url.searchParams.set('filter_category', category);
			url.searchParams.set('filter_post_theme', postTheme);
			window.history.replaceState({}, document.title, url);

            // Update load more button attributes
			setLoadmoreButtonAttributes(response.data.totalPosts, cornerLabel, category, postTheme);
		})
		.catch((error) => console.error('AJAX Error:', error));
};

const applyUrlParamsToForm = (form) => {
	const urlParams = new URLSearchParams(window.location.search);
	const map = {
		filter_cornerlabels: '#posts-archive-cornerlabels',
		filter_category: '#posts-archive-category',
		filter_post_theme: '#posts-archive-post_theme',
	};
	Object.entries(map).forEach(([param, selector]) => {
		const el = form.querySelector(selector);
		const val = urlParams.get(param);
		
        if (el && val !== null) {
			el.value = val;
		}
	});
};

const triggerFormUpdateOnPageLoad = (form) => {
	const urlParams = new URLSearchParams(window.location.search);
	const filterKeys = ['filter_cornerlabels', 'filter_category', 'filter_post_theme'];
	const shouldTrigger = filterKeys.some((key) => urlParams.has(key) && urlParams.get(key) !== '');

	if (!shouldTrigger) {
		return;
	}

	applyUrlParamsToForm(form);
	doFiltering();
    form.scrollIntoView({ behavior: 'smooth' });
};

export const postsArchiveFiltering = () => {
	const form = document.querySelector('.posts-archive-filtering');
	
    if (!form) {
		return;
	}

	form.addEventListener('submit', doFiltering);
	triggerFormUpdateOnPageLoad(form);

	// Load more
	loadMorePosts('load_more_posts_archive_results', postsContainer);
};
