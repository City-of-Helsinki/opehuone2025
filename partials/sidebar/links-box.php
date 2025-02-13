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
?>
<div class="sidebar-box sidebar-box--engel-light">
	<h3 class="sidebar-box__sub-title">Oikopolut</h3>

	<ul class="front-side__links-list" id="user-custom-links-list">
		<?php
		foreach ( $own_links['added_custom_links'] as $row ) {
			?>
			<li class="front-side__links-list-item">
				<a href="<?php echo esc_url( $row['url'] ); ?>"
				   class="front-side__links-list-link is-highlight-color"
				   target="_blank">
					<?php echo esc_html( $row['url_name'] ); ?>
				</a>
				<button class="front-side__links-list-remove-btn front-side__links-list-remove-btn--custom"
						aria-label="<?php esc_html_e( 'Poista tämä linkki', 'helsinki-universal' ); ?>"
						data-custom-link-name="<?php echo esc_attr( $row['url_name'] ); ?>"
						data-custom-link-url="<?php echo esc_url( $row['url'] ); ?>">
					<?php Helpers\the_svg( 'icons/cross-circle-fill' ); ?>
				</button>
			</li>
			<?php
		}
		?>
	</ul>

	<?php

	$link_lists = [
		[
			'name'  => '',
			'terms' => Oppiaste_checker::get_oppiaste_options_term_value(),
		],
		[
			'name'  => esc_html__( 'Yhteiset', 'helsinki-universal' ),
			'terms' => get_field( 'oppiaste_term_default', 'option' ),
		]
	];

	foreach ( $link_lists as $link_list ) {

		/**
		 * Dont show default "yhteiset" set for non logged
		 */
		if ( ! Utils\user_data_meta_exists() && empty( $link_list['name'] ) ) {
			continue;
		}

		$args = [
			'post_type'      => 'links',
			'posts_per_page' => - 1,
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
			'tax_query'      => [
				[
					'taxonomy' => 'cornerlabels',
					'field'    => 'term_id',
					'terms'    => $link_list['terms'],
				]
			],
		];

		$query = new WP_Query( $args );

		if ( $query->have_posts() ) {
			if ( ! empty( $link_list['name'] ) ) {
				?>
				<h2 class="front-side-column__title">
					<?php echo esc_html( $link_list['name'] ); ?>
				</h2>
				<?php
			}
			?>
			<ul class="front-side__links-list">
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();

					$link_array = get_field( 'link' );

					// Check is this link is removed by user
					if ( in_array( $link_array['url'], $own_links['removed_default_urls'] ) ) {
						continue;
					}

					?>
					<li class="front-side__links-list-item">
						<a href="<?php echo esc_url( $link_array['url'] ); ?>"
						   class="front-side__links-list-link is-highlight-color"
						   target="_blank">
							<?php echo esc_html( $link_array['title'] ); ?>
						</a>
						<button class="front-side__links-list-remove-btn front-side__links-list-remove-btn--default"
								aria-label="<?php esc_html_e( 'Poista tämä linkki', 'helsinki-universal' ); ?>"
								data-link-url="<?php echo esc_url( $link_array['url'] ); ?>">
							<?php Helpers\the_svg( 'icons/cross-circle-fill' ); ?>
						</button>
					</li>
					<?php
				}
				wp_reset_postdata();
				?>
			</ul>
			<?php
		}
	}

	?>
	<?php if ( is_user_logged_in() ) : ?>
		<a href="#" class="front-side__edit-link is-highlight-color" id="own-links-modify">
			<?php esc_html_e( 'Muokkaa', 'helsinki-universal' ); ?><?php Helpers\the_svg( 'icons/rounded-plus' ); ?>
		</a>
		<div id="own-links-functions">
			<form id="own-links__add-new-form">
				<fieldset class="own-links__add-new-form__fieldset">
					<legend class="own-links__add-new-form__legend">
						<?php esc_html_e( 'Voit lisätä listan alkuun omia linkkejä.', 'helsinki-universal' ); ?>
					</legend>
					<div class="own-links__add-new-form__form-group">
						<label for="own-link-name">
							<?php esc_html_e( 'Linkin nimi', 'helsinki-universal' ); ?>
						</label>
						<input id="own-link-name" type="text" class="form-control" autocomplete="own-link-name"
							   aria-required="true">
					</div>

					<div class="own-links__add-new-form__form-group">
						<label for="own-link-url">
							<?php esc_html_e( 'Linkin osoite', 'helsinki-universal' ); ?>
						</label>
						<input id="own-link-url" type="url" class="form-control" autocomplete="own-link-url"
							   aria-required="true">
						<small class="form-text text-muted">
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
			<button class="front-side__links-list-reset-btn">
				<?php esc_html_e( 'Palauta alkuperäiset linkit', 'helsinki-universal' ); ?>
			</button>
			<button class="front-side__links-list-reset-btn front-side__links-list-reset-btn--final">
				<?php esc_html_e( 'VAROITUS!! Painamalla tästä kaikki luomasi linkit poistetaan ja kaikki alkuperäiset linkit palautetaan. Sivu latautuu automaattisesti uudelleen.', 'helsinki-universal' ); ?>
			</button>
		</div>
	<?php endif; ?>
</div>
