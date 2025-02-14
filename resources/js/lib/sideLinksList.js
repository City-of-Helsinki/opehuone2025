const mainModifyButton = document.querySelector('.side-links-list__edit-link');
const sideLinksBox = document.querySelector('.side-links-list-box');
const modifyButtonText = mainModifyButton?.querySelector('span');
const resetButtonStage1 = document.querySelector('.side-links-list__reset-btn');

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

export const sideLinksList = () => {
	toggleModifyVisibility();
};
