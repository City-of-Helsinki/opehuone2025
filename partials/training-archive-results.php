<?php

use function \Opehuone\TemplateFunctions\get_training_posts_query;

$trainings_query = get_training_posts_query();
?>

<span class="training-archive__number-of-posts" aria-live="polite">
	<span id="training-archive-number-of-posts"><?php echo esc_html($trainings_query->found_posts); ?></span> <?php esc_html_e('hakutulosta', 'helsinki-universal'); ?>
</span>

<div class="b-training-row" id="training-archive-results">
    <?php if ( $trainings_query->have_posts() ) : ?>
        <?php while ( $trainings_query->have_posts() ) : $trainings_query->the_post(); ?>
            <?php
            $block_args = [
                'url'            => get_the_permalink(),
                'title'          => get_the_title(),
                'type'           => get_post_meta( get_the_ID(), 'training_type', true ),
                'theme'          => get_post_meta( get_the_ID(), 'training_theme_color', true ),
                'start_datetime' => get_post_meta( get_the_ID(), 'training_start_datetime', true ),
                'end_datetime'   => get_post_meta( get_the_ID(), 'training_end_datetime', true ),
                'excerpt'        => get_the_excerpt(),
                'categories'     => get_the_terms( get_the_ID(), 'training_theme' ),
            ];
            get_template_part( 'partials/template-blocks/b-training-post', '', $block_args );
            ?>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
</div>
