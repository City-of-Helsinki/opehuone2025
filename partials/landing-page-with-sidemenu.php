<?php

use function Opehuone\TemplateFunctions\displayBannerWavelineSvg;

$theme_color = get_field('theme_color');
$theme_image = get_field('theme_image');
$header_sve = get_field('header_sve');
$quick_links_header = get_field('quick_links_header');
$landing_links_header = get_field('links_header');
?>

<div class="hero has-default-style has-koros landing-page <?php echo !empty($theme_color) ? 'theme__' . esc_attr($theme_color) : ''; ?>">
	<div class="hds-container hero__container">
		<?php get_template_part( 'partials/breadcrumbs' ); ?>
		<div class="hero__content">		
			<h1 class="hero__title"><?php echo get_the_title(); ?></h1>
			<?php if( !empty( $header_sve ) ): ?>
				<h2 class="hero__title__sve"><?php echo esc_attr($header_sve) ?></h2>
			<?php endif;
			if( !empty( $theme_image ) ): ?>
				<img src="<?php echo esc_url($theme_image['sizes']['medium']); ?>" alt="<?php echo esc_attr($theme_image['alt'] ?: 'hero-image'); ?>" />
			<?php endif; ?>
		</div>
	</div>
	<?php displayBannerWavelineSvg(); ?>
</div>


<div class="opehuone-content-container">
	<div class="opehuone-grid">
		<main>
			<div class="quick-links-container <?php echo !empty($theme_color) ? 'theme__' . esc_attr($theme_color) : ''; ?>">
			<?php if ( !empty( $quick_links_header ) ): ?>
				<h3 class="quick-links-header">
					<?php echo esc_html( $quick_links_header ); ?>
				</h3>
			<?php endif; ?>
				<?php get_template_part( 'partials/landing-quick-links' ); ?>
			</div>
			<?php
			the_post_thumbnail( 'large', [ 'class' => 'single-post__featured-image' ] );		
			the_content();
			?>
		</main>
		<aside class="sidebar-boxes">
			<?php
			get_template_part( 'partials/empty' );
			if ( !empty( $landing_links_header ) ):
				get_template_part( 'partials/sidebar/landing-links-box' );
			endif;
			get_template_part( 'partials/sidebar/landing-latest-news' );
			?>
		</aside>
	</div>
	<?php get_template_part( 'partials/empty' ); ?>
</div>

