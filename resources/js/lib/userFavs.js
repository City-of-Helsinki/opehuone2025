import { getTranslations } from './translations';

const T = getTranslations('opehuone-variables');

const pinSvg = `<svg width="42" height="46" viewBox="0 0 42 46" fill="none" xmlns="http://www.w3.org/2000/svg">
  <circle cx="21" cy="21.3828" r="21" fill="white" fill-opacity="0.8"></circle>
  <path d="M28 31.3828V11.3828H14V31.3828L21 26.8828L28 31.3828Z" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>`;

const pinnedSvg = `<svg width="42" height="42" viewBox="0 0 42 42" fill="none" xmlns="http://www.w3.org/2000/svg">
  <circle cx="21" cy="21" r="21" fill="white" fill-opacity="0.85"></circle>
  <path d="M28 31V11H14V31L21 26.5L28 31Z" fill="#008741" stroke="#008741" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
</svg>`;

const buttonAriaPinned = 'Poista sivu kirjanmerkeistä';
const buttonAriaPin = 'Lisää sivu kirjanmerkkeihin';

const addToFavs = () => {
	document.addEventListener('click', (event) => {
		const pinnerButton = event.target.closest('.b-post__pinner');

		if (!pinnerButton) return; // Ignore clicks outside .b-post__pinner buttons

		const action = pinnerButton.getAttribute('data-action');
		const postId = pinnerButton.getAttribute('data-post-id');

		if (!action || !postId) {
			console.error('Missing data attributes: action or postId.');
			return;
		}

		fetch(T.ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: new URLSearchParams({
				action,
				userId: T.userId,
				postId,
				nonce: T.opehuoneNonce,
			}),
		})
			.then((response) => {
				if (!response.ok) {
					throw new Error(`HTTP error! Status: ${response.status}`);
				}
				return response.json();
			})
			.then(() => {
				// Toggle button content and data-action attribute
				if (action === 'favs_add') {
					pinnerButton.innerHTML = pinnedSvg;
					pinnerButton.setAttribute('data-action', 'favs_remove');
					pinnerButton.setAttribute('aria-label', buttonAriaPinned);
				} else {
					pinnerButton.innerHTML = pinSvg;
					pinnerButton.setAttribute('data-action', 'favs_add');
					pinnerButton.setAttribute('aria-label', buttonAriaPin);
				}
			})
			.catch((error) => console.error('AJAX Error:', error));
	});
};

export const userFavs = () => {
	addToFavs();
};
