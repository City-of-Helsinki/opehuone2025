<?php

class Services_api extends WP_REST_Controller {

    public function __construct() {
        $this->namespace = 'wp/v2';
        $this->rest_base = 'services';
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

        $query_args = [
            'post_type'      => 'services',
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'posts_per_page' => - 1,
            'lang'           => isset( $_GET['lang'] ) ? $_GET['lang'] : 'fi',
            'tax_query'      => [
                [
                    'taxonomy' => 'cornerlabels',
                    'terms'    => $_GET['cornerlabels'],
                ],
            ],
        ];

        $query = new WP_Query( $query_args );
        $i     = 1;

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                $args = [
                    'post_id'     => get_the_ID(),
                    'id'          => sanitize_title( get_the_title() ),
                    'title'       => get_the_title(),
                    'url'         => get_field( 'service_url' ),
                    'description' => get_field( 'service_description' ),
                    'icon'        => get_field( 'service_icon' ),
                    'is_default'  => $i <= 6 ? true : false,
                ];

                $items[] = $args;
                $i ++;
            }
        }

        wp_reset_postdata();

        $data = [];
        foreach ( $items as $item ) {
            $itemdata = $this->prepare_item_for_response( $item, $request );

            if ( null !== $itemdata ) {
                $data[] = $this->prepare_response_for_collection( $itemdata );
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
            'post_id'     => $item['post_id'],
            'id'          => $item['id'],
            'title'       => $item['title'],
            'url'         => $item['url'],
            'description' => $item['description'],
            'icon_url'    => $item['icon']['url'],
            'icon_alt'    => $item['icon']['alt'],
            'is_default'  => $item['is_default'],
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
    $places = new Services_api();

    return $places->register_routes();
} );
