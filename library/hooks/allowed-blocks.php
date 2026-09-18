<?php

/**
 * Gutenberg blocks related stuff. Default blocks comes from the parent theme and/or the HDS plugin.
 * Here is custom allowed blocks and functionality for embed (iframe and script tags) 
 * 
 */

// Allow core/html block
add_filter( 'allowed_block_types_all', function( $allowed_blocks, $context ) {
    if ( is_array( $allowed_blocks ) ) {
        $allowed_blocks[] = 'core/html';
    }
    return $allowed_blocks;
}, 999, 2 );

// Allowed domains for iframe and script embeds
function opehuone_get_allowed_embed_domains() {

    if ( ! function_exists( 'get_field' ) ) {
        return array(
            'iframe' => array(),
            'script' => array(),
        );
    }

	// Get repeater from ACF options page ("Asetukset" --> "Opehuone asetukset")
    $rows = get_field( 'allowed_embeds', 'option' );

    if ( empty( $rows ) || ! is_array( $rows ) ) {
        return array(
            'iframe' => array(),
            'script' => array(),
        );
    }

    $domains = array(
        'iframe' => array(),
        'script' => array(),
    );

    foreach ( $rows as $row ) {
        $type       = isset( $row['tag_type'] ) ? strtolower( trim( $row['tag_type'] ) ) : '';
        $source_url = isset( $row['source_url'] ) ? strtolower( trim( $row['source_url'] ) ) : '';

        if ( in_array( $type, array( 'iframe', 'script' ), true ) && ! empty( $source_url ) ) {
            $domains[ $type ][] = $source_url;
        }
    }

    return array(
        'iframe' => ! empty( $domains['iframe'] ) ? array_unique( $domains['iframe'] ) : array(),
        'script' => ! empty( $domains['script'] ) ? array_unique( $domains['script'] ) : array(),
    );
}

// Allow iframe and script tags in wp_kses for post content
function opehuone_allow_embed_tags_in_kses( $tags, $context ) {
    if ( $context === 'post' ) {
        $tags['iframe'] = array(
            'src'                  => true,
            'style'                => true,
            'width'                => true,
            'height'               => true,
            'scrolling'            => true,
            'allowfullscreen'      => true,
            'frameborder'          => true,
            'title'                => true,
            'loading'              => true,
            'data-original-width'  => true,
            'data-original-height' => true,
        );
        $tags['script'] = array(
            'src'   => true,
            'async' => true,
            'defer' => true,
        );
    }
    return $tags;
}
add_filter( 'wp_kses_allowed_html', 'opehuone_allow_embed_tags_in_kses', 10, 2 );

// Check src url
function opehuone_is_domain_allowed( $src, $allowed_domains ) {
    $host = parse_url( $src, PHP_URL_HOST );
    if ( ! $host ) {
        return false;
    }
    $host = preg_replace( '/^www\./', '', strtolower( $host ) );

    foreach ( $allowed_domains as $domain ) {
        if ( $host === $domain || str_ends_with( $host, ".$domain" ) ) {
            return true;
        }
    }
    return false;
}

// Filter embeds on display, showing only allowed ones. Non-allowed embeds are replaced with a warning text for logged-in users
function opehuone_filter_embeds_on_display( $content ) {
    $allowed_embed_domains = opehuone_get_allowed_embed_domains();

    foreach ( $allowed_embed_domains as $tag => $domains ) {
        $pattern = '/<' . $tag . '\b[^>]*src=["\']([^"\']+)["\'][^>]*>(?:.*?<\/' . $tag . '>)?/is';

        $content = preg_replace_callback( $pattern, function( $matches ) use ( $domains, $tag ) {
            $src = $matches[1];

            if ( opehuone_is_domain_allowed( $src, $domains ) ) {
                return $matches[0];
            }

            if ( is_user_logged_in() ) {
                return '<div class="opehuone-non-allowed-embed" style="border:1px dashed red;padding:10px;color:#c00;margin-bottom:15px;">'
                    . 'Upotus (' . esc_html( $tag ) . ') estetty: lähde ei ole sallittujen listalla ('
                    . esc_html( parse_url( $src, PHP_URL_HOST ) ) . ')</div>';
            }

            return '';
        }, $content );
    }

    return $content;
}
add_filter( 'the_content', 'opehuone_filter_embeds_on_display', 20 );
