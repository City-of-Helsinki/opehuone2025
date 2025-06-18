<?php

use Opehuone\Helpers;

$page = $args['page'] ?? null;

$rows = get_field('quick_links', $page);

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
			if ( !empty($link_icon) ) : ?>
				<img src="<?php echo esc_url( $link_icon['sizes']['medium']); ?>" alt="<?php echo esc_attr($link_icon['alt'] ?: 'link-icon'); ?>">
			<?php endif; ?>
			</div>
			<div class="quick-links-box__content">
				<a class="quick-links-box__title side-links-list__link" 
					href="<?php echo esc_url( $link_url ); ?>" 
					target="<?php echo esc_attr( $link_target ?: '_self' ); ?>">			
					<?php 
					echo esc_html( $link_title );
					if( $link_target === "_self" ) :
						Helpers\the_svg( 'icons/arrow-right-lg' );
					else :
						Helpers\the_svg( 'icons/arrow-top-right' );
					endif;
					?>
				</a>
				<span class="quick-links-box__description">
					<?php echo esc_html( $link_description ?: '' ); ?>
				</span>
			</div>
		</div>
		<?php
	endforeach; 
endif; ?>
