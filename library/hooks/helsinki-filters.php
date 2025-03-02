<?php

namespace Opehuone\HelsinkiMainThemeFilters;

// Default scheme
add_filter( 'helsinki_default_scheme', function () {
	return 'tram';
} );

// Enable search
add_filter('helsinki_header_search_enabled', '__return_true');

// Allow all blocks, especially classic (core/freeform) block
add_filter('helsinki_wp_disallowed_blocks', function () {
	return [];
});
