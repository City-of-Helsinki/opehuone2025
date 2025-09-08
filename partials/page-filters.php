<?php

// Get the root page slug and return if it's not 'pedagogiikka'
if ( $post->post_parent ) {

    $root_page_id = end( get_post_ancestors( $post->ID ) );

    if ( get_post_field( 'post_name', $root_page_id ) !== 'pedagogiikka' ) {
        return;
    }
} 

$cornerlabels = Opehuone_user_settings_reader::get_user_settings_key( 'cornerlabels' );

    echo '<p>Suodata sisältöä koulutusasteen mukaan</p>';
    echo '<form id="front-page-filter-pages" class="front-page-posts-filter" data-target="pages">';
    echo '<div class="front-page-posts-filter__checkboxes-row">';

    $terms = get_terms( [
        'taxonomy'   => 'cornerlabels',
        'hide_empty' => true,
    ] );

    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            ?>
            <label class="front-page-posts-filter__checkbox-label">
                <input type="checkbox" class="front-page-posts-filter__checkbox-input" name="cornerlabels[]"
                        value="<?php echo esc_attr( $term->term_id ); ?>" <?php echo in_array( $term->term_id, $cornerlabels ) ? ' checked' : ''; ?>>
                <?php echo esc_html( $term->name ); ?>
            </label>
            <?php
        }
    }

    echo '</div>';
    echo '</form>';

?>
