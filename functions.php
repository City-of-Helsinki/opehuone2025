<?php

use \Opehuone\Helpers;

define( 'TEXT_DOMAIN', 'opehuone2025' );

// removes main menu action from Helsinki theme
function remove_parent_menu() {
    remove_action('helsinki_header_bottom', 'opehuone_header_main_menu', 20);
}
add_action('wp_loaded', 'remove_parent_menu');

add_action( 'helsinki_header_bottom', function() {
    get_template_part( 'partials/components/findkit' );
}, 10);
/**
 * Require helpers
 */
require dirname( __FILE__ ) . '/library/functions/helpers.php';
require dirname( __FILE__ ) . '/library/functions/modules/menu.php';
require dirname( __FILE__ ) . '/library/utils/walkers.php';
require dirname( __FILE__ ) . '/library/functions/polylang-fallbacks.php';
require dirname( __FILE__ ) . '/library/functions/template-functions.php';

// calls for menu template part
if ( ! function_exists('opehuone_header_main_menu') ) {
	function opehuone_header_main_menu() {
		get_template_part('partials/header/menu');
	}
}

/**
 * Set theme name which will be referenced from style & script registrations
 *
 * @return WP_Theme
 */
function opehuone_theme() {
	return wp_get_theme();
}

/**
 * Require some classes
 */
Helpers\require_files( dirname( __FILE__ ) . '/library/classes' );

/**
 * Require utils
 */
Helpers\require_files( dirname( __FILE__ ) . '/library/utils' );

/**
 * Require acf options
 */
Helpers\require_files( dirname( __FILE__ ) . '/library/acf-options' );

/**
 * Require custom post types and taxonomies
 */
Helpers\require_files( dirname( __FILE__ ) . '/library/custom-posts' );
Helpers\require_files( dirname( __FILE__ ) . '/library/taxonomies' );

/**
 * Hooks
 */
Helpers\require_files( dirname( __FILE__ ) . '/library/hooks' );

/**
 * Register local ACF-json
 */
add_filter( 'acf/settings/save_json', function () {
	return get_stylesheet_directory() . '/library/acf-data';
} );

add_filter( 'acf/settings/load_json', function ( $paths ) {
	$paths[] = get_stylesheet_directory() . '/library/acf-data';

	return $paths;
} );

/**
 * Remove certain levels from Heading block
 */
function example_modify_heading_levels_globally( $args, $block_type ) {
    if ( 'core/heading' !== $block_type ) {
        return $args;
    }
    // Remove H1, H5, and H6.
    $args['attributes']['levelOptions']['default'] = [ 2, 3, 4 ];
    return $args;
}
add_filter( 'register_block_type_args', 'example_modify_heading_levels_globally', 10, 2 );

/**
 * Add text to theme footer
 */
add_filter(
	'admin_footer_text',
	function () {
		return '<span id="footer-thankyou">' . opehuone_theme()->Name . ' by: <a href="' . opehuone_theme()->AuthorURI . '" target="_blank">' . opehuone_theme()->Author . '</a><span>';
	}
);

// Site is hidden from search engines, but Findkit needs the sitemap ==> lets enable it
add_filter( 'wp_sitemaps_enabled', '__return_true' );

// Don't send TablePress Premium-originated warnings/errors to Sentry
add_filter('wp_sentry_before_send', function (\Sentry\Event $event, ?\Sentry\EventHint $hint = null) {
    $PLUGIN_PATH = 'wp-content/plugins/tablepress-premium/';

    // Determine the level safely (avoid strict identity)
    $level = $event->getLevel(); // \Sentry\Severity|null

    // Helper to normalize a level into a lowercase string
    $level_name = null;
    if ($level instanceof \Sentry\Severity) {
        // Prefer the comparison helper if available
        $is_warning = method_exists($level, 'isEqualTo')
            ? $level->isEqualTo(\Sentry\Severity::warning())
            : (string)$level === (string)\Sentry\Severity::warning();

        $is_error = method_exists($level, 'isEqualTo')
            ? $level->isEqualTo(\Sentry\Severity::error())
            : (string)$level === (string)\Sentry\Severity::error();
    } else {
        $is_warning = false;
        $is_error   = false;
    }

    // We want to drop TablePress “Warning: …” but sometimes these come in as error level
    $is_warning_or_error = $is_warning || $is_error;

    if ($is_warning_or_error) {
        // First try $hint->exception file (fast path)
        if ($hint instanceof \Sentry\EventHint && $hint->exception instanceof \Throwable) {
            $file = $hint->exception->getFile();
            if (is_string($file) && strpos($file, $PLUGIN_PATH) !== false) {
                return null; // DROP
            }
        }

        // Fallback: scan stack frames for the plugin path
        $exceptions = $event->getExceptions();
        if (is_array($exceptions)) {
            foreach ($exceptions as $ex) {
                $stack = $ex->getStacktrace();
                if ($stack) {
                    foreach ($stack->getFrames() as $frame) {
                        $frameFile = $frame->getFile();
                        if (is_string($frameFile) && strpos($frameFile, $PLUGIN_PATH) !== false) {
                            return null; // DROP
                        }
                    }
                }
            }
        }
    }

    return $event; // keep everything else
}, 100, 2);

// Add Askem to post content
add_action( 'template_redirect', function() {
    if ( is_singular( 'post' ) && apply_filters( 'helsinki_feedback_enabled', false ) ) {
        add_filter( 'body_class', 'helsinki_feedback_buttons_body_class', 10 );
        add_action( 'helsinki_content_body_after', 'helsinki_feedback_buttons', 21 );
    }
}, 20 );
