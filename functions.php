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

// Don't send TablePress warnings to Sentry
add_filter( 'wp_sentry_before_send', function ( \Sentry\Event $event, ?\Sentry\EventHint $hint = null ) {

    // Make sure $hint exists AND contains an exception
    if ( $hint instanceof \Sentry\EventHint && $hint->exception instanceof \Throwable ) {

        // Only drop warnings
        if ( $event->getLevel() === \Sentry\Severity::warning() ) {

            // Check file path for TablePress
            if ( strpos( $hint->exception->getFile(), 'wp-content/plugins/tablepress/' ) !== false ) {
                return null; // DROP the event
            }
        }
    }

    return $event; // Keep everything else
}, 2, 2 );

// Remove the parent setup hook after the parent theme has been initialized.
\add_action('after_setup_theme', function () {
    $parent_namespace = 'CityOfHelsinki\\WordPress\\Helsinki\\Theme\\Integrations\\Askem';

    // Only try to remove if the parent function exists and is hooked.
    if (\function_exists($parent_namespace . '\\setup_feedback_buttons')) {
        \remove_action('template_redirect', $parent_namespace . '\\setup_feedback_buttons');
    }
}, 20);

// Add own setup that always attaches to `helsinki_content_body_after` on posts.
\add_action('template_redirect', __NAMESPACE__ . '\\setup_feedback_buttons_child');

function setup_feedback_buttons_child(): void
{
    $parent_ns = 'CityOfHelsinki\\WordPress\\Helsinki\\Theme\\Integrations\\Askem';

    $enabled  = \function_exists($parent_ns . '\\is_feedback_enabled') 
        ? \call_user_func($parent_ns . '\\is_feedback_enabled') 
        : true; // fall back if needed

    $context  = \function_exists($parent_ns . '\\is_feedback_context') 
        ? \call_user_func($parent_ns . '\\is_feedback_context') 
        : true; // fall back if needed

    if ($enabled && $context && \is_singular('post')) {
        // Optional: apply the same body class behavior as parent (if you want it)
        if (\function_exists($parent_ns . '\\apply_body_class')) {
            \add_filter('body_class', $parent_ns . '\\apply_body_class', 10);
        }

        // Target the desired hook and priority for single posts
        \add_action('helsinki_content_body_after', __NAMESPACE__ . '\\render_feedback_buttons_child', 21);
    }
}

/**
 * This runs on `helsinki_content_body_after` for single posts.
 */
function render_feedback_buttons_child(): void
{
    $parent_fn = 'CityOfHelsinki\\WordPress\\Helsinki\\Theme\\Integrations\\Askem\\provide_feedback_buttons';
    if (\function_exists($parent_fn)) {
        \call_user_func($parent_fn);
        return;
    }

}
