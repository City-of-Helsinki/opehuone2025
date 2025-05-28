<?php

use Opehuone\Helpers;

$rows = get_field('links_list');
$header = get_field('links_header');
$description = get_field('links_description');

?>
<div class="sidebar-box sidebar-box--fog-light side-links-list-box">
	
	<?php if ( !empty( $header ) ) : ?>
		<h3 class="sidebar-box__sub-title">
			<?php echo esc_html( $header ); ?>
		</h3>
	<?php endif;

	if ( !empty( $description ) ) : ?>
		<p class="sidebar-box__description">
			<?php echo esc_html( $description  ); ?>
		</p>
	<?php endif; ?>

    <ul class="side-links-list">
	<?php 
	if ( $rows ) :
        foreach ( $rows as $row ) : 
            $link_url = $row['link_url'];
			$link_target = $row['link_target'];
			$link_title = $row['link_title'];
           	?>
                <li class="side-links-list__item">
                    <a href="<?php echo esc_url( $link_url ); ?>"
                       class="side-links-list__link"
                       target="<?php echo esc_attr( $link_target ?: '_self' ); ?>">
                        <?php echo esc_html( $link_title ); ?>
						<?php Helpers\the_svg( 'icons/arrow-right-lg' ); ?>
                    </a>
                </li>
            <?php
        endforeach; 
	endif; ?>
    </ul>
</div>