import { hashTagFunctions } from '../lib/scrollTo';
import { sideLinksList } from '../lib/sideLinksList';
import { serviceFailure } from '../lib/serviceFailure';
import { userFavs } from '../lib/userFavs';
import { postsFiltering } from '../lib/postsFiltering';
import { profileOpener } from '../lib/profileOpener';
import { addNewOwnService, handleAddNewServiceClick } from '../lib/ownServices'
import { concentrationFunctions } from "../lib/concentration";
import { findkitUI } from "../lib/findkit";

function toggleAria($element, attribute) {
	const isHidden = $element.attr(attribute) === 'true';
	$element.attr(attribute, isHidden ? 'false' : 'true');
}

function toggleTabIndex($element) {
	$element.find('a, button, input, select, textarea').each(function () {
		const $el = jQuery(this);
		const tabIndex = $el.attr('tabindex');
		if (typeof tabIndex === 'undefined' || tabIndex === '0') {
			$el.attr('tabindex', '-1');
		} else {
			$el.attr('tabindex', '0');
		}
	});
}


export default {
	init() {
		jQuery(function(jQuery) {
		const $dock = jQuery('.dock');
		const $wholeDock = jQuery('.whole-dock');

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
		// Own service functions
		handleAddNewServiceClick();
		addNewOwnService();
		// Concentration functions
		concentrationFunctions();

		jQuery('.dock-toggler').on('click', (e) => {
			e.preventDefault();
			jQuery('body').toggleClass('whole-dock-opened');
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
