<?php
/**
 * Filter to change capabilities for the default category taxonomy
 * Only admins and editors can manage categories.
 */
add_filter('register_taxonomy_args', function ( $args, $taxonomy ) {

    if ( $taxonomy === 'category' ) {
        $args['capabilities'] = [
            'manage_terms' => 'manage_categories',
            'edit_terms'   => 'manage_categories',
            'delete_terms' => 'manage_categories',
            'assign_terms' => 'edit_posts',
        ];
    }

    return $args;

}, 10, 2);
