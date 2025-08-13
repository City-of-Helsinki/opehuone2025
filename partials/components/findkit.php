<div id="header-search" aria-labelledby="header-search-toggle" role="region" hidden>
    <div class="hds-container header-search-box-container">
        <h2 class="search-title"><?php echo esc_html_x('Hae Opehuoneesta', 'search title', 'helsinki-universal'); ?></h2>
        <label for="header-search-input"><?php echo esc_html_x( 'Vapaasanahaku', 'search box', 'helsinki-universal'); ?></label>
        <div class="search-field hds-text-input hds-text-input__input-wrapper">
            <input id="header-search-input" class="hds-text-input__input search-field__input" type="search" />
            <button id="clear-search" class="search-field__clear-search" type="button" aria-label="Clear search">
                <?php \Opehuone\Helpers\the_svg('icons/' . 'cross'); ?>
            </button>
        </div>
    </div>
    <div class="header-search-outer-findkit-container">
        <div class="hds-container">
            <div class="findkit-overlay-container"></div>
        </div>
    </div>
</div>

