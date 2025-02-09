<?php

namespace Opehuone\TemplateTypeLabels;

// Template names into here
function default_template_types_labels( $template_types ) {
//	$template_types['archive-cases'] = array(
//		'title'       => 'Töitä esittelysivu',
//		'description' => 'Töiden arkistonäkymä.',
//	);
//
//	$template_types['archive-blogs'] = array(
//		'title'       => 'Blogit arkisto',
//		'description' => 'Blogien arkistonäkymä.',
//	);
//
//	$template_types['archive-guide'] = array(
//		'title'       => 'Oppaiden arkisto',
//		'description' => 'Oppaiden arkistonäkymä.',
//	);

	return $template_types;
}

add_filter( 'default_template_types', __NAMESPACE__ . '\\default_template_types_labels' );
