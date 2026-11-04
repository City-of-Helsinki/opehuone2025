<?php

/**
 * Template that handles user settings
 *
 *
 * Template Name: Käyttäjäasetukset
 */

get_header();

get_template_part( 'partials/components/service-notices' ); 

if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        get_template_part( 'partials/user-settings-page' );
    }
}

get_footer();
