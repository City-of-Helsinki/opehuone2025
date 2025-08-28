<?php
use Opehuone\Helpers;
use Opehuone\Utils;

$current_user = wp_get_current_user();
$user_id      = $current_user->ID;

$own_links = User_settings::get_user_own_links( $user_id );

// Fallback to empty set if no data
if ( ! is_array( $own_links ) ) {
	$own_links = [
		'removed_default_urls' => [],
		'added_custom_links'   => [],
	];
}

// 1) Get user-selected term IDs (preferred source)
$user_term_ids = (array) ( Opehuone_user_settings_reader::get_user_settings_key( 'cornerlabels' ) ?: [] );
$user_term_ids = array_values( array_filter( array_map( 'intval', $user_term_ids ) ) );

// 2) Fallback to ACF options ONLY if user has no terms
$default_term_ids = [];
if ( empty( $user_term_ids ) ) {
	$raw = get_field( 'oppiaste_term_default', 'option' ); // may be IDs or WP_Term objects
	$raw = is_array( $raw ) ? $raw : ( $raw ? [ $raw ] : [] );
	foreach ( $raw as $t ) {
		$default_term_ids[] = ( $t instanceof WP_Term ) ? (int) $t->term_id : (int) $t;
	}
	$default_term_ids = array_values( array_filter( $default_term_ids ) );
}

// 3) Decide which terms to use
$link_terms = ! empty( $user_term_ids ) ? $user_term_ids : $default_term_ids;

// 4) If still no terms, skip querying posts entirely
?>
<div class="sidebar-box sidebar-box--engel-light side-links-list-box">
	<h3 class="sidebar-box__sub-title">Omat pikalinkit</h3>

	<ul class="side-links-list">
		<?php
		if ( ! empty( $link_terms ) ) {
			$args = [
				'post_type'      => 'links',
				'posts_per_page' => -1,
				'order'          => 'ASC',
				'orderby'        => 'menu_order',
				'tax_query'      => [
					[
						'taxonomy'         => 'cornerlabels',
						'field'            => 'term_id',
						'terms'            => $link_terms,   // only one source used
						'operator'         => 'IN',
						'include_children' => false,
					],
				],
			];

			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();

					$link_array = get_field( 'link' );
					$url        = $link_array['url']   ?? '';
					$title      = $link_array['title'] ?? '';

					// Skip if user has removed this default link
					if ( in_array( $url, $own_links['removed_default_urls'], true ) ) {
						continue;
					}
					?>
					<li class="side-links-list__item">
						<a href="<?php echo esc_url( $url ); ?>"
						   class="side-links-list__link"
						   target="_blank">
							<?php echo esc_html( $title ); ?>
						</a>
						<button class="side-links-list__remove-btn side-links-list__remove-btn--default"
								aria-label="<?php esc_html_e( 'Poista tämä linkki', 'helsinki-universal' ); ?>"
								data-link-url="<?php echo esc_url( $url ); ?>">
							<?php Helpers\the_svg( 'icons/cross-circle-fill' ); ?>
						</button>
					</li>
					<?php
				}
			}
			wp_reset_postdata();
		}

		// User-added custom links
		foreach ( $own_links['added_custom_links'] as $row ) : ?>
			<li class="side-links-list__item">
				<a href="<?php echo esc_url( $row['url'] ); ?>"
				   class="side-links-list__link"
				   target="_blank">
					<?php echo esc_html( $row['url_name'] ); ?>
				</a>
				<button class="side-links-list__remove-btn side-links-list__remove-btn--custom"
						aria-label="<?php esc_html_e( 'Poista tämä linkki', 'helsinki-universal' ); ?>"
						data-custom-link-name="<?php echo esc_attr( $row['url_name'] ); ?>"
						data-custom-link-url="<?php echo esc_url( $row['url'] ); ?>">
					<?php Helpers\the_svg( 'icons/cross-circle-fill' ); ?>
				</button>
			</li>
		<?php endforeach; ?>
	</ul>
	<?php if ( is_user_logged_in() ) : ?>
		<div class="side-links-list__own-links-functions">
			


			<div class="side-links-list__form-wrapper">
				<form class="side-links-list__form" id="own-links__add-new-form">
					<fieldset class="own-links__add-new-form__fieldset">
						<legend class="own-links__add-new-form__legend">
							<?php esc_html_e( 'Voit lisätä listan alkuun omia linkkejä.', 'helsinki-universal' ); ?>
						</legend>
						<div class="opehuone-form-field-group">
							<label for="own-link-name" class="opehuone-form-label">
								<?php esc_html_e( 'Linkin nimi', 'helsinki-universal' ); ?>
							</label>
							<input id="own-link-name" type="text" class="opehuone-form-control"
								   aria-required="true">
						</div>

						<div class="opehuone-form-field-group">
							<label for="own-link-url" class="opehuone-form-label">
								<?php esc_html_e( 'Linkin osoite', 'helsinki-universal' ); ?>
							</label>
							<input id="own-link-url" type="url" class="opehuone-form-control" autocomplete="url"
								   aria-required="true">
							<small class="opehuone-form-field-description">
								<?php esc_html_e( 'Lisääthän osoitteen muodossa https://linkkisi.osoite', 'helsinki-universal' ); ?>
							</small>
						</div>
						<button type="submit" class="own-links__submit-btn">
							<?php esc_html_e( 'Lisää uusi linkki', 'helsinki-universal' ); ?>
						</button>
						<div class="own-links__add-new-form-notifications">

						</div>
					</fieldset>
				</form>
				<div class="side-links-list__reset-buttons">

					<button
						class="side-links-list__reset-btn side-links-list__reset-btn--final side-links-list__reset-btn--final--hidden">
						<?php esc_html_e( 'VAROITUS!! Painamalla tästä kaikki luomasi linkit poistetaan ja kaikki alkuperäiset linkit palautetaan. Sivu latautuu automaattisesti uudelleen.', 'helsinki-universal' ); ?>
					</button>
				</div>
				
			</div>

			<button class="side-links-list__edit-link" id="own-links-modify">
				<span><?php esc_html_e( 'Muokkaa ja lisää', 'helsinki-universal' ); ?></span>
			</button>

		</div>
	<?php endif; ?>
</div>
