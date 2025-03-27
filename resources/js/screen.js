// import local dependencies
import Router from './util/router';
import common from './routes/Common';
import pageTemplateUserSettings from './routes/pageTemplateUserSettings';
import pageTemplateWithSidemenu from './routes/pageTemplateWithSidemenu';
import postTypeArchiveTraining from './routes/postTypeArchiveTraining';
import blog from './routes/blog';

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
  // Training archive
  postTypeArchiveTraining,
  // Blog
  blog
});

// Load Events
document.addEventListener('DOMContentLoaded', () => router.loadEvents());
