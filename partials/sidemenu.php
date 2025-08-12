<?php
$current_id = get_the_ID();
$ancestors = get_post_ancestors($current_id);
$ancestors = array_reverse($ancestors);

// Show error message if hierarchy is not deep enough
if (count($ancestors) < 1) { 
    echo '<p class="error">' . esc_html__('Sivun hierarkia ei ole riittävän syvä - valikkoa ei voida näyttää.') . '</p>';
    return;
}

// Find the second level parent
$second_level_parent_id = null;

if (count($ancestors) >= 2) {
    $second_level_parent_id = $ancestors[1];
} else {
    $second_level_parent_id = $current_id;
}

// Make sure the page exists
$second_level_page = get_post($second_level_parent_id);
if (!$second_level_page) {
    echo '<p class="error">' . esc_html__('Valikon muodostaminen epäonnistui - sivua ei löytynyt.') . '</p>';
    return;
}

// Print the title only
echo '<div class="sidemenu-heading">' . esc_html(get_the_title($second_level_page)) . '</div>';

// Get all the child pages of the current ancestor
$args = [
    'title_li'    => '',
    'sort_column' => 'menu_order',
    'order'       => 'asc',
    'child_of'    => $second_level_parent_id,
    'depth'       => 3,
    'walker'      => new BEM_Page_Walker(),
];
?>

<nav aria-label="<?php esc_attr_e('Sivuvalikko', 'helsinki-universal'); ?>">
    <ul class="sidemenu-nav-lvl-1 sidemenu-nav-lvl" id="sidebar-nav">
        <?php wp_list_pages($args); ?>
    </ul>
</nav>
