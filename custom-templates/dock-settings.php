<?php

/**
 * Template that handles dock settings
 *
 * @package Opehuone
 *
 * Template Name: Dock-asetukset
 */

get_header();

if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        get_template_part( 'partials/dock-settings-page' );
    }
}

get_footer();
