<?php
$theme_color = get_field('theme_color');
$theme_image = get_field('theme_image');
$header_sve = get_field('header_sve');
$quick_links_header = get_field('quick_links_header');
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
	<div class="hds-koros hds-koros--basic hds-koros--flip-horizontal">
		<svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="100%" height="42">
			<defs>
				<pattern id="koros_basic-page_hero" x="0" y="0" width="53" height="42"
							patternUnits="userSpaceOnUse">
					<path transform="scale(2.65)" d="M0,800h20V0c-4.9,0-5,2.6-9.9,2.6S5,0,0,0V800z"></path>
				</pattern>
			</defs>
			<rect fill="url(#koros_basic-page_hero)" width="100%" height="42"></rect>
		</svg>
	</div>
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
			get_template_part( 'partials/sidebar/landing-links-box' );
			get_template_part( 'partials/sidebar/post-latest-news' );
			?>
		</aside>
	</div>
	<?php get_template_part( 'partials/empty' ); ?>
</div>

