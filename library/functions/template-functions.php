<?php

namespace Opehuone\TemplateFunctions;

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

function get_top_monthly_posts($limit = 5): \WP_Query {
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

    return new \WP_Query( $args );
}

function get_top_parent_page_title( $post_id = null ): ?string {
    $post_id = $post_id ?: get_the_ID();
    $ancestors = get_post_ancestors( $post_id );
    $top_parent = $ancestors ? end( $ancestors ) : $post_id;

    return $top_parent ? get_the_title( $top_parent ) : null;
}


