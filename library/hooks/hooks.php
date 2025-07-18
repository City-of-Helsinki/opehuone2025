<?php

namespace Opehuone\Hooks;

/**
 * Add scripts to head
 *
 * @hook wp_head
 */
add_action(
	'wp_head',
	function () {
		?>
		<?php
	},
	999
);

/**
 * Add scripts to footer
 *
 * @hook wp_footer
 */
add_action(
	'wp_footer',
	function () {
		?>
		<?php
	},
	999
);

/**
 * Add scripts after opening body
 */
add_action(
	'wp_body_open',
	function () {
		?>
		<?php
	}, 1
);

add_filter(
	'excerpt_more',
	function () {
		return '...';
	}
);

/**
 * Redirect non-logged in users, just uncomment hook to have normal functionality
 */
function redirect_non_logged_in() {
	// No need for this is local/development envs
	if ( \wp_get_environment_type() === 'local' || \wp_get_environment_type() === 'development' ) {
		return;
	}

	if ( ! \is_user_logged_in() ) {
		\wp_redirect( wp_login_url() );
		exit;
	}
}

//add_action( 'template_redirect', __NAMESPACE__ . '\\redirect_non_logged_in' );

function render_dock_updater_button_in_acf( $field ) {
    echo '<h3>Päivitä käyttäjien dockit tästä</h3>';
    echo '<button id="dock-update-btn">Päivitä</button>';
}

// Update dock button to dock items acf field
add_action( 'acf/render_field/name=dock_items', __NAMESPACE__ . '\render_dock_updater_button_in_acf');

function render_dock_updater_js_admin_footer() {
    ?>
    <script type="text/javascript">
        (function ($) {
            var opehuone_ajax_url = '<?php echo admin_url( 'admin-ajax.php' ); ?>';
            var updateBtn = $('#dock-update-btn');
            var categoriesUpdateBtn = $('#oppiaste-categories-update-btn');
            var copyBtn = $('#training-copy-btn');
            updateBtn.on('click', function (e) {
                e.preventDefault();
                updateBtn.prop('disabled', true);
                updateBtn.text('Päivitetään...');

                $.ajax({
                    url: opehuone_ajax_url,
                    type: 'POST',
                    data: ({
                            action: 'update_dock_items',
                        }
                    ),
                    success: function () {
                        $('#dock-update-btn').text('Päivitetty!!');
                        updateBtn.prop('disabled', false);
                    }
                });
            });

            categoriesUpdateBtn.on('click', function (e) {
                e.preventDefault();
                categoriesUpdateBtn.prop('disabled', true);
                categoriesUpdateBtn.text('Päivitetään...');

                $.ajax({
                    url: opehuone_ajax_url,
                    type: 'POST',
                    data: ({
                            action: 'update_users_oppiaste_settings',
                        }
                    ),
                    success: function () {
                        categoriesUpdateBtn.text('Päivitetty!!');
                        categoriesUpdateBtn.prop('disabled', false);
                    }
                });
            });

            copyBtn.on('click', function (e) {
                e.preventDefault();
                copyBtn.prop('disabled', true);
                copyBtn.text('Kopioidaan...');
                var post_id = $(this).attr('data-training-id');

                $.ajax({
                    url: opehuone_ajax_url,
                    type: 'POST',
                    data: ({
                            postID: post_id,
                            action: 'copy_training_to_articles',
                        }
                    ),
                    success: function (content) {
                        console.log(content);
                        copyBtn.text('Kopioitu!!');
                        copyBtn.prop('disabled', false);
                    }
                });
            });
        })(jQuery);
    </script>
    <?php
}

add_action( 'admin_footer', __NAMESPACE__ . '\render_dock_updater_js_admin_footer' );

add_action( 'after_setup_theme', function () {
    register_nav_menus([
        'footer_top_menu' => __('Alatunnisteen ylävalikko', 'helsinki-universal'),
    ]);
} );

add_action('helsinki_footer_bottom', function() {

}, 10);