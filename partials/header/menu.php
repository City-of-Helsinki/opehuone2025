<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$subwalker = new Opehuone_Submenu_Walker();
$nav_color = $subwalker->parent_theme_color_class;

// Check for news (=blogs page) hero color
if ( ! is_front_page() && is_home() ) {
	$nav_color = get_field( 'news_hero_color', 'options' );
}
?>
<nav id="main-menu-nav" class="navigation__menu show-for-l <?php echo esc_attr( $nav_color ); ?>" aria-labelledby="main-menu-nav-label">
	<span id="main-menu-nav-label" class="screen-reader-text">
		<?php echo esc_html_x( 'Main menu', 'Label - Nav - Main menu', 'helsinki-universal' ); ?>
	</span>
	<div class="hds-container hds-container--wide">
		<?php echo opehuone_menu('main_menu'); ?>
		<?php echo opehuone_menu('sub_menu'); ?>
	</div>
</nav>
