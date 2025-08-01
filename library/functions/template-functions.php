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


function fetchWikipediaFeaturedArticles() {
    $cache_key = 'wikipedia_most_read_articles';
    $articles = get_transient($cache_key);

    if ( $articles === false ) {
        $date = date('Y/m/d' );
        $url = "https://fi.wikipedia.org/api/rest_v1/feed/featured/{$date}";
        $response = wp_remote_get( $url, array(
            'timeout' => 3
        ) );

        if ( is_wp_error( $response ) ) {
            echo '<p>'. esc_html_e('Virhe haettaessa artikkeleita', 'helsinki-universal')  .'</p>';
            return;
        }

        $data = json_decode(wp_remote_retrieve_body($response), true);
        $articles = [];

        if (!empty($data['mostread']['articles'])) {
            $top_results = array_slice( $data['mostread']['articles'], 0, 3 );
            foreach ( $top_results as $item ) {
                $articles[] = [
                    'title' => sanitize_text_field( $item['titles']['normalized'] ),
                    'url'   => esc_url_raw( $item['content_urls']['desktop']['page'] ),
                    'extract' => sanitize_text_field( $item['extract'] )
                ];
            }
        }

        set_transient( $cache_key, $articles, 12 * HOUR_IN_SECONDS );
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
        echo '<p>'. esc_html_e('Ei artikkeleita', 'helsinki-universal') .'</p>';
    }
}