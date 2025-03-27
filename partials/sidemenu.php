<?php
$current_id = get_the_ID();

// Selvitä koko hierarkia nykyisestä sivusta ylöspäin
$ancestors = get_post_ancestors( $current_id );
$ancestors = array_reverse( $ancestors ); // niin että top-level on ensimmäisenä

// Varmistetaan että sivu on vähintään kolmannella tasolla (eli on olemassa top, mid, current)
if ( count( $ancestors ) < 2 ) {
	echo '<p class="error">Sivun hierarkia ei ole riittävän syvä – valikkoa ei voida näyttää.</p>';

	return;
}

// Haetaan se sivu, joka on 1. tason jälkeen ketjussa – eli "top-level parentin" lapsi
$target_parent_id = $ancestors[1];

// Varmistetaan, että tuo sivu on olemassa
if ( ! get_post( $target_parent_id ) ) {
	echo '<p class="error">Valikon muodostaminen epäonnistui – sivua ei löytynyt.</p>';

	return;
}

// Tulostetaan sen alasivut
$args = [
	'title_li'    => '',
	'sort_column' => 'menu_order',
	'order'       => 'asc',
	'child_of'    => $target_parent_id,
	'depth'       => 2,
	'walker'      => new BEM_Page_Walker(),
];

?>
<nav aria-label="<?php esc_attr_e( 'Sivuvalikko', 'helsinki-universal' ); ?>">
	<ul class="sidemenu-nav-lvl-1 sidemenu-nav-lvl" id="sidebar-nav">
		<?php wp_list_pages( $args ); ?>
	</ul>
</nav>
