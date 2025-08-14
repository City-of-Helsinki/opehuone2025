<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<nav id="main-menu-nav" class="navigation__menu show-for-l" aria-labelledby="main-menu-nav-label">
	<span id="main-menu-nav-label" class="screen-reader-text">
		<?php echo esc_html_x( 'Main menu', 'Label - Nav - Main menu', 'helsinki-universal' ); ?>
	</span>
	<div class="hds-container hds-container--wide">
		<?php echo opehuone_menu('main_menu'); ?>
		<?php echo opehuone_menu('sub_menu'); ?>
	</div>
</nav>
