// import local dependencies
import Router from './util/router';
import common from './routes/Common';
import pageTemplateUserSettings from './routes/pageTemplateUserSettings';
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
  // Training archive
  postTypeArchiveTraining,
  // Blog
  blog
});

// Load Events
document.addEventListener('DOMContentLoaded', () => router.loadEvents());
