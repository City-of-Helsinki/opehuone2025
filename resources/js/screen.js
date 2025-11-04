// import local dependencies
import Router from './util/router';
import common from './routes/Common';
import pageTemplateUserSettings from './routes/pageTemplateUserSettings';
import pageTemplateWithSidemenu from './routes/pageTemplateWithSidemenu';
import pageTemplateDockSettings from './routes/pageTemplateDockSettings';

/**
 *
 * @type {Router}
 */
const router = new Router({
	// All pages
	common,
	// User settings
	pageTemplateUserSettings,
	// Sidemenu page
	pageTemplateWithSidemenu,
	// dock settings
	pageTemplateDockSettings,
});

// Load Events
document.addEventListener('DOMContentLoaded', () => router.loadEvents());
