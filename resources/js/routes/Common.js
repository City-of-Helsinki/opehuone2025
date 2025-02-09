import { hashTagFunctions } from '../lib/scrollTo';

export default {
	init() {
		// Hashtag smooth scrolls
		hashTagFunctions();
	},
	finalize() {
		// JavaScript to be fired on all pages, after page specific JS is fired
	},
};
