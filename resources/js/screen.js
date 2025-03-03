// import local dependencies
import Router from './util/router';
import common from './routes/Common';
import pageTemplateUserSettings from './routes/pageTemplateUserSettings';

/**
 *
 * @type {Router}
 */
const router = new Router({
	// All pages
	common,
	// User settings
	pageTemplateUserSettings,
});

// Load Events
document.addEventListener('DOMContentLoaded', () => router.loadEvents());
