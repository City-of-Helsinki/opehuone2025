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
<div class="sidebar-box sidebar-box--engel-light side-links-list-box">
	<h3 class="sidebar-box__sub-title">Oikopolut</h3>

	<ul class="side-links-list" id="user-custom-side-links-list">
		<?php
		foreach ( $own_links['added_custom_links'] as $row ) {
			?>
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
			'name'  => esc_html__( 'Yhteiset linkit', 'helsinki-universal' ),
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
				<h2 class="sidebar-box__sub-title sidebar-box__sub-title--margin-top">
					<?php echo esc_html( $link_list['name'] ); ?>
				</h2>
				<?php
			}
			?>
			<ul class="side-links-list">
				<?php
				while ( $query->have_posts() ) {
					$query->the_post();

					$link_array = get_field( 'link' );

					// Check is this link is removed by user
					if ( in_array( $link_array['url'], $own_links['removed_default_urls'] ) ) {
						continue;
					}

					?>
					<li class="side-links-list__item">
						<a href="<?php echo esc_url( $link_array['url'] ); ?>"
						   class="side-links-list__link"
						   target="_blank">
							<?php echo esc_html( $link_array['title'] ); ?>
						</a>
						<button class="side-links-list__remove-btn side-links-list__remove-btn--default"
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
		<div class="side-links-list__own-links-functions">
			<button class="side-links-list__edit-link" id="own-links-modify">
				<span><?php esc_html_e( 'Muokkaa ja lisää', 'helsinki-universal' ); ?></span><?php Helpers\the_svg( 'icons/arrow-right-lg' ); ?>
			</button>

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
					<button class="side-links-list__reset-btn">
						<?php esc_html_e( 'Palauta alkuperäiset linkit', 'helsinki-universal' ); ?>
					</button>
					<button
						class="side-links-list__reset-btn side-links-list__reset-btn--final side-links-list__reset-btn--final--hidden">
						<?php esc_html_e( 'VAROITUS!! Painamalla tästä kaikki luomasi linkit poistetaan ja kaikki alkuperäiset linkit palautetaan. Sivu latautuu automaattisesti uudelleen.', 'helsinki-universal' ); ?>
					</button>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>
