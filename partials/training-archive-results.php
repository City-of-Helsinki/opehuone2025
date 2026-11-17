<?php

use function \Opehuone\TemplateFunctions\get_training_posts_query;
use function Opehuone\TemplateFunctions\display_load_more_button;

$trainings_query = get_training_posts_query();
?>

<span class="training-archive__number-of-posts" aria-live="polite">
	<span id="archive-number-of-posts"><?php echo esc_html($trainings_query->found_posts); ?></span> <?php esc_html_e('hakutulosta', 'helsinki-universal'); ?>
</span>

<div class="b-training-row" id="training-archive-results">
    <?php if ( $trainings_query->have_posts() ) : ?>
        <?php while ( $trainings_query->have_posts() ) : $trainings_query->the_post(); ?>
            <?php
            // Extra dates (JSON string)
            $extra_dates = [];
            $extra_raw   = get_post_meta( get_the_ID(), 'training_extra_dates', true );

            if ( ! empty( $extra_raw ) ) {
                $decoded = json_decode( $extra_raw, true );

                if ( is_array( $decoded ) ) {
                    foreach ( $decoded as $extra ) {
                        // Skip rows that are missing a start time
                        if ( empty( $extra['start'] ) ) {
                            continue;
                        }

                        $extra_dates[] = [
                            'start' => $extra['start'],
                            'end'   => $extra['end'] ?? '',
                        ];
                    }
                }
            }
            $block_args = [
                'url'            => get_the_permalink(),
                'title'          => get_the_title(),
                'type'           => get_post_meta( get_the_ID(), 'training_type', true ),
                'theme'          => get_post_meta( get_the_ID(), 'training_theme_color', true ),
                'start_datetime' => get_post_meta( get_the_ID(), 'training_start_datetime', true ),
                'end_datetime'   => get_post_meta( get_the_ID(), 'training_end_datetime', true ),
                'extra_dates'    => $extra_dates,
                'excerpt'        => get_the_excerpt(),
                'categories'     => get_the_terms( get_the_ID(), 'training_theme' ),
            ];
            get_template_part( 'partials/template-blocks/b-training-post', '', $block_args );
            ?>
        <?php endwhile; ?>
        <?php wp_reset_postdata(); ?>
    <?php endif; ?>
</div>
<?php display_load_more_button( $trainings_query->found_posts, 15 ); ?>