<div id="header-search" aria-labelledby="header-search-toggle" role="region" hidden>
    <div class="hds-container header-search-box-container">
        <div class="search-header">
            <h2 class="search-header__title"><?php echo esc_html_x('Hae Opehuoneesta', 'search title', 'helsinki-universal'); ?></h2>
            <button class="search-header__close-button">
                <span>Sulje</span>
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
        <button
            id="header-search-toggle"
            class="button-reset has-icon has-icon--above js-toggle js-toggle-no-scroll"
            aria-haspopup="true"
            aria-controls="header-search"
            aria-expanded="false"
            data-no-scroll-breakpoint="992"
            data-no-scroll-limit="down">
            <span class="js-toggle__close">
    			<?php helsinki_svg_icon( 'cross' ); ?>
    			<span class="text" aria-hidden="true">
    				<?php echo esc_html_x( 'Close', 'verb', 'helsinki-universal' ); ?>
    			</span>
    			<span class="screen-reader-text">
    				<?php echo esc_html_x( 'Close site search', 'Label - Toggle - Search', 'helsinki-universal' ); ?>
    			</span>
    		</span>
        </button>
    </div>
    <div class="header-search-outer-findkit-container">
        <div class="hds-container">
            <div class="findkit-overlay-container"></div>
        </div>
    </div>
</div>

