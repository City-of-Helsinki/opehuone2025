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

function get_top_monthly_posts($limit = 5) {
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

