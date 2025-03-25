<?php
$post_parent_id       = wp_get_post_parent_id( get_the_ID() ) ?: get_the_ID();
$args                 = [
	'title_li'    => '',
	'sort_column' => 'menu_order',
	'order'       => 'asc',
	'child_of'    => $post_parent_id,
	'depth'       => 3,
	'walker'      => new BEM_Page_Walker(),
];
$back_link_aria_label = sprintf( esc_html__( 'Siirry yläsivulle: %s', 'helsinki-universal' ), get_the_title( $post_parent_id ) );
?>
<nav aria-label="<?php esc_attr_e( 'Sivuvalikko', 'helsinki-universal' ); ?>">
	<ul class="sidemenu-nav-lvl-1 sidemenu-nav-lvl" id="sidebar-nav">
		<?php wp_list_pages( $args ); ?>
	</ul>
</nav>
