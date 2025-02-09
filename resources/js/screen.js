// import local dependencies
import Router from './util/router';
import common from './routes/Common';

/**
 *
 * @type {Router}
 */
const router = new Router({
	// All pages
	common,
});

// Load Events
document.addEventListener('DOMContentLoaded', () => router.loadEvents());
