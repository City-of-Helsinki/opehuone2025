# Opehuone 2025

This is repo for Opehuone wp theme.

## Theme requirements
This theme is meant to be child theme of [Helsinki parent theme](https://github.com/City-of-Helsinki/wordpress-helfi-helsinkiteema).

## Plugin requirements

- [Helsinki WP plugin](https://github.com/City-of-Helsinki/wordpress-helfi-hds-wp)
- [ACF Pro](https://www.advancedcustomfields.com/pro/)


## CSS/JS development

Install needed node module with `npm install`. Use example [Volta.sh](https://volta.sh/), to automatically set correct node version for the project. If you dont' have volta installed, check node version from package.json.

`npm start` => Do dev work and use livereload to build css/js

`npm run build` ==> Build css/js for production

`npm run pretiier` => format your scss/js to prettier rules

JS-development is done using vanilla js with no jquery dependencies. Style code uses SASS with prettier config. Note that some css-variables (colors, example --color-black-90) comes from helsinki parent theme.

## Using the old Opehuone settings / data

It would be good to keep old data as it is as much as possible. Example current Opehuone settings, that has been done with ACF.

That way end users don't need to input everything all over again.

## Adding meta / custom fields

You can use ACF, but there is also more modern way...

You can register meta fields for post types using `register_post_meta` function. See example `library/custom-posts/training.php` how it's done. Then do the settings with js, for that see file `resources/js/settings/training.js`.

## About file structure

`build` ==> DONT touch here, files are automatically compiled using `npm run build`
`custom-templates` ==> Add all custom templates here
`libary` ==> Folder with all sorts of files, such as custom post registration, custom taxonomy registration, hooks, filters and utility functions. NOTE that files inside each folder are automatically loaded in functions.php.
`partials` ==> files used with wp's `get_template_part()` function
`resources` ==> folder to work with js and css