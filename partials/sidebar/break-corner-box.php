<?php

use function \Opehuone\Helpers\the_svg;
use function \Opehuone\TemplateFunctions\fetch_wikipedia_featured_articles;

/**
 * Display the concentration element
 * It's possible that there are no concentration posts, so that's why it's in it's own template
 */
get_template_part( 'partials/components/concentration' ); ?>

<div class="break-corner-box__wikipedia">
    <div class="break-corner-box__wikipedia-header">
        <?php the_svg('icons/' . 'wiki'); ?>
        <span class="break-corner-box__wikipedia-title"><?php esc_html_e('Wikipedia viikon suosituimmat artikkelit', 'helsinki-universal'); ?></span>

    </div>
    <?php fetch_wikipedia_featured_articles(); ?>
</div>
