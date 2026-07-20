<div id="header-search" aria-labelledby="header-search-toggle" role="region" hidden>
    <div class="hds-container header-search-box-container">
        <div class="search-header">
            <h2 class="search-header__title"><?php echo esc_html_x('Hae Opehuoneesta', 'search title', 'helsinki-universal'); ?></h2>
            <button class="search-header__close-button">
                <span><?php echo esc_html_x( 'Sulje', 'search box', 'helsinki-universal'); ?></span>
                <?php \Opehuone\Helpers\the_svg('icons/' . 'cross'); ?>
            </button>
        </div>
        <label for="header-search-input"><?php echo esc_html_x( 'Vapaasanahaku', 'search box', 'helsinki-universal'); ?></label>
        <div class="search-field hds-text-input hds-text-input__input-wrapper">
            <input id="header-search-input" class="hds-text-input__input search-field__input" type="search" />
            <button id="clear-search" class="search-field__clear-search" type="button" aria-label="Clear search">
                <?php \Opehuone\Helpers\the_svg('icons/' . 'cross'); ?>
            </button>
        </div>
        <nav class="fdk-tabs" role="tablist" aria-label="<?php esc_attr_e( 'Hakutulosten suodattimet', 'helsinki-universal' ); ?>">
            <button
                type="button"
                class="fdk-tabs-button active"
                data-group="pages-tab"
                role="tab"
                id="tab-pages-tab"
                aria-selected="true"
                aria-controls="panel-pages-tab"
                tabindex="0"
            >
                <span class="button-text"><?php esc_html_e( 'Sisältösivut', 'helsinki-universal' ); ?></span>
                <span class="results-count"></span>
            </button>
            <button
                type="button"
                class="fdk-tabs-button"
                data-group="posts-tab"
                role="tab"
                id="tab-posts-tab"
                aria-selected="false"
                aria-controls="panel-posts-tab"
                tabindex="-1"
            >
                <span class="button-text"><?php esc_html_e( 'Uutiset', 'helsinki-universal' ); ?></span>
                <span class="results-count"></span>
            </button>
            <button
                type="button"
                class="fdk-tabs-button"
                data-group="trainings-tab"
                role="tab"
                id="tab-trainings-tab"
                aria-selected="false"
                aria-controls="panel-trainings-tab"
                tabindex="-1"
            >
                <span class="button-text"><?php esc_html_e( 'Koulutukset', 'helsinki-universal' ); ?></span>
                <span class="results-count"></span>
            </button>
        </nav>
    </div>
    <div id="findkit-counts-hidden" aria-hidden="true"></div>
    <div class="header-search-outer-findkit-container">
        <div class="hds-container">
            <div class="findkit-test-container"></div>
            <div class="findkit-overlay-container"></div>
        </div>
    </div>
</div>

