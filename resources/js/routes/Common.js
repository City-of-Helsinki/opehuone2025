import { hashTagFunctions } from '../lib/scrollTo';
import { sideLinksList } from '../lib/sideLinksList';
import { serviceFailure } from '../lib/serviceFailure';
import { userFavs } from '../lib/userFavs';
import { postsFiltering } from '../lib/postsFiltering';
import { profileOpener } from '../lib/profileOpener';

export default {
	init() {
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
	},
	finalize() {
		// JavaScript to be fired on all pages, after page specific JS is fired
	},
};
