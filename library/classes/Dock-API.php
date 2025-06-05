<?php

class Dock_api extends WP_REST_Controller {

    public function __construct() {
        $this->namespace = 'wp/v2';
        $this->rest_base = 'dock-items';
    }

    /**
     * Register the routes for the objects of the controller.
     */
    public function register_routes() {

        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_items' ],
                    'permission_callback' => [ $this, 'get_items_permissions_check' ],
                    'args'                => [],
                ],
            ]
        );
    }

    /**
     * Get a collection of items
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_Error|WP_REST_Response
     */
    public function get_items( $request ) {

        $items = [];

        if ( have_rows( 'dock_items', 'option' ) ) {
            while ( have_rows( 'dock_items', 'option' ) ) {
                the_row();
                $dock_title = get_sub_field( 'dock_title' );
                $first_char = substr( $dock_title, 0, 1 );
                $dock_icon  = get_sub_field( 'dock_icon' );
                $icon_url   = wp_get_attachment_image_src( $dock_icon, 'full' );
                $dock_url   = get_sub_field( 'dock_url' );

                $args = [
                    'id'         => sanitize_title( $dock_title ),
                    'title'      => $dock_title,
                    'url'        => $dock_url,
                    'icon_url'   => $icon_url[0],
                    'first_char' => $first_char,
                ];

                $items[] = $args;
            }
        }

        $data = [];
        foreach ( $items as $item ) {
            $itemdata = $this->prepare_item_for_response( $item, $request );

            if ( null !== $itemdata ) {
                $data[ $item['id'] ] = $this->prepare_response_for_collection( $itemdata );
            }
        }

        return new WP_REST_Response( $data, 200 );
    }

    /**
     * Prepare the item for the REST response
     *
     * @param mixed $item WordPress representation of the item.
     * @param WP_REST_Request $request Request object.
     *
     * @return mixed
     */
    public function prepare_item_for_response( $item, $request ) {
        return [
            'title'      => $item['title'],
            'url'        => $item['url'],
            'first_char' => $item['first_char'],
            'icon_url'   => $item['icon_url'],
        ];
    }

    /**
     * Check if a given request has access to get a specific item
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_Error|bool
     */
    public function get_item_permissions_check( $request ) {
        return $this->get_items_permissions_check( $request );
    }

    /**
     * Check if a given request has access to get items
     *
     * @param WP_REST_Request $request Full data about the request.
     *
     * @return WP_Error|bool
     */
    public function get_items_permissions_check( $request ) {
        return true;
    }

    /**
     * Get the query params for collections
     *
     * @return array
     */
    public function get_collection_params() {
        return [
            'page'     => [
                'description'       => 'Current page of the collection.',
                'type'              => 'integer',
                'default'           => 1,
                'sanitize_callback' => 'absint',
            ],
            'per_page' => [
                'description'       => 'Maximum number of items to be returned in result set.',
                'type'              => 'integer',
                'default'           => 10,
                'sanitize_callback' => 'absint',
            ],
            'search'   => [
                'description'       => 'Limit results to those matching a string.',
                'type'              => 'string',
                'sanitize_callback' => 'sanitize_text_field',
            ],
        ];
    }
}

add_action( 'rest_api_init', function () {
    $items = new Dock_api();

    return $items->register_routes();
} );
