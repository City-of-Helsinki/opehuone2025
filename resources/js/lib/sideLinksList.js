import { getTranslations } from './translations';

// Create a list item element for a link object
const ownLinkItem = (link) => {
	const li = document.createElement('li');
	li.classList.add('side-links-list__item');
	li.innerHTML = `
        <a href="${link.url}"
           class="side-links-list__link"
           target="_blank">
            ${link.title}
        </a>
        <button class="side-links-list__remove-btn side-links-list__remove-btn--${link.type}"
                aria-label="Poista tämä linkki"
                ${link.type === 'custom' ? `data-custom-link-name="${link.title}" data-custom-link-url="${link.url}"` : `data-link-url="${link.url}"`}>
            <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
              <g fill="none" fill-rule="evenodd">
                <rect width="24" height="24"></rect>
                <path fill="currentColor" d="M12,3 C7.02943725,3 3,7.02943725 3,12 C3,16.9705627 7.02943725,21 12,21 C16.9705627,21 21,16.9705627 21,12 C21,7.02943725 16.9705627,3 12,3 Z M15,7.5 L16.5,9 L13.5,12 L16.5,15 L15,16.5 L12,13.5 L9,16.5 L7.5,15 L10.5,12 L7.5,9 L9,7.5 L12,10.5 L15,7.5 Z"></path>
              </g>
            </svg>
        </button>
    `;
	return li;
};

const mainModifyButton = document.querySelector('.side-links-list__edit-link');
const sideLinksBox = document.querySelector('.side-links-list-box');
const modifyButtonText = mainModifyButton?.querySelector('span');
const resetButtonFinal = document.querySelector(
	'.side-links-list__reset-btn--final'
);
const submitBtn = document.querySelector('.own-links__submit-btn');
const urlNameInput = document.querySelector('#own-link-name');
const urlInput = document.querySelector('#own-link-url');
const notificationsWrapper = document.querySelector(
	'.own-links__add-new-form-notifications'
);
const addNewForm = document.querySelector('#own-links__add-new-form');
const customList = document.querySelector('.side-links-list');

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

// Add new custom link
const addNewCustomLink = () => {
	if (!addNewForm) return;

	addNewForm.addEventListener('submit', (event) => {
		event.preventDefault(); // Prevent page refresh

		let isValidForm = true;
		let urlName = urlNameInput.value.trim();
		const url = urlInput.value.trim();

		if (!urlName) isValidForm = false;
		if (!isValidUrl(url)) isValidForm = false;
		if (urlName.length > 50) urlName = urlName.slice(0, 50);

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
				.then((response) => {
					if (!response.ok)
						throw new Error(
							`HTTP error! Status: ${response.status}`
						);
					return response.json();
				})
				.then((data) => {
					submitBtn.classList.remove('is-disabled');
					urlNameInput.value = '';
					urlInput.value = '';

					const newLink = data.data;
					if (!newLink || !newLink.title)
						throw new Error(
							'Invalid Ajax response: missing link data'
						);

					// Insert the new link in alphabetical order
					const items = Array.from(
						customList.querySelectorAll('.side-links-list__item')
					);
					let inserted = false;

					items.forEach((item) => {
						if (inserted) return;
						const itemTitle = item
							.querySelector('.side-links-list__link')
							.textContent.trim();
						if (
							newLink.title.localeCompare(itemTitle, 'fi', {
								sensitivity: 'base',
							}) < 0
						) {
							customList.insertBefore(ownLinkItem(newLink), item);
							inserted = true;
						}
					});

					if (!inserted) customList.appendChild(ownLinkItem(newLink));

					// Show success notification
					setTimeout(() => {
						notificationsWrapper.innerHTML =
							'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M21 7L10 18L4.5 12.5L6 11L10 15L19.5 5.5L21 7Z" fill="black"/></svg> Linkki lisätty.';
						setTimeout(() => {
							notificationsWrapper.style.display = 'none';
						}, 3000);
					}, 1000);
				})
				.catch((error) => {
					console.error('AJAX Error:', error);
					notificationsWrapper.innerHTML =
						'Tapahtui virhe linkkiä lisättäessä.';
				});
		} else {
			notificationsWrapper.innerHTML =
				'<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M18 7.5L13.5 12L18 16.5L16.5 18L12 13.5L7.5 18L6 16.5L10.5 12L6 7.5L7.5 6L12 10.5L16.5 6L18 7.5Z" fill="black"/></svg> Linkin lisääminen ei onnistunut. Annoithan linkille nimen ja osoitteen. Huomaathan, että linkin pitää alkaa joko http:// tai https://.';
		}
	});
};

// Toggle edit/add mode
const toggleModifyVisibility = () => {
	if (!mainModifyButton || !sideLinksBox || !modifyButtonText) return;
	const originalText = modifyButtonText.textContent;
	const toggleText = 'Poistu muokkaustilasta';

	mainModifyButton.addEventListener('click', () => {
		sideLinksBox.classList.toggle(
			'side-links-list-box--modification-ongoing'
		);
		modifyButtonText.textContent =
			modifyButtonText.textContent === originalText
				? toggleText
				: originalText;
	});
};

// Shared removal logic for both link types
const setupLinkRemoval = ({ btnClass, urlAttr, nameAttr = null, action }) => {
	document.addEventListener('click', (e) => {
		const target = e.target.closest(btnClass);
		if (!target) return;
		e.preventDefault();

		const confirmed = confirm('Haluatko varmasti poistaa tämän linkin?');
		if (!confirmed) return;

		const url = target.getAttribute(urlAttr);
		const urlName = nameAttr ? target.getAttribute(nameAttr) : null;
		const listItem = target.closest('.side-links-list__item');
		if (listItem) listItem.remove();

		const bodyParams = {
			action,
			userId: T.userId,
			url,
			nonce: T.opehuoneNonce,
		};
		if (urlName) bodyParams.urlName = urlName;

		fetch(T.ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: new URLSearchParams(bodyParams),
		})
			.then((response) => {
				if (!response.ok)
					throw new Error(`HTTP error! Status: ${response.status}`);
				return response.json();
			})
			.catch((error) => console.error('AJAX Error:', error));
	});
};

// Remove custom link
const customLinkRemoval = () => {
	setupLinkRemoval({
		btnClass: '.side-links-list__remove-btn--custom',
		urlAttr: 'data-custom-link-url',
		nameAttr: 'data-custom-link-name',
		action: 'remove_custom_link',
	});
};

// Remove default link
const defaultLinkRemoval = () => {
	setupLinkRemoval({
		btnClass: '.side-links-list__remove-btn--default',
		urlAttr: 'data-link-url',
		action: 'remove_default_link',
	});
};

// Reset all links
const resetAllLinks = () => {
	if (!resetButtonFinal) return;

	resetButtonFinal.addEventListener('click', (e) => {
		e.preventDefault();
		const confirmed = confirm('Haluatko varmasti palauttaa kaikki linkit?');
		if (!confirmed) return;

		fetch(T.ajaxUrl, {
			method: 'POST',
			headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
			body: new URLSearchParams({
				action: 'reset_own_links',
				user_id: T.userId,
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
				location.reload();
			})
			.catch((error) => console.error('AJAX Error:', error));
	});
};

export const sideLinksList = () => {
	toggleModifyVisibility();
	addNewCustomLink();
	customLinkRemoval();
	defaultLinkRemoval();
	resetAllLinks();
};
