<?php
/**
 * Filter to change capabilities for the default category taxonomy
 * Only admins can manage categories, but editors and authors can just assign them to posts.
 */
add_filter('register_taxonomy_args', function ( $args, $taxonomy ) {

    if ( $taxonomy === 'category' ) {
        $args['capabilities'] = [
            'manage_terms' => 'manage_options',
            'edit_terms'   => 'manage_options',
            'delete_terms' => 'manage_options',
            'assign_terms' => 'edit_posts',
        ];
    }

    return $args;

}, 10, 2);
