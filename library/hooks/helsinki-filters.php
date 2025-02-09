<?php

namespace Opehuone\HelsinkiMainThemeFilters;

add_filter( 'helsinki_default_scheme', function () {
	return 'tram';
} );

add_filter('helsinki_header_search_enabled', '__return_true');
