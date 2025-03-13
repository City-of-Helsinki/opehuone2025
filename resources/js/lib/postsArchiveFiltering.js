import {getTranslations} from './translations';
import {setLoadmoreButtonAttributes, loadMorePosts} from "./loadMoreHelpers";

const T = getTranslations('opehuone-variables');

const numberOfPostsSpan = document.querySelector('#posts-archive-number-of-posts');
const postsContainer = document.querySelector('#posts-archive-results');

const getCheckedValues = (form, name) => {
  // need to escape the square brackets with double backslashes (\\) in the selector string
  const escapedName = name.replace(/([\[\]])/g, '\\$1');
  const checkboxes = form.querySelectorAll(`input[type="checkbox"][name="${escapedName}"]:checked`);
  return Array.from(checkboxes).map(input => input.value);
};

const doFiltering = (event, form) => {
  event.preventDefault(); // Prevent the default form submission

  const cornerlabels = getCheckedValues(form, 'cornerlabels[]');
  const categories = getCheckedValues(form, 'category[]');
  const postTags = getCheckedValues(form, 'post_tag[]');

  fetch(T.ajaxUrl, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: new URLSearchParams({
      action: `update_posts_archive_results`,
      cornerLabels: cornerlabels,
      categories: categories,
      postTags: postTags,
      userId: T.userId
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
      if (postsContainer) {
        postsContainer.innerHTML = response.data.output;
      }
      numberOfPostsSpan.innerHTML = response.data.totalPosts;

      // Set load more button properties
      setLoadmoreButtonAttributes(response.data.totalPosts, cornerlabels, categories, postTags);

    })
    .catch((error) => console.error('AJAX Error:', error));
};

const toggleDropdown = () => {
  const filterButtons = document.querySelectorAll('.checkbox-filter__filter-btn');

  filterButtons.forEach((button) => {
    const originalLabel = button.getAttribute('aria-label') || 'Näytä valinnat'; // Fallback label

    button.addEventListener('click', (event) => {
      event.preventDefault();
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
  const checkboxes = document.querySelectorAll('.checkbox-filter__checkbox-input');

  checkboxes.forEach((checkbox) => {
    checkbox.addEventListener('keydown', (event) => {
      if (event.key === 'Enter' || event.key === ' ') {
        event.preventDefault(); // Prevent triggering other elements like buttons
        checkbox.checked = !checkbox.checked; // Toggle checkbox state manually
        checkbox.dispatchEvent(new Event('change')); // Trigger change event if needed
      }
    });
  });
};

const initializeResetButtons = () => {
  const resetButtons = document.querySelectorAll('.checkbox-filter__checkboxes-reset-btn');

  resetButtons.forEach((button) => {
    button.addEventListener('click', (event) => {
      event.preventDefault();
      // Find the closest filter wrapper to scope the reset action
      const filterWrapper = button.closest('.posts-archive__select-filter-wrapper');

      if (filterWrapper) {
        // Select all checked checkboxes within this filter group
        const checkboxes = filterWrapper.querySelectorAll('.checkbox-filter__checkbox-input:checked');

        checkboxes.forEach((checkbox) => {
          checkbox.checked = false;
          checkbox.dispatchEvent(new Event('change')); // Dispatch change event if needed
        });
      }
    });
  });
};

export const postsArchiveFiltering = () => {
  const form = document.querySelector('.posts-archive-filtering');
  if (form) {
    form.addEventListener('submit', (event) => doFiltering(event, form));
  }

  // toggle dropdowns with button
  toggleDropdown();

  // reset buttons
  initializeResetButtons();

  // Load more
  loadMorePosts('load_more_posts_archive_results', postsContainer);
};

