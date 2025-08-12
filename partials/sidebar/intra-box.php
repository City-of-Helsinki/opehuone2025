<?php
$intrabox_title = get_field('intrabox_title','options');
$intrabox_content = get_field('intrabox_content','options');

if( $intrabox_title || $intrabox_content ):

    $bgimage = get_field('intrabox_bgimage','options');
?>

<div class="sidebar-box sidebar-box--suomenlinna-light" style="background-image: url(<?php echo esc_url($bgimage); ?>)">
	<h3 class="sidebar-box__sub-title">
		<?php echo esc_html( $intrabox_title ); ?>
	</h3>
	<div class="sidebar-box__sub-content">
		<?php echo $intrabox_content; ?>
	</div>
	<div class="sidebar-box__sub-links">
		<?php if( have_rows('intrabox_links','options') ): ?>
			<ul>
			<?php while( have_rows('intrabox_links','options') ): the_row();
				$link = get_sub_field('intrabox_link','options');

				if( $link ):
					$link_url = $link['url'];
					$link_title = $link['title'];
					$link_target = $link['target'] ? $link['target'] : '_self';
					?>
					<li><a class="link" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?><svg width="23" height="18" viewBox="0 0 23 18" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M13.6666 0.332031L11.6666 2.33203L16.9999 7.66536H0.333252V10.332H16.9999L11.6666 15.6654L13.6666 17.6654L22.3333 8.9987L13.6666 0.332031Z" fill="black"></path></svg></a></li>
				<?php endif; ?>
			<?php endwhile; ?>
			</ul>
		<?php endif; ?>
	</div>
	<div class="sidebar-box__sub-button">
		<?php
		$link = get_field('intrabox_loginbtn','options');
		if( $link ):
			$link_url = $link['url'];
			$link_title = $link['title'];
			$link_target = $link['target'] ? $link['target'] : '_self';
			?>
			<a class="button" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>"><?php echo esc_html( $link_title ); ?><svg class="icon mask-icon icon--link-external hds-icon--link-external inline-icon" viewBox="0 0 24 24" aria-label="(Linkki johtaa ulkoiseen palveluun)" tabindex="-1" role="img"></svg></a>
		<?php endif; ?>
	</div>
</div>

<?php endif; ?>
