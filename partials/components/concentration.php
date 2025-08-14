<?php

use function \Opehuone\Helpers\the_svg;

?>
<div class="concentration" id="concentration">
    <div class="concentration__content">
        <div class="concentration__actions">
            <div class="concentration__actions-row">
                <button class="concentration__action-button stop-concentration">
                    <?php the_svg('icons/' . 'close-24px'); ?>
                </button>
                <button class="concentration__action-button mute-concentration">
                    <?php the_svg('icons/' . 'audio-mute'); ?>
                </button>
            </div>
        </div>
        <div class="concentration__loading">
            <?php the_svg('icons/' . 'refresh'); ?>
        </div>
        <div class="concentration__text">
            <div class="concentration__text-close-wrapper">
                <button class="concentration__text-close">
                    <?php the_svg('icons/' . 'close-24px'); ?>
                </button>
            </div>
            <div class="concentration__text-wrapper">

            </div>
        </div>
    </div>
</div>