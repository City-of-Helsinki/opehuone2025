<?php

use Opehuone\Helpers;

$rows = get_field('quick_links');

if ( $rows ) :
	foreach ( $rows as $row ) : 
		$link_icon = $row['quick_link_icon'];
		$link_title = $row['quick_link_title'];
		$link_url = $row['quick_link_url'];
		$link_target = $row['quick_link_target'];
		$link_description = $row['quick_link_description'];

		?>           
		<div class="quick-links-box">
			<div class="quick-links-box__icon">
			<?php
			if ( $link_icon ) : ?>
				<img src="<?php echo esc_url( $link_icon['sizes']['medium']); ?>" alt="<?php echo esc_attr($link_icon['alt'] ?: 'link-icon'); ?>">
			<?php endif; ?>
			</div>
			<div class="quick-links-box__content">
				<a class="quick-links-box__title" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ?: '_self' ); ?>">			
					<?php echo esc_html( $link_title ?: '' ); ?>
					<?php Helpers\the_svg( 'icons/arrow-right-lg' ); ?>
				</a>
				<span class="quick-links-box__description">
					<?php echo esc_html( $link_description ?: '' ); ?>
				</span>
			</div>
		</div>
		<?php
	endforeach; 
endif; ?>
