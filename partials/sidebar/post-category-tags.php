<?php

$terms = get_the_terms( get_the_ID(), 'category' );

if ( empty( $terms ) || is_wp_error( $terms ) ) {
	return;
}

$post_type = get_post_type();
$archive_url = get_post_type_archive_link( $post_type );

?>
<h3><?php esc_html_e( 'Lue lisää aiheesta', 'helsinki-universal' ); ?></h3>

<ul class="post-tags post-tags--is-lg">
	<?php
	foreach ( $terms as $category ) {
		$color = ! empty( get_term_meta( $category->term_id, 'button_color_theme', true ) ) ? get_term_meta( $category->term_id, 'button_color_theme', true ) : 'suomenlinna';
        // $filtered_url = add_query_arg(
        //     'filter_posts_theme', // This has to be prefixed, because sending taxonomy name as query string redirects to 404
        //     $category->term_id,
        //     $archive_url
        // );

        ?>
		<li class="has-post-tag-color-<?php echo esc_attr( $color ); ?>">
            <a class="post-tags__clickable" href="#">
                <?php echo esc_html( $category->name ); ?>
            </a>
		</li>
		<?php
	}
	?>
</ul>





