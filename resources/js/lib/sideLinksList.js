const mainModifyButton = document.querySelector('.side-links-list__edit-link');
const sideLinksBox = document.querySelector('.side-links-list-box');

// Toggles sideLinksBox visibility by toggling class .side-links-list-box--modification-ongoing when mainModifyButton is clicked
const toggleModifyVisibility = () => {
	if (!mainModifyButton || !sideLinksBox) return;

	mainModifyButton.addEventListener('click', () => {
		sideLinksBox.classList.toggle(
			'side-links-list-box--modification-ongoing'
		);
	});
};

export const sideLinksList = () => {
	toggleModifyVisibility();
};
