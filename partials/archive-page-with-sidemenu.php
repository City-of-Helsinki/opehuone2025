<?php

use function \Opehuone\TemplateFunctions\displayBannerWaveLineSvg;

$page_id = isset($args['page_id']) ? absint($args['page_id']) : 0;

if ( ! $page_id ) return;

$page = get_post( $page_id );

if ( ! ( $page && $page->post_status === 'publish' ) ) :
    return;
endif;


setup_postdata( $page );

$theme_color = get_field('theme_color', $page_id);
$theme_image = get_field('theme_image', $page_id);
$header_sve = get_field('header_sve', $page_id);
$quick_links_header = get_field('quick_links_header', $page_id);
?>
		
	<div class="hero has-default-style has-koros landing-page <?php echo !empty($theme_color) ? 'theme__' . esc_attr($theme_color) : ''; ?>">
	<div class="hds-container hero__container">

		<?php get_template_part( 'partials/breadcrumbs', null, ['page' => $page] ); ?>
		
		<div class="hero__content">		
			<h1 class="hero__title"><?php echo get_the_title( $page ); ?></h1>
			<?php if( !empty( $header_sve ) ): ?>
				<h2 class="hero__title__sve"><?php echo esc_attr($header_sve) ?></h2>
			<?php endif;
			if( !empty( $theme_image ) ): ?>
				<img src="<?php echo esc_url($theme_image['sizes']['medium']); ?>" alt="<?php echo esc_attr($theme_image['alt'] ?: 'hero-image'); ?>" />
			<?php endif; ?>
		</div>
	</div>

	<?php displayBannerWaveLineSvg(); ?>
</div>

<div class="opehuone-content-container">
	<div class="opehuone-grid">
		<main>
			<div class="quick-links-container <?php echo !empty($theme_color) ? 'theme__' . esc_attr($theme_color) : ''; ?>">
			<?php if ( !empty( $quick_links_header ) ): ?>
				<h3 class="quick-links-header"><?php echo esc_html( $quick_links_header ); ?></h3>
			<?php endif; ?>
				<?php get_template_part( 'partials/landing-quick-links', null, ['page' => $page] ); ?>
			</div>

			<?php
			echo get_the_post_thumbnail( $page, 'large', [ 'class' => 'single-post__featured-image' ] );
			echo apply_filters( 'the_content', $page->post_content );
			?>
		</main>
		<aside class="sidebar-boxes">
			<?php
			get_template_part( 'partials/empty' );
			get_template_part( 'partials/sidebar/training-archive-upcoming-registration-deadline' );
			?>
		</aside>
	</div>
</div>

<?php
wp_reset_postdata();
?>
