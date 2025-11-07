<?php

namespace Opehuone\TemplateFunctions;

use WP_Query;
use function \Opehuone\Utils\get_user_favs;

/**
 * @return void
 */
function displayBannerWaveLineSvg(): void {
    echo '<div class="hds-koros hds-koros--basic hds-koros--flip-horizontal">
        <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="100%" height="42">
            <defs>
                <pattern id="koros_basic-page_hero" x="0" y="0" width="53" height="42"
                         patternUnits="userSpaceOnUse">
                    <path transform="scale(2.65)" d="M0,800h20V0c-4.9,0-5,2.6-9.9,2.6S5,0,0,0V800z"></path>
                </pattern>
            </defs>
            <rect fill="url(#koros_basic-page_hero)" width="100%" height="42"></rect>
        </svg>
    </div>';
}

function fetch_wikipedia_featured_articles(): void {
    $cache_key = 'wikipedia_most_read_articles';
    $articles = get_transient($cache_key);

    if ( $articles === false ) {
        $date = date('Y/m/d' );
        $url = "https://fi.wikipedia.org/api/rest_v1/feed/featured/{$date}";

        $response = wp_remote_get( $url, array(
            'timeout' => 3
        ) );

        if ( is_wp_error( $response ) ) {
            echo '<i>'. esc_html('Virhe haettaessa artikkeleita', 'helsinki-universal')  .'</i>';
            return;
        }

        $data = json_decode( wp_remote_retrieve_body( $response ), true );

        $articles = [];
        if (! empty( $data['mostread']['articles'] ) ) {
            $top_results = array_slice( $data['mostread']['articles'], 0, 3 );
            foreach ( $top_results as $item ) {
                $articles[] = [
                    'title' => sanitize_text_field( $item['titles']['normalized'] ),
                    'url'   => esc_url_raw( $item['content_urls']['desktop']['page'] ),
                    'extract' => sanitize_text_field( $item['extract'] )
                ];
            }
        }

        // Only cache articles if they exist
        if ( count( $articles ) > 0 ) {
            set_transient( $cache_key, $articles, 2 * HOUR_IN_SECONDS );
        }
    }

    if ( ! empty( $articles ) ) {
        echo '<ul class="break-corner-box__wikipedia-list">';
        foreach ( $articles as $article ) {
            echo '<li>';
            echo '<a href="' . esc_url( $article['url'] ) . '" target="_blank">'
                . esc_html( $article['title'] ) . '</a>';
            if ( ! empty( $article['extract'] ) ) {
                echo '<p class="break-corner-box__wikipedia-extract">' . esc_html( $article['extract'] ) . '</p>';
            }
            echo '</li>';
        }
        echo '</ul>';
    } else {
        echo '<i>'. esc_html('Ei löytynyt artikkeleita', 'helsinki-universal') .'</i>';
    }
}

function get_top_monthly_posts($limit = 5): WP_Query {
    $current_month = date('Y-m');

    $args = [
        'post_type' => 'post',
        'posts_per_page' => $limit,
        'ignore_sticky_posts' => 1,
        'post__not_in'        => [ get_the_ID() ],
        'meta_key' => 'monthly_views_count',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
        'meta_query' => [
            [
                'key' => 'monthly_views_timestamp',
                'value' => $current_month,
                'compare' => '='
            ]
        ]
    ];

    return new WP_Query( $args );
}

function get_top_parent_page_title( $post_id = null ): ?string {
    $post_id = $post_id ?: get_the_ID();
    $ancestors = get_post_ancestors( $post_id );
    $top_parent = $ancestors ? end( $ancestors ) : $post_id;

    return $top_parent ? get_the_title( $top_parent ) : null;
}

// Function which displays the bookmark icon
function get_favorite_article_button(): void {
    $user_favs = get_user_favs();
    $block_is_pinned = in_array( get_the_ID(), $user_favs );

    $pinner_aria = $block_is_pinned ? 'Poista sivu kirjanmerkeistä' : 'Lisää sivu kirjanmerkiksi';
    $button_action = $block_is_pinned ? 'favs_remove' : 'favs_add';

    get_template_part('partials/components/pin-favorite-button', null, array(
        'block_is_pinned' => $block_is_pinned,
        'pinner_aria' => $pinner_aria,
        'button_action' => $button_action,
    ));
}

