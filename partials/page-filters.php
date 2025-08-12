<?php
$terms = get_terms(
	array(
    'taxonomy' => 'cornerlabels',
    'hide_empty' => false,
));

if (!empty($terms) && !is_wp_error($terms)) {
    echo '<form id="front-page-filter-pages" class="front-page-posts-filter" data-target="pages">';
    echo '<div class="front-page-posts-filter__checkboxes-row">';

    foreach ($terms as $term) {
        $term_id = $term->term_id;
        $term_name = $term->name;

        // Check if the term has any published pages
        $pages = get_posts(
			array(
            'post_type' => 'page',
            'tax_query' => array(
                array(
                    'taxonomy' => 'cornerlabels',
                    'field' => 'term_id',
                    'terms' => $term_id,
				),
			)
		));

        $disabled = empty($pages) ? 'disabled' : '';

        echo '<label class="front-page-posts-filter__checkbox-label">';
        echo '<input type="checkbox" class="front-page-posts-filter__checkbox-input" value="' . esc_attr($term_id) . '" ' . $disabled . '>';
        echo esc_html($term_name);
        echo '</label>';
    }

    echo '</div>';
    echo '</form>';
}
?>
