<?php
use function \Opehuone\Helpers\the_svg;

// show box only when logged in
if ( ! is_user_logged_in() ) {
	return;
}
?>
<div class="sidebar-box sidebar-box--tram-light user-favs-box">
	<h3 class="sidebar-box__sub-title"><?php esc_html_e( 'Omat tallennetut sisällöt', 'helsinki-universal' ); ?></h3>
	<?php
	$user_favs = get_user_meta( get_current_user_id(), 'opehuone_favs', true );

	if ( ! $user_favs ) {
		$user_favs = [];
	}

	if ( count( $user_favs ) === 0 ) {
		?>
		<p class="sidebar-box__placeholder-text">
			<?php esc_html_e( 'Voit tallentaa Omiksi suosikeiksi uutisia ja Opehuoneen sisältösivuja.', 'helsinki-universal' ); ?>
		</p>
        <p class="sidebar-box__placeholder-text">
            <?php esc_html_e( 'Löydät tallenna-napin jokaisen sisältösivun ja uutiskortin oikeasta yläkulmasta.', 'helsinki-universal' ); ?>
        </p>
		<div class="user-favs-list__sub-buttons">
			<button disabled class="user-favs-list__sub-buttons__button edit-favs-button" id="own-favorites-edit">
				<span><?php esc_html_e( 'Ei tallennettuja sisältöjä', 'helsinki-universal' ); ?></span>
				<div class="button-svg"><?php the_svg( 'icons/settings' ); ?></div>
			</button>
		</div>
		<?php
	} else {
		?>
		<ul class="user-favs-list">
			<?php
			// Loop through favs
			foreach ( $user_favs as $fav_post_id ) {
				$category_name = esc_html__( 'Sivut', 'helsinki-universal' );

				if ( get_post_type( $fav_post_id ) === 'post' ) {
					$category_name = esc_html__( 'Uutiset', 'helsinki-universal' );
				}
				?>
				<li class="user-favs-list__item" data-id="<?php echo esc_attr( $fav_post_id ); ?>">
					<button class="remove-fav-button" data-action="favs_remove" data-post-id="<?php echo esc_attr( $fav_post_id ); ?>" aria-label="<?php esc_attr_e( 'Poista sivu kirjanmerkeistä', 'helsinki-universal' ); ?>">
						<?php the_svg( 'icons/cross-circle-fill' ); ?>
					</button>
					<a href="<?php echo esc_url( get_permalink( $fav_post_id ) ); ?>" class="user-favs-list__link">
						<span
							class="user-favs-list__link-category"><?php echo esc_html( $category_name ); ?></span>
						<span
							class="user-favs-list__link-title"><?php echo esc_html( get_the_title( $fav_post_id ) ); ?></span>
					</a>
				</li>
				<?php
			}
			?>
		</ul>
		<div class="user-favs-list__sub-buttons">
			<?php if ( count( $user_favs ) > 4 ) : ?>
				<button class="user-favs-list__sub-buttons__button show-all-favs" aria-label="<?php esc_attr_e( 'Näytä kaikki tallennetut sisällöt', 'helsinki-universal' ); ?>" aria-expanded="false" aria-controls="user-favs-list">
					<span><?php esc_html_e( 'Näytä kaikki tallennetut sisällöt', 'helsinki-universal' ); ?></span>
					<div class="button-svg button-svg__up"><?php the_svg( 'icons/angle-up' ); ?></div>
					<div class="button-svg button-svg__down"><?php the_svg( 'icons/angle-down' ); ?></div>
				</button>
			<?php endif; ?>
			<button class="user-favs-list__sub-buttons__button edit-favs-button" id="own-favorites-edit">
				<span><?php esc_html_e( 'Muokkaa', 'helsinki-universal' ); ?></span>
				<div class="button-svg"><?php the_svg( 'icons/settings' ); ?></div>
			</button>
		</div>
		<?php
	}
	?>
</div>