function get_training_posts_query( $isAjax = false ): WP_Query {
    $filters = ['cornerlabels', 'training_theme'];

    $args = [
        'post_type'      => 'training',
        'posts_per_page' => 15,
        'post_status'    => 'publish',
        'meta_key'       => 'training_start_datetime', // Define the meta key for ordering
        'orderby'        => 'meta_value', // Order by meta value
        'order'          => 'ASC', // Order in ascending order
        'meta_query'     => [
            [
                'key'     => 'training_end_datetime', // Target the correct meta field
                'value'   => current_time( 'Y-m-d\TH:i:s' ), // Get the current date and time in WordPress timezone
                'compare' => '>=', // Only include posts where the date is in the future
                'type'    => 'DATETIME', // Ensure proper comparison as a date-time value
            ],
        ],
    ];

    $tax_query = [];

    foreach( $filters as $filter ) {
        $values = get_URL_parameter_values($filter, $isAjax);

        if ( ! empty( $values ) ) {
            $tax_query[] = [
                'taxonomy' => $filter,
                'field'    => 'term_id',
                'terms'    => $values,
            ];
        }
    }

    $args['tax_query'] = $tax_query;

    $offset = isset( $_POST['offset'] ) ? intval( $_POST['offset'] ) : false;

    // If we have offset (clicking the load more button), set it to the query args
    if ( ! empty( $offset) ) {
        $args['offset'] = $offset;
    }

    // Apply tax_query if any filters are set
    if ( ! empty( $tax_query ) ) {
        // If both filters are set, use 'AND' to require both terms
        if ( count( $tax_query ) > 1 ) {
            $args['tax_query'] = array_merge( [ 'relation' => 'AND' ], $tax_query );
        } else {
            $args['tax_query'] = $tax_query;
        }
    }

    return new WP_Query( $args );
}

/**
 * @param string $filter
 * @param mixed $isAjax
 * @return array|mixed
 */
function get_URL_parameter_values(string $filter, mixed $isAjax): mixed {
    $values = $_GET[ $filter ] ?? [];

    if ( $isAjax ) {
        $values = $_POST[ $filter ] ?? [];
    }

    if ( ! empty( $values ) ) {
        $flat_values = [];
        foreach ( ( array ) $values as $value ) {
            $flat_values = array_merge( $flat_values, explode(',', $value ) );
        }
        $values = array_map( 'intval', $flat_values );
    }
    return $values;
}

function display_time_until_holidays(): void {
    $holidays = [
        'autumn'   => 'Aikaa syyslomaan',
        'christmas'=> 'Aikaa joululomaan',
        'winter'   => 'Aikaa talvilomaan',
        'summer'   => 'Aikaa kesälomaan',
    ];

    echo '<div class="profile-opener-dropdown__holiday">';
    foreach ( $holidays as $season => $label ) {
        $field_name = 'show_until_' . $season;
        $show = get_field( $field_name, 'option' );

        if ( ! $show ) {
            continue;
        }

        $countdown = \Time_until::get_days_until_string( $season );

        if ( ! $countdown ) {
            continue;
        }

        if ( intval( $countdown ) < 0 ) {
            update_field( $field_name, false, 'option' );
            continue;
        }

        echo '<span class="profile-opener-dropdown__holiday-countdown">' . esc_html( $label ) . ': ' . esc_html( $countdown ) . '</span>';
    }
    echo '</div>';
}


/**
 * @return array
 */
function get_user_cornerlabels_with_added_default_value(): array {
    $cornerlabels = \Opehuone_user_settings_reader::get_user_settings_key('cornerlabels');

    if ( ! is_array( $cornerlabels ) ) {
        $cornerlabels = $cornerlabels ? [(string) $cornerlabels] : [];
    }

    $default_term_id = (string) get_field('oppiaste_term_default', 'option');

    if ($default_term_id !== '') {
        $cornerlabels[] = $default_term_id;
        $cornerlabels = array_unique($cornerlabels);
    }

    return $cornerlabels;
}



/**
 * Function that loops through sticky and regular posts and displays the posts
 * This function is used on the front page, both for the template and for the AJAX functionality that updates the results
 *
 * @param int $sticky_count
 * @param WP_Query $sticky_query
 * @param mixed $user_favs
 * @param mixed $remaining
 * @param WP_Query $regular_query
 * @return void
 */
function display_sticky_and_regular_posts( int $sticky_count, WP_Query $sticky_query, mixed $user_favs, mixed $remaining, WP_Query $regular_query ): void {
    if ( $sticky_count > 0 ) {
        while ( $sticky_query->have_posts() ) {
            $sticky_query->the_post();

            $block_args = [
                'post_id' => get_the_ID(),
                'title' => get_the_title(),
                'url' => get_the_permalink(),
                'media_id' => get_post_thumbnail_id(),
                'excerpt' => get_the_excerpt(),
                'is_sticky' => true,
                'categories' => get_the_category(),
                'date' => get_the_date(),
                'is_pinned' => in_array( get_the_ID(), $user_favs, false ),
            ];

            get_template_part('partials/template-blocks/b-post', null, $block_args );
            wp_reset_postdata();
        }
    }

    // Then regular posts
    if ( $remaining > 0 && $regular_query->have_posts() ) {
        while ( $regular_query->have_posts() ) {
            $regular_query->the_post();

            $block_args = [
                'post_id' => get_the_ID(),
                'title' => get_the_title(),
                'url' => get_the_permalink(),
                'media_id' => get_post_thumbnail_id(),
                'excerpt' => get_the_excerpt(),
                'is_sticky' => false,
                'categories' => get_the_category(),
                'date' => get_the_date(),
                'is_pinned' => in_array( get_the_ID(), $user_favs, false ),
            ];

            get_template_part('partials/template-blocks/b-post', null, $block_args );
            wp_reset_postdata();
        }
    }
}

