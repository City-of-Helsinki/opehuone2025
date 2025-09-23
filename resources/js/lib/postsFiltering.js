import { getTranslations } from './translations';
import { sidemenuToggler } from './sidemenuToggler';

const T = getTranslations('opehuone-variables');

/**
 * Function wires up the form and runs once on load
 * @param form
 * @param filterOnFirstLoad
 */
const detectCheckboxChange = (form, filterOnFirstLoad) => {
	if (!form) return;

	// Handle changes after user input
	form.addEventListener('change', (event) => {
		handleCheckboxChange(form, event.target);
	});

	if (filterOnFirstLoad) {
		// Run once on page load if something is pre-checked
		const firstChecked = form.querySelector(
			'.front-page-posts-filter__checkbox-input:checked'
		);

		if (firstChecked) {
			handleCheckboxChange(form, firstChecked);
		}
	}
};

/**
 * Function contains the logic for form submit
 * @param form
 * @param target
 */
const handleCheckboxChange = (form, target) => {
	const toTarget = form.getAttribute('data-target');

	if (!target.classList.contains('front-page-posts-filter__checkbox-input')) {
		return;
	}

	const checkedValues = Array.from(
		form.querySelectorAll(
			'.front-page-posts-filter__checkbox-input:checked'
		)
	).map((input) => input.value);

	const currentPageId =
		document.querySelector('article.content')?.dataset.currentPageId;

	// When no checkboxes are selected, restore original sidebar
	if (checkedValues.length === 0) {
		console.log('Ei valintoja, palautetaan alkuperäinen valikko');

		fetch(window.location.href)
			.then((response) => response.text())
			.then((html) => {
				const parser = new DOMParser();
				const doc = parser.parseFromString(html, 'text/html');
				const originalAside = doc.querySelector('aside');
				const container = document.querySelector('aside');
				if (originalAside && container) {
					container.innerHTML = originalAside.innerHTML;
					sidemenuToggler();
				}
			})
			.catch((error) =>
				console.error(
					'Virhe alkuperäisen valikon palautuksessa:',
					error
				)
			);

		return;
	}

	// Otherwise fetch filtered results
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
				throw new Error(`HTTP error! Status: ${response.status}`);
			}
			return response.json();
		})
		.then((response) => {
			const targetSelector =
				toTarget === 'posts'
					? '.b-posts-row'
					: toTarget === 'training'
						? '.b-training-row'
						: 'aside';

			const container = document.querySelector(targetSelector);
			if (container) {
				container.innerHTML = response.data.output;
				if (toTarget === 'pages') {
					sidemenuToggler();
				}
			}
		})
		.catch((error) => console.error('AJAX Error:', error));
};

export const postsFiltering = () => {
	detectCheckboxChange(
		document.querySelector('#front-page-filter-posts'),
		true
	);
	detectCheckboxChange(
		document.querySelector('#front-page-filter-training'),
		false
	);
	detectCheckboxChange(
		document.querySelector('#front-page-filter-pages'),
		true
	);
};

document.addEventListener('DOMContentLoaded', () => {
	const checkboxes = document.querySelectorAll(
		'input[name="cornerlabels[]"]'
	);

	checkboxes.forEach((cb) => {
		cb.addEventListener('change', () => {
			const params = new URLSearchParams(window.location.search);

			// Get all checked values
			const selected = Array.from(checkboxes)
				.filter((c) => c.checked)
				.map((c) => c.value);

			if (selected.length > 0) {
				params.set('cornerlabels', selected.join(','));
			} else {
				params.delete('cornerlabels');
			}

			// Update URL without reload
			const newUrl = window.location.pathname + '?' + params.toString();
			window.history.replaceState({}, '', newUrl);
		});
	});
});
