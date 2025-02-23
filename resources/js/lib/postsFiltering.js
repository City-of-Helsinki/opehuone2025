import { getTranslations } from './translations';

const T = getTranslations('opehuone-variables');

/**
 * Look for changes in form .front-page-posts-filter__posts-form
 * ==> so basically when any of the checkboxes change, output cornerlabels[] to console log
 */
const detectCheckboxChange = () => {
	const form = document.querySelector('.front-page-posts-filter__posts-form');
	if (!form) return;

	form.addEventListener('change', (event) => {
		if (
			event.target.classList.contains(
				'front-page-posts-filter__checkbox-input'
			)
		) {
			const checkedValues = Array.from(
				form.querySelectorAll(
					'.front-page-posts-filter__checkbox-input:checked'
				)
			).map((input) => input.value);

			fetch(T.ajaxUrl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: new URLSearchParams({
					action: 'update_front_page_posts',
					cornerLabels: checkedValues,
					userId: T.userId,
				}),
			})
				.then((response) => {
					if (!response.ok) {
						throw new Error(
							`HTTP error! Status: ${response.status}`
						);
					}
					return response.json();
				}) // Assuming the response is JSON
				.then((response) => {
					const postsContainer =
						document.querySelector('.b-posts-row');
					if (postsContainer) {
						postsContainer.innerHTML = response.data.output;
					}
				})
				.catch((error) => console.error('AJAX Error:', error));
		}
	});
};

export const postsFiltering = () => {
	detectCheckboxChange();
};
