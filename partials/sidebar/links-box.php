<?php
use Opehuone\Helpers;
use Opehuone\Utils;

$current_user = wp_get_current_user();
$user_id      = $current_user->ID;

$own_links = User_settings::get_user_own_links( $user_id );

// Fallback to empty set if no data
if ( ! is_array( $own_links ) ) {
	$own_links = [
		'removed_default_urls' => array(),
		'added_custom_links'   => array(),
	];
}

// Get pre-selected default term based on oppiaste-checker value or fallback to ACF options
$user_term_ids = (array) ( Oppiaste_checker::get_oppiaste_options_term_value() ?: array() );
$user_term_ids = array_values( array_filter( array_map( 'intval', $user_term_ids ) ) );

// ACF options ONLY if user has no terms
$default_term_ids = array();
if ( empty( $user_term_ids ) ) {
	$raw = get_field( 'oppiaste_term_default', 'option' );
	$raw = is_array( $raw ) ? $raw : ( $raw ? [ $raw ] : array() );
	foreach ( $raw as $t ) {
		$default_term_ids[] = ( $t instanceof WP_Term ) ? (int) $t->term_id : (int) $t;
	}
	$default_term_ids = array_values( array_filter( $default_term_ids ) );
}

$link_terms = ! empty( $user_term_ids ) ? $user_term_ids : $default_term_ids;

$all_links = array();

// Default query
if ( ! empty( $link_terms ) ) {
	$args = [
		'post_type'      => 'links',
		'posts_per_page' => -1,
		'tax_query'      => [
			[
				'taxonomy'         => 'cornerlabels',
				'field'            => 'term_id',
				'terms'            => $link_terms,
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

			$all_links[] = array(
				'title' => $title,
				'url'   => $url,
				'type'  => 'default',
			);
		}
	}
	wp_reset_postdata();
}

// User-added custom links
foreach ( $own_links['added_custom_links'] as $row ) {
	$all_links[] = array(
		'title' => $row['url_name'],
		'url'   => $row['url'],
		'type'  => 'custom',
	);
}

// Order alphabetically
usort( $all_links, function( $a, $b ) {
	return strcasecmp( $a['title'], $b['title'] );
});
?>

<div class="sidebar-box sidebar-box--engel-light side-links-list-box">
	<h3 class="sidebar-box__sub-title">Omat pikalinkit</h3>

	<ul class="side-links-list">
		<?php foreach ( $all_links as $link ) : ?>
			<li class="side-links-list__item">
				<a href="<?php echo esc_url( $link['url'] ); ?>"
				   class="side-links-list__link"
				   target="_blank">
					<?php echo esc_html( $link['title'] ); ?>
				</a>
				<?php if ( $link['type'] === 'default' ) : ?>
					<button class="side-links-list__remove-btn side-links-list__remove-btn--default"
							aria-label="<?php esc_html_e( 'Poista tämä linkki', 'helsinki-universal' ); ?>"
							data-link-url="<?php echo esc_url( $link['url'] ); ?>">
						<?php Helpers\the_svg( 'icons/cross-circle-fill' ); ?>
					</button>
				<?php else : ?>
					<button class="side-links-list__remove-btn side-links-list__remove-btn--custom"
							aria-label="<?php esc_html_e( 'Poista tämä linkki', 'helsinki-universal' ); ?>"
							data-custom-link-name="<?php echo esc_attr( $link['title'] ); ?>"
							data-custom-link-url="<?php echo esc_url( $link['url'] ); ?>">
						<?php Helpers\the_svg( 'icons/cross-circle-fill' ); ?>
					</button>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
	</ul>

	<?php if ( is_user_logged_in() ) : ?>
		<div class="side-links-list__own-links-functions">
			<div class="side-links-list__form-wrapper">
				<form class="side-links-list__form" id="own-links__add-new-form">
					<fieldset class="own-links__add-new-form__fieldset">
						<legend class="own-links__add-new-form__legend">
							<?php esc_html_e( 'Voit lisätä listaan omia linkkejä.', 'helsinki-universal' ); ?>
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
						<div class="own-links__add-new-form-notifications"></div>
					</fieldset>
				</form>
				<div class="side-links-list__reset-buttons">
					<button
						class="side-links-list__reset-btn side-links-list__reset-btn--final button hds-button--white">
						<?php esc_html_e( 'Palauta alkuperäiset linkit', 'helsinki-universal' ); ?>
					</button>
				</div>
			</div>

			<button class="side-links-list__edit-link" id="own-links-modify">
				<span><?php esc_html_e( 'Muokkaa ja lisää', 'helsinki-universal' ); ?></span>
			</button>
		</div>
	<?php endif; ?>
</div>
