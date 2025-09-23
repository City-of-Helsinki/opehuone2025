<?php

// Get the root page slug and return if it's not 'pedagogiikka'
if ( $post->post_parent ) {

    $root_page_id = end( get_post_ancestors( $post->ID ) );

    if ( get_post_field( 'post_name', $root_page_id ) !== 'pedagogiikka' ) {
        return;
    }
}

$cornerlabels = Opehuone_user_settings_reader::get_user_settings_key( 'cornerlabels' );
//$user_selected_cornerlabels = $_POST['cornerlabels'] ?? null;

echo '<p>Suodata sisältöä koulutusasteen mukaan</p>';
echo '<form id="front-page-filter-pages" class="front-page-posts-filter" data-target="pages">';
echo '<div class="front-page-posts-filter__checkboxes-row">';

$terms = get_terms( [
    'taxonomy'   => 'cornerlabels',
    'hide_empty' => false, // We want to check manually
] );

if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
    foreach ( $terms as $term ) {
        // @TODO: Check if the page contains pages with a specific cornerlabel, if not disable the checkbox
        // Check if there are pages under this post with this term
//        $query = new WP_Query( [
//            'post_type'      => 'page',
//            'post_status'    => 'publish',
//            'posts_per_page' => 1,
//            'post_parent'    => $post->ID,
//            'tax_query'      => [
//                [
//                    'taxonomy' => 'cornerlabels',
//                    'field'    => 'term_id',
//                    'terms'    => $term->term_id,
//                ],
//            ],
//        ] );

        // Check if there are any posts found for the cornerlabel and set the correct string to handle checkbox state
//        $disabled = $query->have_posts() ? '' : 'disabled';

        ?>
        <label class="front-page-posts-filter__checkbox-label">
            <input type="checkbox" class="front-page-posts-filter__checkbox-input" name="cornerlabels[]"
                   value="<?php echo esc_attr( $term->term_id ); ?>" <?php echo in_array( $term->term_id, $cornerlabels ) ? ' checked' : ''; ?>>
            <?php echo esc_html( $term->name ); ?>
        </label>
        <?php
        wp_reset_postdata();
    }
}

echo '</div>';
echo '</form>';
?>
