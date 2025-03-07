<?php
$type = get_post_meta( get_the_ID(), 'training_type', true );

$readable_type = TrainingHelpers::get_training_type( $type );
$svg           = TrainingHelpers::get_training_type_svg( $type );
?>
<div class="icon-detail icon-detail--align-center">
	<?php \Opehuone\Helpers\the_svg( 'icons/' . $svg ); ?>
	<span class="icon-detail__title icon-detail__title--no-margin"><?php echo esc_html( $readable_type ); ?></span>
</div>
