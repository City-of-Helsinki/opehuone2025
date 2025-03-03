import { getTranslations } from '../lib/translations';

const submitButton = document.querySelector(
	'.user-settings-form__submit-button'
);
const T = getTranslations('opehuone-variables');

export default {
	init() {
		const form = document.getElementById('user-settings');

		if (!form) return;

		form.addEventListener('submit', function (event) {
			event.preventDefault();
			submitButton.classList.add('is-disabled');
			const originalButtonText = submitButton.textContent;
			submitButton.textContent = 'Asetuksia päivitetään';

			const formData = new FormData(form);
			const serializedData = {};

			formData.forEach((value, key) => {
				if (key === 'cornerlabels[]') {
					serializedData[key] = serializedData[key] || [];
					serializedData[key].push(value);
				} else {
					serializedData[key] = value;
				}
			});

			fetch(T.ajaxUrl, {
				method: 'POST',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded',
				},
				body: new URLSearchParams({
					action: 'update_user_settings',
					userId: T.userId,
					cornerLabels: serializedData['cornerlabels[]'],
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
				}) // Assuming the response is JSON
				.then(() => {
					submitButton.textContent = 'Asetukset päivitetty';
					setTimeout(() => {
						submitButton.classList.remove('is-disabled');
						submitButton.textContent = originalButtonText;
					}, 3000);
				})
				.catch((error) => console.error('AJAX Error:', error));
		});
	},
	finalize() {
		// JavaScript to be fired after the init JS
	},
};
