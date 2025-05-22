import { hashTagFunctions } from '../lib/scrollTo';
import { sideLinksList } from '../lib/sideLinksList';
import { serviceFailure } from '../lib/serviceFailure';
import { userFavs } from '../lib/userFavs';
import { postsFiltering } from '../lib/postsFiltering';
import { profileOpener } from '../lib/profileOpener';

export default {
	init() {
		jQuery(function($) {
		const $dock = $('.dock');
		const $wholeDock = $('.whole-dock');

		// Hashtag smooth scrolls
		hashTagFunctions();
		// Side links list functions
		sideLinksList();
		// Service failure
		serviceFailure();
		// User favs functions
		userFavs();
		// Posts filtering
		postsFiltering();
		// Profile opener
		profileOpener();

		$('.dock-toggler').on('click', (e) => {
			e.preventDefault();
			$('body').toggleClass('whole-dock-opened');
			toggleAria($dock, 'aria-hidden');
			toggleAria($wholeDock, 'aria-hidden');
			toggleTabIndex($dock);
			toggleTabIndex($wholeDock);
			});
		});
	},
	finalize() {
		// JavaScript to be fired on all pages, after page specific JS is fired
	},
};
