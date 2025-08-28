<?php

$category_terms = get_the_terms( get_the_ID(), 'category' );
$post_theme_terms = get_the_terms( get_the_ID(), 'post_theme' );

if ( ( empty( $category_terms ) && empty( $post_theme_terms ) ) || is_wp_error( $category_terms ) || is_wp_error( $post_theme_terms ) ) {
	return;
}

$post_type = get_post_type();
$archive_url = get_post_type_archive_link( $post_type );

?>
<h3><?php esc_html_e( 'Lue lisää aiheesta', 'helsinki-universal' ); ?></h3>

<ul class="post-tags post-tags--is-lg">
	<?php
    if ( !empty( $category_terms ) ) {
        foreach ( $category_terms as $category ) {
            $color = get_term_meta( $category->term_id, 'color_theme', true ) ?: 'suomenlinna';
            $filtered_url = add_query_arg(
                'filter_category', // This has to be prefixed, because sending taxonomy name as query string redirects to 404
                $category->term_id,
                $archive_url
            );

            ?>
            <li class="has-post-tag-color-<?php echo esc_attr( $color ); ?>">
                <a class="post-tags__clickable" href="<?php echo esc_url( $filtered_url ); ?>">
                    <?php echo esc_html( $category->name ); ?>
                </a>
            </li>
            <?php
        }
    }
    if ( !empty( $post_theme_terms ) ) {
        foreach ( $post_theme_terms as $theme ) {
            $color = get_term_meta( $theme->term_id, 'button_color_theme', true ) ?: 'suomenlinna';
            $filtered_url = add_query_arg(
                'filter_post_theme', // This has to be prefixed, because sending taxonomy name as query string redirects to 404
                $theme->term_id,
                $archive_url
            );

            ?>
            <li class="has-post-tag-color-<?php echo esc_attr( $color ); ?>">
                <a class="post-tags__clickable" href="<?php echo esc_url( $filtered_url ); ?>">
                    <?php echo esc_html( $theme->name ); ?>
                </a>
            </li>
            <?php
        }
    }
	?>
</ul>





