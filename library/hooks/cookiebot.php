<?php

namespace Opehuone\Cookiebot;

// No cookiebot here!
return;

/**
 * Cookiebot related hooks
 */

/**
 * Edit and parse oembed iframes.
 * Add cookiebot placeholder message with renew link and link to original source.
 *
 * @param $return
 * @param $data
 * @param $url
 *
 * @return mixed|string|string[]
 */
function wrap_oembed_dataparse( $return, $data, $url ) {

	$no_cookies_message_1 = __( 'Hyväksy evästeet katsoakseni videon' );
	$no_cookies_message_2 = __( 'tai katso video' );
	$no_cookies_message_3 = __( 'täällä.' );
	$aria_label           = __( 'Avaa evästeasetukset' );

	// Create cookiebot optout marketing html element
	$placeholder_html = '<div class="video-embedding__error cookieconsent-optout-marketing"><p class="video-embedding__error-text"><a class="video-embedding__error-link" href="javascript:CookieConsent.renew();" aria-label="' . $aria_label . ' ">' . $no_cookies_message_1 . ' </a> ' . $no_cookies_message_2 . ' <a class="video-embedding__error-link" href="' . $url . '" target="_blank">' . $no_cookies_message_3 . '</a></p></div>';

	// Check if oembed has issuu content
	if ( in_array( $data->provider_name, array( 'Issuu' ) ) ) {
		// Replace script type and add cookieconset data attribute
		$return = str_replace( 'type="text/javascript"', 'type="text/plain" data-cookieconsent="marketing"', $return );
		// Add placeholder html before wrapping div element
		$return = str_replace( '<div data-url=', sprintf( '%s<div data-url=', $placeholder_html ), $return );
	}

	// Check if oembed has youtube or soundcloud content
	if ( in_array( $data->provider_name, array( 'YouTube', 'SoundCloud' ) ) ) {
		// Replace scr attribute with data-cookieblock
		$return = str_replace( ' src=', ' data-cookieblock-src=', $return );
		// Add placeholder html after iframe and add data-cookieconsent attribute
		$return = str_replace( '<iframe ', sprintf( '%s<iframe data-cookieconsent="marketing" ', $placeholder_html ), $return );
	}

	return $return;
}

add_filter( 'oembed_dataparse', __NAMESPACE__ . '\\wrap_oembed_dataparse', 99, 4 );


/**
 * Add global cookiebot consent renew shortcode
 *
 * Usage: [cookiebot-consent-renew text="Show my settings"]
 * Usage: [cookiebot-consent-renew text="Näytä evästeasetukseni"]
 */
add_shortcode( 'cookiebot-consent-renew', function ( $atts ) {

	$atts = shortcode_atts( [
		'text' => 'Näytä evästeasetukseni',
	], $atts, 'cookiebot-consent-renew' );

	ob_start();
	?>

	<p>
		<a href="javascript:CookieConsent.renew();">
			<?php echo esc_html( $atts['text'] ); ?>
		</a>
	</p>

	<?php
	return ob_get_clean();
} );

add_filter( 'script_loader_tag', __NAMESPACE__ . '\\add_cookiebot_allot_to_recaptcha', 10, 3 );

function add_cookiebot_allot_to_recaptcha( $tag, $handle, $source ) {
	if ( 'google-recaptcha' === $handle ) {
		$tag = '<script src="' . $source . '" id="google-recaptcha-js" data-cookieconsent="ignore"></script>';
	}

	return $tag;
}
