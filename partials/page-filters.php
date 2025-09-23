<?php

// Get the root page slug and return if it's not 'pedagogiikka'
if ( $post->post_parent ) {

    $root_page_id = end( get_post_ancestors( $post->ID ) );

    if ( get_post_field( 'post_name', $root_page_id ) !== 'pedagogiikka' ) {
        return;
    }
}

// Use Opehuone user setting as a default, if url has cornerlabels parameter, use it instead
$cornerlabels = !empty($_GET['cornerlabels'])
    ? $_GET['cornerlabels']
    : Opehuone_user_settings_reader::get_user_settings_key('cornerlabels');

if ( ! is_array( $cornerlabels ) ) {
    $cornerlabels = explode(',',$cornerlabels);
}

$cornerlabels = array_map('strval', $cornerlabels);
$cornerlabels = array_unique($cornerlabels);

echo '<p>Suodata sisältöä koulutusasteen mukaan</p>';
echo '<form id="front-page-filter-pages" class="front-page-posts-filter" data-target="pages">';
echo '<div class="front-page-posts-filter__checkboxes-row">';

$terms = get_terms( [
    'taxonomy'   => 'cornerlabels',
    'hide_empty' => false, // We want to check manually
] );

// Remove the term "Kaikille yhteinen"
$terms = array_filter($terms, function($term) {
    return $term->slug !== 'yhteiset';
});


if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
    foreach ( $terms as $term ) {

        // Query the posts with term value. Here we handle checkbox state (enabled / disabled) based on posts length
        $query = new WP_Query( [
            'post_type'      => 'page',
            'post_status'    => 'publish',
            'posts_per_page' => 1,
            'post_parent'    => $post->post_parent,
            'tax_query'      => [
                [
                    'taxonomy' => 'cornerlabels',
                    'field'    => 'term_id',
                    'terms'    => $term->term_id,
                ],
            ],
        ] );

        // Check if there are any posts found for the cornerlabel and set the correct string to handle checkbox state
        $disabled = $query->have_posts() ? '' : 'disabled';
        $checked = in_array( $term->term_id, $cornerlabels ) ? ' checked' : '';

        // If there are no posts and the checkbox is disabled, but we have cornerlabels coming from URL parameter, we want to uncheck it
        // For example: You get redirected to a page that has no posts for a specific cornerlabel, although you had that cornerlabel checked previously
        // Here we avoid checked disabled checkboxes
        if ($disabled && $checked) {
            $checked = '';
        }

        ?>
        <label class="front-page-posts-filter__checkbox-label">
            <input type="checkbox" class="front-page-posts-filter__checkbox-input" name="cornerlabels[]" <?php echo $disabled; ?>
                   value="<?php echo esc_attr( $term->term_id ); ?>" <?php echo $checked; ?>>
            <?php echo esc_html( $term->name ); ?>
        </label>
        <?php
        wp_reset_postdata();
    }
}

echo '</div>';
echo '</form>';
?>
