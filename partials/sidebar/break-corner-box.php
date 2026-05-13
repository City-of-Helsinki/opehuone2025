<?php

use function \Opehuone\Helpers\the_svg;
use function \Opehuone\TemplateFunctions\fetch_wikipedia_featured_articles;

/**
 * Display the concentration element
 * It's possible that there are no concentration posts, so that's why it's in it's own template
 */
get_template_part( 'partials/components/concentration' ); ?>

<!-- Button to open the wikipedia articles accordion -->
<button class="break-corner-box__button actions-wrapper__list-item--wikipedia">
    <div class="break-corner-box__button-face-svg">
        <?php the_svg('icons/' . 'wiki'); ?>
    </div>

    <span>
        <?php esc_html_e('Wikipedian viikon suosituimmat artikkelit', 'helsinki-universal'); ?>
    </span>

    <div class="break-corner-box__button-chevron-down-svg">
        <?php the_svg('icons/angle-down') ?>
    </div>
    
    <div class="break-corner-box__button-chevron-up-svg">
        <?php the_svg('icons/angle-up') ?>
    </div>
</button>

<div class="wikipedia-opener">
    <div class="break-corner-box__wikipedia">
        <?php fetch_wikipedia_featured_articles(); ?>
    </div>
</div>

<!-- Other external buttons at the bottom of the break corner box -->
<div class="break-corner-box__buttons">
    <a href="https://natlibfi.github.io/NDL-VuFind2/api/memory-game.html" target="_blank" rel="noopener noreferrer"
        class="button">
        <?php esc_html_e( 'Muistipeli', 'helsinki-universal' ); ?>
        <?php the_svg( 'icons/arrow-top-right' ); ?>
    </a>
    <a href="https://sanuli.fi/" target="_blank" rel="noopener noreferrer"
        class="button">
        <?php esc_html_e( 'Sanuli', 'helsinki-universal' ); ?>
        <?php the_svg( 'icons/arrow-top-right' ); ?>
    </a>
</div>