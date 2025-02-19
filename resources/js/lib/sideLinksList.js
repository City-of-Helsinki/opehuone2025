import { getTranslations } from './translations';

const ownLinkItem = (url, urlName) => {
	const li = document.createElement('li');
	li.classList.add('side-links-list__item');
	li.innerHTML = `
        <a href="${url}"
           class="side-links-list__link"
           target="_blank">
            ${urlName}
        </a>
        <button class="side-links-list__remove-btn side-links-list__remove-btn--custom"
                aria-label="Poista tämä linkki"
                data-custom-link-name="${urlName}"
                data-custom-link-url="${url}">
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <g fill="none" fill-rule="evenodd">
                <rect width="24" height="24"></rect>
                <path fill="currentColor" d="M12,3 C7.02943725,3 3,7.02943725 3,12 C3,16.9705627 7.02943725,21 12,21 C16.9705627,21 21,16.9705627 21,12 C21,7.02943725 16.9705627,3 12,3 Z M15,7.5 L16.5,9 L13.5,12 L16.5,15 L15,16.5 L12,13.5 L9,16.5 L7.5,15 L10.5,12 L7.5,9 L9,7.5 L12,10.5 L15,7.5 Z"></path>
              </g>
            </svg>
        </button>
    `;
	return li; // Return a DOM element, not a string
};

const mainModifyButton = document.querySelector('.side-links-list__edit-link');
const sideLinksBox = document.querySelector('.side-links-list-box');
const modifyButtonText = mainModifyButton?.querySelector('span');
const resetButtonStage1 = document.querySelector('.side-links-list__reset-btn');
const resetButtonStage2 = document.querySelector(
	'.side-links-list__reset-btn--final'
);
const submitBtn = document.querySelector('.own-links__submit-btn');
const urlNameInput = document.querySelector('#own-link-name');
const urlInput = document.querySelector('#own-link-url');
const notificationsWrapper = document.querySelector(
	'.own-links__add-new-form-notifications'
);
const addNewForm = document.querySelector('#own-links__add-new-form');
const customList = document.querySelector('#user-custom-side-links-list');

const T = getTranslations('opehuone-variables');

const isValidUrl = (string) => {
	let url;

	try {
		url = new URL(string);
	} catch (_) {
		return false;
	}

	return url.protocol === 'http:' || url.protocol === 'https:';
};

const addNewCustomLink = () => {
	addNewForm.addEventListener('submit', (event) => {
		event.preventDefault(); // Prevent the page from refreshing
		let isValidForm = true;
		let urlName = urlNameInput.value.trim();
		const url = urlInput.value.trim();

		if (!urlName) {
			isValidForm = false;
		}

		if (!isValidUrl(url)) {
			isValidForm = false;
		}

		if (urlName.length > 50) {
			urlName = urlName.slice(0, 50);
		}

		notificationsWrapper.style.display = 'block';

		if (isValidForm) {
			notificationsWrapper.textContent = 'Uutta linkkiä lisätään...';
			submitBtn.classList.add('is-disabled');

			fetch(T.ajaxUrl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: new URLSearchParams({
					action: 'add_new_own_link',
					userId: T.userId,
					urlName,
					url,
					nonce: T.opehuoneNonce,
				}),
			})
				.then((response) => response.json()) // Assuming the response is JSON
				.then(() => {
					submitBtn.classList.remove('is-disabled');
					urlNameInput.value = '';
					urlInput.value = '';
					customList.appendChild(ownLinkItem(url, urlName));

					setTimeout(() => {
						notificationsWrapper.innerHTML = 'Linkki lisätty.';

						setTimeout(() => {
							notificationsWrapper.style.display = 'none';
						}, 300);
					}, 100);
				})
				.catch((error) => console.error('AJAX Error:', error));
		} else {
			notificationsWrapper.textContent =
				'Linkin lisääminen ei onnistunut. Annoithan linkille nimen ja osoitteen. Huomaathan, että linkin pitää alkaa joko http:// tai https://.';
		}
	});
};

const toggleResetStage2 = () => {
	resetButtonStage1.addEventListener('click', () => {
		resetButtonStage2.classList.remove(
			'side-links-list__reset-btn--final--hidden'
		);
	});
};

const toggleModifyVisibility = () => {
	if (!mainModifyButton || !sideLinksBox || !modifyButtonText) return;

	const originalText = modifyButtonText.textContent;
	const toggleText = 'Poistu muokkaustilasta';

	mainModifyButton.addEventListener('click', () => {
		sideLinksBox.classList.toggle(
			'side-links-list-box--modification-ongoing'
		);

		// Toggle the button text
		modifyButtonText.textContent =
			modifyButtonText.textContent === originalText
				? toggleText
				: originalText;
	});
};

const customLinkRemoval = () => {
	document.addEventListener('click', (e) => {
		const target = e.target.closest('.side-links-list__remove-btn--custom');
		if (!target) return;

		e.preventDefault();
		const url = target.getAttribute('data-custom-link-url');
		const urlName = target.getAttribute('data-custom-link-name');

		// Remove the closest `.front-side__links-list-item`
		const listItem = target.closest('.side-links-list__item');
		if (listItem) {
			listItem.remove();
		}

		// Send AJAX request using fetch()
		fetch(T.ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: new URLSearchParams({
				action: 'remove_custom_link',
				userId: T.userId,
				url,
				urlName,
				nonce: T.opehuoneNonce,
			}),
		})
			.then((response) => response.json()) // Assuming the response is JSON
			.then(() => {
				// eslint-disable-next-line no-alert
				alert('Linkki poistettu.');
			})
			.catch((error) => console.error('AJAX Error:', error));
	});
};

export const sideLinksList = () => {
	toggleModifyVisibility();
	toggleResetStage2();
	addNewCustomLink();
	customLinkRemoval();
};
