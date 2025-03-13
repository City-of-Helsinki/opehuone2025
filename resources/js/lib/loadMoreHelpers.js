import {getTranslations} from './translations';

const T = getTranslations('opehuone-variables');

const loadMoreButton = document.querySelector('.posts-archive__load-more-btn');

/**
 * Convert array to comma separated string
 *
 * @param {Array} array - The array to be converted.
 * @returns {string} - Comma-separated string or an empty string if input is not an array.
 */
const convertArrayToString = (array) => {
  return Array.isArray(array) ? array.join(',') : '';
};

const setLoadMoreButtonOffSet = (currentOffSet) => {
  const totalPosts = parseInt(loadMoreButton.getAttribute('data-total-posts'));
  const newOffset = 15 + currentOffSet;

  loadMoreButton.setAttribute('data-posts-offset', newOffset);

  if (newOffset < totalPosts) {
    loadMoreButton.classList.remove('is-disabled');
  }
};

export const setLoadmoreButtonAttributes = (totalPosts, cornerlabels, categories, postTags) => {
  loadMoreButton.classList.remove('is-disabled');
  loadMoreButton.setAttribute('data-total-posts', totalPosts);
  loadMoreButton.setAttribute('data-posts-offset', 15);
  loadMoreButton.setAttribute('data-cornerlabels', convertArrayToString(cornerlabels));
  loadMoreButton.setAttribute('data-categories', convertArrayToString(categories));
  loadMoreButton.setAttribute('data-post-tags', convertArrayToString(postTags));

  if (totalPosts <= 15) {
    loadMoreButton.classList.add('is-disabled');
  }
};

export const loadMorePosts = (ajaxAction, container) => {
  loadMoreButton.addEventListener('click', (event) => {
    event.preventDefault();
    loadMoreButton.classList.add('is-disabled');
    const currentOffSet = parseInt(loadMoreButton.getAttribute('data-posts-offset'));
    const cornerlabels = loadMoreButton.getAttribute('data-cornerlabels');
    const categories = loadMoreButton.getAttribute('data-categories');
    const postTags = loadMoreButton.getAttribute('data-post-tags');

    fetch(T.ajaxUrl, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        action: ajaxAction,
        cornerLabels: cornerlabels,
        categories: categories,
        postTags: postTags,
        userId: T.userId,
        offset: currentOffSet
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
        if (container) {
          container.insertAdjacentHTML('beforeend', response.data.output);
        }

        // Set load more button properties
        setLoadMoreButtonOffSet(currentOffSet);

      })
      .catch((error) => console.error('AJAX Error:', error));
  });
};
