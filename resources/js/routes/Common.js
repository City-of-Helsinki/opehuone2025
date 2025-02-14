import { hashTagFunctions } from '../lib/scrollTo';
import { sideLinksList } from '../lib/sideLinksList';

export default {
	init() {
		// Hashtag smooth scrolls
		hashTagFunctions();
		// Side links list functions
		sideLinksList();
	},
	finalize() {
		// JavaScript to be fired on all pages, after page specific JS is fired
	},
};
