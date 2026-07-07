import { getTranslations } from './translations';
import Sortable from 'sortablejs';

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

const getUserFavsElements = () => ({
	editFavsButton: document.querySelector('.edit-favs-button'),
	userFavsBox: document.querySelector('.user-favs-box'),
	userFavList: document.querySelector('.user-favs-list'),
	showAllFavsButton: document.querySelector('.show-all-favs'),
});

const replaceFavoritesBox = (markup) => {
	if (!markup) return;

	const { userFavsBox } = getUserFavsElements();
	const isModificationOngoing = userFavsBox?.classList.contains(
		'user-favs-box__modification-ongoing'
	);

	destroySortable();
	if (!userFavsBox) return;

	userFavsBox.outerHTML = markup;

	if (!isModificationOngoing) return;

	const {
		userFavsBox: nextUserFavsBox,
		editFavsButton,
		userFavList,
	} = getUserFavsElements();

	nextUserFavsBox?.classList.add('user-favs-box__modification-ongoing');
	const editFavsButtonText = editFavsButton?.querySelector('span');
	if (editFavsButtonText) {
		editFavsButtonText.textContent = 'Poistu muokkaustilasta';
	}

	if (userFavList) {
		initSortable();
	}
};

const setPinButtonState = (pinButton, addFavorite) => {
	if (pinButton.classList.contains('b-post__pinner')) {
		pinButton.innerHTML = addFavorite ? pinnedSvg : pinSvg;
		pinButton.setAttribute(
			'aria-label',
			addFavorite ? buttonAriaPinned : buttonAriaPin
		);
	} else if (pinButton.classList.contains('pin-btn')) {
		pinButton.classList.toggle('pinned', addFavorite);
		pinButton.setAttribute('aria-pressed', addFavorite);
	}

	pinButton.setAttribute(
		'data-action',
		addFavorite ? 'favs_remove' : 'favs_add'
	);
};

const syncPinButtons = (postId, addFavorite) => {
	const pinButtons = document.querySelectorAll(
		`.b-post__pinner[data-post-id="${postId}"], .pin-btn[data-post-id="${postId}"]`
	);

	pinButtons.forEach((pinButton) => {
		setPinButtonState(pinButton, addFavorite);
	});
};

const addToFavs = () => {
	document.addEventListener('click', (event) => {
		// This is pin button for front page post listing (small bookmark icon)
		let pinnerButton = event.target.closest('.b-post__pinner');

		if (!pinnerButton) {
			// Large pin-button element located for individual pages/posts
			pinnerButton = event.target.closest('.pin-btn');
		}

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
			.then((result) => {
				const addFavorite = action === 'favs_add';

				syncPinButtons(postId, addFavorite);
				replaceFavoritesBox(result?.data?.favoritesBoxMarkup);
			})
			.catch((error) => console.error('AJAX Error:', error));
	});
};

let favsSortable = null;

const removeFromFavs = () => {
	document.addEventListener('click', (event) => {
		const clickedRemoveButton = event.target.closest('.remove-fav-button');
		if (!clickedRemoveButton) return;

		const action = clickedRemoveButton.getAttribute('data-action');
		const postId = clickedRemoveButton.getAttribute('data-post-id');

		if (!action || !postId) {
			console.error('Missing data attributes: action or postId.');
			return;
		}

		// Confirm removal with the user
		const confirmed = confirm(
			'Haluatko varmasti poistaa tämän linkin omista sisällöistäsi?'
		);
		if (!confirmed) return;

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
			.then((result) => {
				syncPinButtons(postId, false);
				replaceFavoritesBox(result?.data?.favoritesBoxMarkup);
			})
			.catch((error) => console.error('AJAX Error:', error));
	});
};

// Initialize Sortable for user favorites list
const initSortable = () => {
	const { userFavList } = getUserFavsElements();
	if (!userFavList || favsSortable) return;

	favsSortable = new Sortable(userFavList, {
		animation: 150,
		handle: '.user-favs-list__item',
		onStart: () => {
			userFavList.classList.add('user-favs-list--dragging');
		},
		onEnd: () => {
			userFavList.classList.remove('user-favs-list--dragging');
		},
	});
};

// Destroy sortable when exiting edit mode
const destroySortable = () => {
	if (!favsSortable) return;

	const { userFavList } = getUserFavsElements();

	favsSortable.destroy();
	favsSortable = null;
	userFavList?.classList.remove('user-favs-list--dragging');
};

// Toggle show all favorites button
const toggleShowAllFavs = () => {
	document.addEventListener('click', (event) => {
		const { showAllFavsButton, userFavList } = getUserFavsElements();
		if (!showAllFavsButton || !userFavList) return;
		if (!event.target.closest('.show-all-favs')) return;

		userFavList.classList.toggle('user-favs-list--show-all');
		showAllFavsButton.setAttribute(
			'aria-expanded',
			userFavList.classList.contains('user-favs-list--show-all')
		);
		showAllFavsButton.setAttribute(
			'aria-label',
			userFavList.classList.contains('user-favs-list--show-all')
				? 'Piilota kaikki tallennetut sisällöt'
				: 'Näytä kaikki tallennetut sisällöt'
		);
		showAllFavsButton.querySelector('span').textContent =
			userFavList.classList.contains('user-favs-list--show-all')
				? 'Piilota kaikki tallennetut sisällöt'
				: 'Näytä kaikki tallennetut sisällöt';
	});
};

// Toggle edit mode
const toggleFavsModify = () => {
	const toggleText = 'Poistu muokkaustilasta';

	document.addEventListener('click', (event) => {
		const clickedEditButton = event.target.closest('.edit-favs-button');
		if (!clickedEditButton || clickedEditButton.disabled) return;

		const { userFavsBox, userFavList } = getUserFavsElements();
		const editFavsButtonText = clickedEditButton.querySelector('span');
		if (!userFavsBox || !userFavList || !editFavsButtonText) return;

		const originalText = 'Muokkaa';

		userFavsBox.classList.toggle('user-favs-box__modification-ongoing');
		const isModificationOngoing = userFavsBox.classList.contains(
			'user-favs-box__modification-ongoing'
		);

		// Check if the user is in edit mode and init or destroy Sortable
		if (isModificationOngoing) {
			initSortable();
		} else {
			destroySortable();

			// Send new favs list when exiting edit mode
			const newOrder = Array.from(userFavList.children).map(
				(item) => item.dataset.id
			);

			fetch(T.ajaxUrl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: new URLSearchParams({
					action: 'favs_update_order',
					userId: T.userId,
					newOrder: JSON.stringify(newOrder),
					nonce: T.opehuoneNonce,
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
				.then((data) => {
					console.log('Favorites reordered successfully:', data);
				})
				.catch((error) => console.error('AJAX Error:', error));
		}

		editFavsButtonText.textContent =
			editFavsButtonText.textContent === originalText
				? toggleText
				: originalText;
	});
};

export const userFavs = () => {
	addToFavs();
	removeFromFavs();
	toggleShowAllFavs();
	toggleFavsModify();
};
