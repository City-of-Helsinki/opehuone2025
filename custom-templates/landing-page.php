<?php

/**
 * Template to be used in eg. Ohjeet-page
 *
 * @package Opehuone
 *
 * Template Name: Laskeutumissivu Opehuone
 */

get_header();

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();
		get_template_part( 'partials/landing-page-with-sidemenu' );
	}
}

get_footer();