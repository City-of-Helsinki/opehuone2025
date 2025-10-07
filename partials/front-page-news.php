<?php
use function \Opehuone\TemplateFunctions\display_sticky_and_regular_posts;
use function \Opehuone\TemplateFunctions\get_user_cornerlabels_with_added_default_value;

$cornerlabels = get_user_cornerlabels_with_added_default_value();

$user_favs = \Opehuone\Utils\get_user_favs();
$max_posts = 8;

// Sticky posts
$sticky_posts = get_option( 'sticky_posts' );
$sticky_posts = ! empty( $sticky_posts ) ? array_map('intval', $sticky_posts ) : [];

$sticky_query_args = [
    'post_type'      => 'post',
    'posts_per_page' => $max_posts,
    'post__in'       => $sticky_posts,
    'orderby'        => 'post__in',
];

if ( ! empty( $cornerlabels ) ) {
    $sticky_query_args['tax_query'] = [
        [
            'taxonomy' => 'cornerlabels',
            'field'    => 'term_id',
            'terms'    => $cornerlabels,
        ],
    ];
}

$sticky_query = new WP_Query( $sticky_query_args );
$sticky_count = count( $sticky_query->posts );

// Regular (non-sticky) posts to fill remaining slots
$remaining = max(0, $max_posts - $sticky_count);

$regular_query_args = [
    'post_type'      => 'post',
    'posts_per_page' => $remaining,
    'post__not_in'   => $sticky_posts,
];

if ( ! empty( $cornerlabels ) ) {
    $regular_query_args['tax_query'] = [
        [
            'taxonomy' => 'cornerlabels',
            'field'    => 'term_id',
            'terms'    => $cornerlabels,
        ],
    ];
}

$regular_query = new WP_Query( $regular_query_args );

// Return if nothing is found
if ( $sticky_count + count( $regular_query->posts ) === 0 ) {
    return;
}

?>

<h2 class="front-page-posts-filter__title">
    <?php esc_html_e('Uutiset', 'helsinki-universal'); ?>
</h2>

<?php get_template_part('partials/front-page-filters'); ?>

<div class="b-posts-row">
    <?php
    display_sticky_and_regular_posts($sticky_count, $sticky_query, $user_favs, $remaining, $regular_query);

    wp_reset_postdata();
    ?>
</div>

<div class="b-posts-row__button-wrapper">
    <a href="<?php echo esc_url(get_post_type_archive_link('post')); ?>" class="b-posts-row__more-btn">
        <?php esc_html_e('Katso kaikki uutiset', 'helsinki-universal'); ?>
    </a>
</div>
