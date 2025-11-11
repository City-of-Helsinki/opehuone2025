<?php
// show box only when logged in
if ( ! is_user_logged_in() ) {
	return;
}
?>
<div class="sidebar-box sidebar-box--tram-light">
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
        <p class="sidebar-box__placeholder-text">
            <?php esc_html_e( 'Voit hallinnoida Omat tallennetut sisällöt -sisältöjä Oma profiili -sivulta.', 'helsinki-universal' ); ?>
        </p>
        <div class="sidebar-box__sub-button">
            <a href="<?php echo get_permalink( get_field( 'favorites_page', 'option' ) ); ?>"
               class="button">
                <?php esc_html_e( 'Katso kaikki tallennetut sisällöt', 'helsinki-universal' ); ?>
            </a>
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
				<li class="user-favs-list__item">
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
        <div class="sidebar-box__sub-button">
            <a href="<?php echo get_permalink( get_field( 'favorites_page', 'option' ) ); ?>"
               class="button">
                <?php esc_html_e( 'Katso kaikki tallennetut sisällöt', 'helsinki-universal' ); ?>
            </a>
        </div>
		<?php
	}
	?>
</div>
