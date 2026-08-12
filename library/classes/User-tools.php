<?php
use function \Opehuone\TemplateFunctions\get_user_cornerlabels_with_added_default_value;

class User_tools {
    private $user;

    public function __construct() {
        $this->user = wp_get_current_user();
    }

    public function get_all_tools() {
        
        $user_cornerlabels = get_user_cornerlabels_with_added_default_value();
        
        $args = [
            'post_type'      => 'tools',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'title',
            'order'          => 'ASC',
        ];

        if ( ! empty( $user_cornerlabels ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => 'cornerlabels',
                    'field'    => 'term_id',
                    'terms'    => $user_cornerlabels,
                ],
            ];
        }

        return get_posts( $args );
    }

    public function get_user_active_tools() {
        $all_tools = $this->get_all_tools();
        $valid_ids = wp_list_pluck( $all_tools, 'ID' );

        $stored = get_user_meta( $this->user->ID, 'user_active_tools', true );

        if ( ! is_array( $stored ) ) {

            $defaults = $valid_ids;

            if ( is_user_logged_in() ) {
                update_user_meta( $this->user->ID, 'user_active_tools', $defaults );
            }

            return $defaults;
        }

        $stored = array_map( 'absint', $stored );
        return array_values( array_intersect( $stored, $valid_ids ) );
    }

    public function save_user_active_tools( array $tool_ids ) {
        $sanitized = array_values( array_filter( array_map( 'absint', $tool_ids ) ) );
        update_user_meta( $this->user->ID, 'user_active_tools', $sanitized );
    }
}