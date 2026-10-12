<?php
// Own pages widget for the WordPress dashboard
function opehuone_own_pages_render() {

$user_id = get_current_user_id();

// Own published pages
$own_pages = get_posts( array(
    'post_type'      => 'page',
    'author'         => $user_id,
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
) );

// Other people's pages where you are the last editor
$edited_pages = get_posts( array(
    'post_type'       => 'page',
    'author__not_in'  => array( $user_id ),
    'meta_key'        => '_edit_last',
    'meta_value'      => $user_id,
    'posts_per_page'  => 3,
    'orderby'         => 'modified',
    'order'           => 'DESC',
    'post_status'     => array( 'publish', 'draft', 'pending', 'private' ),
) );
?>

<div id="opehuone-own-pages" class="opehuone-widgets">

    <?php if ( empty( $own_pages ) ) : ?>
        <p class="info-message"><?php esc_html_e( 'Sinulla ei ole vielä julkaistuja sivuja.' ); ?></p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th colspan="2"><?php esc_html_e( 'Julkaistut sivut' ); ?></th>
                </tr>
            </thead>
            <?php foreach ( $own_pages as $page ) : ?>
                <tr>
                    <td class="col-date"><?php echo esc_html( get_the_date( '', $page ) ); ?></td>
                    <td class="col-title">
                        <a href="<?php echo esc_url( get_edit_post_link( $page->ID ) ); ?>">
                            <?php echo esc_html( get_the_title( $page ) ); ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <?php if ( empty( $edited_pages ) ) : ?>
        <p class="info-message"><?php esc_html_e( 'Et ole muokannut muiden sivuja.' ); ?></p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th colspan="2"><?php esc_html_e( 'Muokatut sivut' ); ?></th>
                </tr>
            </thead>
            <?php foreach ( $edited_pages as $page ) : ?>
                <tr>
                    <td class="col-date"><?php echo esc_html( get_the_modified_date( '', $page ) ); ?></td>
                    <td class="col-title">
                        <a href="<?php echo esc_url( get_edit_post_link( $page->ID ) ); ?>">
                            <?php echo esc_html( get_the_title( $page ) ); ?>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

</div>

<?php
}