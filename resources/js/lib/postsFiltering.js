import { getTranslations } from './translations';
import { sidemenuToggler } from './sidemenuToggler';	
const T = getTranslations('opehuone-variables');

/**
 * Look for changes in form .front-page-posts-filter__posts-form
 * ==> so basically when any of the checkboxes change, output cornerlabels[] to console log
 */

const detectCheckboxChange = (form) => {

	if (!form) return;

	const toTarget = form.getAttribute('data-target');

	form.addEventListener('change', (event) => {
		if (
			event.target.classList.contains(
			'front-page-posts-filter__checkbox-input'
			)
		) {

		const checkedValues = Array.from(
			form.querySelectorAll('.front-page-posts-filter__checkbox-input:checked')
			).map((input) => input.value);
			
		// When no changes, return the initial state
		if (checkedValues.length === 0) {
			console.log('Ei valintoja, käytetään alkuperäistä valikkoa');
			return;
		}
					
		const currentPageId = document.querySelector('article.content')?.dataset.currentPageId;

		fetch(T.ajaxUrl, {
			method: 'POST',
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded',
				},
			body: new URLSearchParams({
			action: `update_front_page_${toTarget}`,
			cornerLabels: checkedValues,
			userId: T.userId,
			currentPageId: currentPageId, 
			}),
		})
		.then((response) => {
			if (!response.ok) {
				throw new Error(
				`HTTP error! Status: ${response.status}`
				);
			}
			return response.json();
		})
		.then((response) => {
			
			console.log('AJAX response:', response); // DEBUG

			const targetClass =
				toTarget === 'posts'
				? '.b-posts-row'
				: toTarget === 'training'
				? '.b-training-row'
				: '#sidebar-nav';

			const container = document.querySelector(targetClass);

			if (container) {
				container.innerHTML = response.data.output;
			}
			// Re-initializing toggler
			if (toTarget === 'pages') {
				sidemenuToggler();
			}
		})
		.catch((error) => console.error('AJAX Error:', error));
		}
	});
};

export const postsFiltering = () => {
	detectCheckboxChange(document.querySelector('#front-page-filter-posts'));
	detectCheckboxChange(document.querySelector('#front-page-filter-training'));
	detectCheckboxChange(document.querySelector('#front-page-filter-pages'));
};
