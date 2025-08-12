
import { getTranslations } from './translations';
import { sidemenuToggler } from './sidemenuToggler';

const T = getTranslations('opehuone-variables');

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

      const currentPageId = document.querySelector('article.content')?.dataset.currentPageId;

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
          .catch((error) => console.error('Virhe alkuperäisen valikon palautuksessa:', error));

        return;
      }

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
          console.log('AJAX response:', response);

          const targetSelector =
            toTarget === 'posts'
              ? '.b-posts-row'
              : toTarget === 'training'
              ? '.b-training-row'
              : 'aside';

          const container = document.querySelector(targetSelector);
          if (container) {
            container.innerHTML = '';
            container.innerHTML = response.data.output;
            if (toTarget === 'pages') {
              sidemenuToggler();
            }
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
