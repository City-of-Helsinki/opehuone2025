<?php

use function Opehuone\TemplateFunctions\displayBannerWavelineSvg;

$page_id = get_the_ID();
$training_page_id   = (int) get_field( 'trainings_page', 'option' );
$is_training_page  = $page_id === $training_page_id;

$theme_color = get_field('theme_color');
$theme_image = get_field('theme_image');
$header_sve = get_field('header_sve');
$quick_links_header = get_field('quick_links_header');
$landing_links_header = get_field('links_header');

$theme_class = $theme_color ? 'theme__' . esc_attr( $theme_color ) : '';
?>

<div class="hero has-default-style has-koros landing-page <?php echo $theme_class ?>">
	<div class="hds-container hero__container">
		<div class="hero__content">
            <div class="hero-text-content">
		    <?php get_template_part( 'partials/breadcrumbs' ); ?>
			<h1 class="hero__title"><?php echo get_the_title(); ?></h1>
			<?php if( !empty( $header_sve ) ): ?>
				<h2 class="hero__title__sve"><?php echo esc_attr($header_sve) ?></h2>
			<?php endif;?>
            </div>
            <?php if( !empty( $theme_image ) ): ?>
				<div class="hero-image-content"><img src="<?php echo esc_url($theme_image['sizes']['medium']); ?>" alt="<?php echo esc_attr($theme_image['alt'] ?: 'hero-image'); ?>" /></div>
			<?php endif; ?>
		</div>
	</div>
	<?php displayBannerWavelineSvg(); ?>
</div>


<div class="opehuone-content-container">
	<div class="opehuone-grid">
		<main>
			<div class="quick-links-container <?php echo $theme_class ?>">

            <!-- Quick Links -->
			<?php if ( !empty( $quick_links_header ) ): ?>
				<h3 class="quick-links-header">
					<?php echo esc_html( $quick_links_header ); ?>
				</h3>
			<?php endif; ?>
				<?php get_template_part( 'partials/landing-quick-links' ); ?>
			</div>

            <!-- Post Thumbnail & Content -->
			<?php
                the_post_thumbnail( 'large', [ 'class' => 'single-post__featured-image' ] );
                the_content();
                get_template_part( 'partials/components/landing-page-link-element-box');
            ?>

            <!-- Training archive section -->
            <?php if ( $is_training_page ): ?>
            <?php
                get_template_part( 'partials/empty' );
                get_template_part( 'partials/training-archive-filters' );
                get_template_part( 'partials/empty' );
                get_template_part( 'partials/training-archive-results' );
            endif; ?>
		</main>

        <!-- Sidebar -->
		<aside class="sidebar-boxes">
			<?php
			if ( !empty( $landing_links_header ) ):
				get_template_part( 'partials/sidebar/landing-links-box' );
			endif;

            if ( $is_training_page ):
                get_template_part( 'partials/sidebar/training-archive-upcoming-registration-deadline' );
            else:
			    get_template_part( 'partials/sidebar/landing-latest-news' );
            endif;
			?>
		</aside>
	</div>
	<?php get_template_part( 'partials/empty' ); ?>
</div>