/**
 * Fetch all cornerlabels, but filter out the default value (Kaikille yhteinen)
 *
 * @return array
 */
function get_cornerlabels_without_default_value(): array {
    $terms = get_terms( [
        'taxonomy'   => 'cornerlabels',
        'hide_empty' => false,
    ] );

    if ( is_wp_error( $terms ) ) {
        return [];
    }

    // Get "Kaikille yhteinen" term id from Opehuone settings ACF field
    $default_term_id = get_field( 'oppiaste_term_default', 'option' );

    if ( empty( $default_term_id) ) {
        return $terms;
    }

    // Remove the term "Kaikille yhteinen" and return the cornerlabels
    return array_filter($terms, function($term) use ($default_term_id) {
        return $term->term_id !== $default_term_id;
    });
}

/**
 * Generate a random colored thumbnail for post cards that have no thumbnail
 *
 * @return string
 */
function get_custom_card_thumbnail(): string {
    $color_variations = [
        'tram',
        'coat-of-arms',
        'suomenlinna',
        'copper',
        'summer',
        'engel',
        'metro'
    ];

    $random_color = $color_variations[ array_rand( $color_variations ) ];

    return sprintf(
        '<div class="b-post__custom-thumbnail b-post__custom-thumbnail-%s">%s</div>',
        esc_attr( $random_color ),
        helsinki_get_svg_logo()
    );
}

function get_post_archive_query( $isAjax = false ): WP_Query {
    $filters = [
        'cornerlabels',
        'category',
        'post_theme'
    ];

    $tax_query = [];

    foreach ( $filters as $filter ) {
        $values = get_URL_parameter_values($filter, $isAjax);
        // Sanitize term IDs (convert to integers)
        if ( ! empty( $values ) ) {
            $tax_query[] = [
                'taxonomy' => $filter,
                'field'    => 'term_id',
                'terms'    => $values,
            ];
        }
    }

    $query_args = [
        'post_type'      => 'post',
        'posts_per_page' => 15,
        'post_status'    => 'publish',
        'ignore_sticky_posts' => true
    ];

    $offset = isset( $_POST['offset'] ) ? intval( $_POST['offset'] ) : false;

    // If we have offset (clicking the load more button), set it to the query args
    if ( ! empty( $offset) ) {
        $query_args['offset'] = $offset;
    }

    // Apply tax_query if any filters are set
    if ( ! empty( $tax_query ) ) {
        // If both filters are set, use 'AND' to require both terms
        if ( count( $tax_query ) > 1 ) {
            $query_args['tax_query'] = array_merge( [ 'relation' => 'AND' ], $tax_query );
        } else {
            $query_args['tax_query'] = $tax_query;
        }
    }

    return new \WP_Query( $query_args );
}

function display_archive_multi_select_filters( $filters ): void {
    foreach ( $filters as $filter ) {
        $default_label = esc_html('Valitse ' . strtolower($filter['name']), 'helsinki-universal');
        ?>
        <div class="posts-archive__single-filter">
            <label for="posts-archive-<?php echo esc_attr( $filter['taxonomy'] ); ?>"
                   class="posts-archive__filter-label">
                <?php echo esc_html( $filter['name'] ); ?>
            </label>
            <div class="posts-archive__select-filter-wrapper">
                <button type="button" class="checkbox-filter__filter-btn" aria-expanded="false"
                        aria-label="<?php esc_attr_e( 'Näytä valinnat', 'helsinki-universal' ); ?>"
                        data-original-label="<?php echo $default_label; ?>"
                >
                    <?php echo $default_label; ?>
                </button>
                <div class="checkbox-filter__filter-dropdown">
                    <div class="checkbox-filter__checkboxes-wrapper">
                        <?php
                        $terms = get_terms( [
                            'taxonomy'   => $filter['taxonomy'],
                            'hide_empty' => true,
                        ] );

                        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
                            foreach ( $terms as $term ) {
                                ?>
                                <label class="checkbox-filter__checkbox-label">
                                    <input type="checkbox" class="checkbox-filter__checkbox-input"
                                           name="<?php echo esc_attr( $filter['taxonomy'] ); ?>[]"
                                           value="<?php echo esc_attr( $term->term_id ); ?>">
                                    <?php echo esc_html( $term->name ); ?>
                                </label>
                                <?php
                            }
                        }
                        ?>
                    </div>
                    <button type="button" class="checkbox-filter__checkboxes-reset-btn">
                        <?php esc_html_e( 'Tyhjennä valinnat', 'helsinki-universal' ); ?>
                    </button>
                </div>
            </div>
        </div>
        <?php
    }
}

function display_load_more_button( $found_posts, $offset ): void {
    ?>
    <div class="posts-archive__load-more-wrapper">
	<button class="posts-archive__load-more-btn <?php echo $offset > $found_posts ? 'is-disabled' : ''; ?>"
			data-total-posts="<?php echo esc_attr( $found_posts ); ?>"
			data-posts-offset="<?php echo $offset; ?>">
		<?php esc_html_e( 'Katso lisää', 'helsinki-universal' ); ?>
	</button>
</div>
<?php
}