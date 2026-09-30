<?php
/**
 * Custom ACF block: Embed
 * 
 * Uses ACF options page for allowed embed domains.
 * Builds iframe or script tags based on allowed domains and attributes.
 * 
 */

// Get allowed embed domains from ACF options page
function opehuone_get_allowed_embed_domains() {
    if ( ! function_exists( 'get_field' ) ) {
        return array( 'iframe' => array(), 'script' => array() );
    }

    $rows = get_field( 'allowed_embeds', 'option' );

    if ( empty( $rows ) || ! is_array( $rows ) ) {
        return array( 'iframe' => array(), 'script' => array() );
    }

    $domains = array( 'iframe' => array(), 'script' => array() );

    foreach ( $rows as $row ) {
        $type       = isset( $row['tag_type'] ) ? strtolower( trim( $row['tag_type'] ) ) : '';
        $source_url = isset( $row['source_url'] ) ? strtolower( trim( $row['source_url'] ) ) : '';

        if ( in_array( $type, array( 'iframe', 'script' ), true ) && ! empty( $source_url ) ) {
            $domains[ $type ][] = $source_url;
        }
    }

    return array(
        'iframe' => array_unique( $domains['iframe'] ),
        'script' => array_unique( $domains['script'] ),
    );
}

// Return true if domain is allowed
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

// Return allowed attributes for a given tag type
function opehuone_allowed_attrs_for( $tag ) {
    if ( $tag === 'iframe' ) {
        return array(
            'width', 'height', 'scrolling', 'allowfullscreen',
            'frameborder', 'title', 'loading',
            'data-original-width', 'data-original-height',
            'allow', 'referrerpolicy',
        );
    }
    if ( $tag === 'script' ) {
        return array( 'async', 'defer' );
    }
    return array();
}

// Builds embed block
function opehuone_build_single_embed( array $row, array &$blocked ) {
    $type = $row['embed_type'] ?? '';
    $url  = trim( (string) ( $row['embed_url'] ?? '' ) );

    if ( ! in_array( $type, array( 'iframe', 'script' ), true ) || empty( $url ) ) {
        return '';
    }

    $allowed_domains = opehuone_get_allowed_embed_domains();

    if ( ! opehuone_is_domain_allowed( $url, $allowed_domains[ $type ] ) ) {
        $blocked[] = $type . ': ' . parse_url( $url, PHP_URL_HOST );
        return '';
    }

    $allowed_attr_names = opehuone_allowed_attrs_for( $type );
    $attr_html = '';

    $attributes = $row['attributes'] ?? array();
    foreach ( $attributes as $attr_row ) {
        $name  = $attr_row['attribute_name'] ?? '';
        $value = $attr_row['attribute_value'] ?? '';

        if ( in_array( $name, $allowed_attr_names, true ) && $value !== '' ) {
            $attr_html .= ' ' . esc_attr( $name ) . '="' . esc_attr( $value ) . '"';
        }
    }

    if ( $type === 'iframe' ) {
        return sprintf(
            '<iframe src="%s"%s style="border:none;" sandbox="allow-scripts allow-presentation allow-same-origin"></iframe>',
            esc_url( $url ),
            $attr_html
        );
    }

    return sprintf(
        '<script src="%s"%s></script>',
        esc_url( $url ),
        $attr_html
    );
}

// Render block
function opehuone_render_embed_block( $block, $content = '', $is_preview = false ) {
    $embeds = get_field( 'embeds' ) ?: array();

    if ( empty( $embeds ) ) {
        if ( $is_preview ) {
            echo '<p style="color:#666;border:1px dashed #ccc;padding:30px;">Lisää vähintään yksi upotus.</p>';
        }
        return;
    }

    $blocked = array();
    $output  = '';

    foreach ( $embeds as $row ) {
        $output .= opehuone_build_single_embed( $row, $blocked );
    }

    if ( ! empty( $blocked ) && ( $is_preview || is_user_logged_in() ) ) {
        echo '<div style="border:1px dashed #c00;padding:30px;color:#c00;">'
            . 'Estetty (lähde ei sallittujen listalla): ' . esc_html( implode( ', ', $blocked ) )
            . '</div>';
    }

    if ( empty( $output ) ) {
        return;
    }

    if ( $is_preview ) {
        echo '<div style="border:1px dashed #999;padding:30px;text-align:center;color:#666;background:#f5f5f5;">'
            . 'Upotus tallennettu — esikatselu näkyy julkaistulla sivulla'
            . '</div>';
        return;
    }

    echo '<div class="opehuone-embed">' . $output . '</div>';
}
