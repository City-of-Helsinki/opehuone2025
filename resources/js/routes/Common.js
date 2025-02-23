import { hashTagFunctions } from '../lib/scrollTo';
import { sideLinksList } from '../lib/sideLinksList';
import { serviceFailure } from '../lib/serviceFailure';
import { userFavs } from '../lib/userFavs';

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
	},
	finalize() {
		// JavaScript to be fired on all pages, after page specific JS is fired
	},
};
