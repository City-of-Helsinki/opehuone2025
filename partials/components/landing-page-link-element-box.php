<?php

$page_id = get_the_ID();

// Get fields
$title      = get_field('opehuone_link_element_title', $page_id);
$links      = get_field('opehuone_link_element_pages', $page_id);
$color      = get_field('opehuone_link_element_color', $page_id); // defaults to "silver"

// If there are no links, don't display the element
if ( empty( $links) ) {
    return;
}

?>

<div class="theme__<?php echo esc_attr( $color );?> link-element-box">
    <h2><?php echo esc_html( $title ); ?></h2>
    <ul>
        <?php foreach ( $links as $link_id ): ?>
            <li>
                <a href="<?php echo esc_url( get_permalink( $link_id ) ); ?>">
                    <?php echo esc_html( get_the_title( $link_id ) ); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>