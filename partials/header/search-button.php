<div class="navigation__search">
    <button
        id="header-search-toggle"
        class="button-reset has-icon has-icon--above js-toggle js-toggle-no-scroll"
        aria-haspopup="true"
        aria-controls="header-search"
        aria-expanded="false"
        data-no-scroll-breakpoint="992"
        data-no-scroll-limit="down">
		<span class="js-toggle__open">
			<?php helsinki_svg_icon( 'search' ); ?>
			<span aria-hidden="true">
				<?php echo esc_html_x( 'Haku', 'verb', 'helsinki-universal' ); ?>
			</span>
			<span class="screen-reader-text">
				<?php echo esc_html_x( 'Search from site', 'Label - Toggle - Search', 'helsinki-universal' ); ?>
			</span>
		</span>
        <span class="js-toggle__close">
            <?php helsinki_svg_icon( 'search' ); ?>
            <span aria-hidden="true">
                <?php echo esc_html_x( 'Haku', 'verb', 'helsinki-universal' ); ?>
            </span>
            <span class="screen-reader-text">
                <?php echo esc_html_x( 'Search from site', 'Label - Toggle - Search', 'helsinki-universal' ); ?>
            </span>
		</span>
    </button>
</div>
