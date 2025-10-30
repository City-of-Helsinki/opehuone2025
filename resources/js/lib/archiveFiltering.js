import { getTranslations } from './translations';
import { loadMorePosts, setLoadmoreButtonAttributes } from './loadMoreHelpers';
import { createAjaxParameters, getUrlParameterAsArray } from './utils';

const T = getTranslations('opehuone-variables');

// Result containers for posts and training pages
const postsContainer = document.querySelector('#posts-archive-results');
const trainingContainer = document.querySelector('#training-archive-results');

const numberOfPostsSpan = document.querySelector('#archive-number-of-posts');

// Filters for posts and training pages
const postsPageFilters = ['cornerlabels[]', 'category[]', 'post_theme[]'];
const trainingPageFilters = ['cornerlabels[]', 'training_theme[]'];

// AJAX Actions for posts and training pages
const postsAction = 'update_post_archive_results';
const trainingAction = 'update_training_archive_results';

const getCheckedValues = (form, name) => {
	// need to escape the square brackets with double backslashes (\\) in the selector string
	const escapedName = name.replace(/([\[\]])/g, '\\$1');
	const checkboxes = form.querySelectorAll(
		`input[type="checkbox"][name="${escapedName}"]:checked`
	);
	return Array.from(checkboxes).map((input) => input.value);
};

const fetchArchiveResults = async (
	action,
	container,
	numberSpan,
	pageFilters = {}
) => {
	const bodyParams = createAjaxParameters(action, pageFilters);

	try {
		const response = await fetch(T.ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: bodyParams,
		});

		if (!response.ok) {
			throw new Error(`HTTP error! Status: ${response.status}`);
		}

		const data = await response.json();

		if (container) {
			container.innerHTML = data.data.output;
		}

		if (numberSpan) {
			numberSpan.innerHTML = data.data.totalPosts;
		}

		setLoadmoreButtonAttributes(data.data.totalPosts);
	} catch (err) {
		console.error('AJAX Error:', err);
	}
};

const toggleDropdown = () => {
	const filterButtons = document.querySelectorAll(
		'.checkbox-filter__filter-btn'
	);

	filterButtons.forEach((button) => {
		const originalLabel =
			button.getAttribute('aria-label') || 'Näytä valinnat'; // Fallback label

		button.addEventListener('click', () => {
			const isExpanded = button.getAttribute('aria-expanded') === 'true';

			// Toggle aria-expanded
			button.setAttribute('aria-expanded', (!isExpanded).toString());

			// Toggle aria-label
			button.setAttribute(
				'aria-label',
				isExpanded ? originalLabel : 'Piilota valinnat'
			);
		});

		// Ensure that pressing "Enter" also toggles the dropdown
		button.addEventListener('keydown', (event) => {
			if (event.key === 'Enter' || event.key === ' ') {
				event.preventDefault(); // Prevent scrolling or default button behavior
				button.click(); // Simulate the click to toggle dropdown
			}
		});
	});

	// Enable checkboxes to toggle with Enter and Space keys
	const checkboxes = document.querySelectorAll(
		'.checkbox-filter__checkbox-input'
	);

	checkboxes.forEach((checkbox) => {
		checkbox.addEventListener('keydown', (event) => {
			if (event.key === 'Enter' || event.key === ' ') {
				event.preventDefault(); // Prevent triggering other elements like buttons
				checkbox.checked = !checkbox.checked; // Toggle checkbox state manually
				checkbox.dispatchEvent(new Event('change')); // Trigger change event if needed
			}
		});
	});

	// If we click outside the dropdown box, we close it
	document.addEventListener('click', (e) => {
		filterButtons.forEach((button) => {
			const wrapper = button.closest(
				'.posts-archive__select-filter-wrapper'
			);

			const originalLabel =
				button.getAttribute('aria-label') || 'Näytä valinnat';

			if (!wrapper.contains(e.target)) {
				button.setAttribute('aria-expanded', 'false');
				button.setAttribute('aria-label', originalLabel);
			}
		});
	});
};

/**
 * Helper function that updates URL parameters whenever a checkbox has been checked/unchecked
 * @param form
 * @param filters
 */
const updateUrlParams = (form, filters) => {
	const params = new URLSearchParams(window.location.search);

	filters.forEach((filter) => {
		const values = getCheckedValues(form, filter);
		params.delete(filter);
		if (values.length > 0) params.set(filter, values.join(','));
	});

	window.history.replaceState(
		{},
		'',
		window.location.pathname + '?' + params.toString()
	);
};

/**
 * Logic for reset button
 * Uncheck all checkboxes and update URL parameters and dropdown button text when clicked
 * @param form
 */
