<?php

use function Opehuone\Helpers\the_svg;

$link = get_post_meta( get_the_ID(), 'training_registration_url', true );

if ( empty( $link ) ) {
	return;
}
?>
<a href="<?php echo esc_url( $link ); ?>" class="single-post__sidebar-link" target="_blank"
   aria-label="<?php esc_attr_e( 'Ilmoittaudu koulutukseen, linkki aukeaa uuteen välilehteen', 'helsinki-universal' ); ?>">
	<?php esc_html_e( 'Ilmoittaudu koulutukseen', 'helsinki-universal' ); ?>
	<?php the_svg( 'icons/arrow-top-right' ); ?>
</a>
