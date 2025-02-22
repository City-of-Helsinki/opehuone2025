import { hashTagFunctions } from '../lib/scrollTo';
import { sideLinksList } from '../lib/sideLinksList';
import { serviceFailure } from '../lib/serviceFailure';

export default {
	init() {
		// Hashtag smooth scrolls
		hashTagFunctions();
		// Side links list functions
		sideLinksList();
		// Service failure
		serviceFailure();
	},
	finalize() {
		// JavaScript to be fired on all pages, after page specific JS is fired
	},
};