const initializeResetButtons = (form, pageFilters) => {
	const resetButtons = document.querySelectorAll(
		'.checkbox-filter__checkboxes-reset-btn'
	);

	resetButtons.forEach((button) => {
		button.addEventListener('click', () => {
			// Find the closest filter wrapper to scope the reset action
			const filterWrapper = button.closest(
				'.posts-archive__select-filter-wrapper'
			);

			if (!filterWrapper) {
				return;
			}

			// Select all checked checkboxes within this filter group and uncheck them
			const checkboxes = filterWrapper.querySelectorAll(
				'.checkbox-filter__checkbox-input:checked'
			);

			checkboxes.forEach((checkbox) => {
				checkbox.checked = false;
			});

			updateUrlParams(form, pageFilters);
			updateDropdownButtonText(filterWrapper);
		});
	});
};

/**
 * Check the checkboxes on first page load based on the URL parameters
 * @param form
 * @param filters
 */
const checkCheckboxesFromUrl = (form, filters) => {
	filters.forEach((filter) => {
		const values = getUrlParameterAsArray(filter); // Get array of values from URL

		if (!values.length) return;

		// Select all checkboxes for this filter
		const checkboxes = form.querySelectorAll(`input[name="${filter}"]`);

		checkboxes.forEach((checkbox) => {
			if (values.includes(checkbox.value)) {
				checkbox.checked = true;
			}
		});
	});
};

/**
 * Whenever we update checkbox state we want to update the button text to show what we have selected
 *
 * @param wrapper
 */
const updateDropdownButtonText = (wrapper) => {
	const button = wrapper.querySelector('.checkbox-filter__filter-btn');
	const checkboxes = wrapper.querySelectorAll(
		'.checkbox-filter__checkbox-input:checked'
	);
	const originalLabel = button.dataset.originalLabel || button.textContent;

	if (checkboxes.length === 0) {
		button.textContent = originalLabel; // no selection
		return;
	}

	const checkedLabels = Array.from(checkboxes).map((cb) => {
		return cb.closest('label')?.textContent.trim() || cb.value;
	});

	if (checkedLabels.length === 1) {
		button.textContent = checkedLabels[0]; // only one selected
	} else {
		button.textContent = `${checkedLabels[0]} + ${checkedLabels.length - 1}`; // two or more, show +N
	}
};

// Initialize filtering logic for either posts or training page
export const archiveFiltering = () => {
	const postsArchiveForm = document.querySelector('.posts-archive-filtering');

	// If we are on posts archive page, initialize it
	if (postsArchiveForm) {
		initArchiveFiltering(
			postsArchiveForm,
			postsPageFilters,
			postsAction,
			postsContainer
		);
		return;
	}

	const trainingArchiveForm = document.querySelector(
		'.training-archive-filtering'
	);

	// If we are on training archive page, initialize it
	if (trainingArchiveForm) {
		initArchiveFiltering(
			trainingArchiveForm,
			trainingPageFilters,
			trainingAction,
			trainingContainer
		);

		// Scroll into view if we navigate from a redirect
		if (window.location.search.includes('training_theme')) {
			trainingArchiveForm.scrollIntoView({
				behavior: 'smooth',
				block: 'start',
			});
		}
	}
};

/**
 * Initialize page form and filter logic
 */
const initArchiveFiltering = (form, pageFilters, action, container) => {
	// Pre-select checkboxes from the URL when navigating to the page for the first time
	checkCheckboxesFromUrl(form, pageFilters);

	// Update button text for all filters initially
	pageFilters.forEach((filter) => {
		const wrapper = form
			.querySelector(`input[name="${filter}"]`)
			?.closest('.posts-archive__select-filter-wrapper');

		if (!wrapper) {
			return;
		}

		updateDropdownButtonText(wrapper);
	});

	// Filter results when we click the submit button
	form.querySelector('#archive-submit-button')?.addEventListener(
		'click',
		async (event) => {
			event.preventDefault();
			await fetchArchiveResults(
				action,
				container,
				numberOfPostsSpan,
				pageFilters
			);
		}
	);

	// Handle the URL parameters when checkboxes are clicked
	form.querySelectorAll('.checkbox-filter__checkbox-input').forEach(
		(checkbox) => {
			const wrapper = checkbox.closest(
				'.posts-archive__select-filter-wrapper'
			);

			checkbox.addEventListener('change', () => {
				updateUrlParams(form, pageFilters);
				updateDropdownButtonText(wrapper);
			});
		}
	);

	// Handle filter dropdown logic
	toggleDropdown();

	// Handle filter reset buttons
	initializeResetButtons(form, pageFilters);

	// Handle Load more posts
	loadMorePosts(action, pageFilters, container);
};
