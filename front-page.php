<?php

get_header(); ?>
<?php //do_action( 'helsinki_front_page_hero' ); ?>

<?php

use function Opehuone\TemplateFunctions\displayBannerWavelineSvg;

$theme_color = get_field('home_hero_color','options');
$theme_image = get_field('home_hero_image','options');
$header_sub = get_field('home_hero_subtitle', 'options');
$header_sub2 = get_field('home_hero_subtitle_2', 'options');
?>

<div class="hero has-default-style has-koros front-page <?php echo !empty($theme_color) ? 'theme__' . esc_attr($theme_color) : ''; ?>">
	<div class="hds-container hero__container">
		<div class="hero__content">		
			<h1 class="hero__title"><?php echo esc_html( get_field('home_hero_title', 'options') ); ?></h1>
			<?php if( !empty( $header_sub ) ): ?>
				<h2 class="hero__title__sve hero__title__sub"><?php echo esc_html( get_field('home_hero_subtitle', 'options') ); ?></h2>
			<?php endif;
            if( !empty( $header_sub2 ) ): ?>
				<h2 class="hero__title__sve hero__title__sub"><?php echo esc_html( get_field('home_hero_subtitle_2', 'options') ); ?></h2>
			<?php endif;
			if( !empty( $theme_image ) ): ?>
				<img src="<?php echo esc_url($theme_image['sizes']['large']); ?>" alt="<?php echo esc_attr($theme_image['alt'] ?: 'hero-image'); ?>" />
			<?php endif; ?>
		</div>
	</div>
	<?php displayBannerWavelineSvg(); ?>
</div>

<?php get_template_part( 'partials/dynamic-front' );
get_footer();
