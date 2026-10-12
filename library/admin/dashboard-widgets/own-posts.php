<?php
// Own posts widget for the WordPress dashboard
function opehuone_own_posts_render() {

$user_id   = get_current_user_id();
$own_posts = get_posts( array(
    'post_type'      => 'post',
    'author'         => $user_id,
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
) );
?>

<div id="opehuone-own-posts" class="opehuone-widgets">

    <?php if ( empty( $own_posts ) ) : ?>
        <p class="info-message"><?php esc_html_e( 'Sinulla ei ole vielä julkaistuja artikkeleita.' ); ?></p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th scope="col"><?php esc_html_e( 'Päivämäärä' ); ?></th>
                    <th scope="col"><?php esc_html_e( 'Artikkeli' ); ?></th>
                </tr>
            </thead>
            <?php foreach ( $own_posts as $post ) : ?>
                <tr>
                    <td class="col-date"><?php echo esc_html( get_the_date( '', $post ) ); ?></td>
                    <td class="col-title">
                        <a href="<?php echo esc_url( get_edit_post_link( $post->ID ) ); ?>">
                            <?php echo esc_html( get_the_title( $post ) ); ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</div>

<?php
}