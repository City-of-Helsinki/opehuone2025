import { getTranslations } from '../lib/translations';
const T = getTranslations('opehuone-variables');

const numberOfPostsSpan = document.querySelector('#training-archive-number-of-posts');

const doFiltering = (event) => {
  event.preventDefault(); // Prevent the default form submission

  const cornerLabels = document.querySelector('#training-archive-cornerlabels').value;
  const trainingTheme = document.querySelector('#training-archive-training_theme').value;

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
        throw new Error(
          `HTTP error! Status: ${response.status}`
        );
      }
      return response.json();
    }) // Assuming the response is JSON
    .then((response) => {
      const postsContainer = document.querySelector('#training-archive-results');
      if (postsContainer) {
        postsContainer.innerHTML = response.data.output;
      }
      numberOfPostsSpan.innerHTML = response.data.totalPosts
    })
    .catch((error) => console.error('AJAX Error:', error));
};

export const trainingFiltering = () => {
  const form = document.querySelector('.training-archive-filtering');
  if (form) {
    form.addEventListener('submit', doFiltering);
  }
};

