<?php
// Latest trainings widget for the WordPress dashboard
function opehuone_latest_trainings_render() {

$trainings = get_posts( array(
    'post_type'      => 'training',
    'posts_per_page' => 3,
    'orderby'        => 'date',
    'order'          => 'DESC',
    'post_status'    => 'publish',
) );
?>

<div id="opehuone-latest-trainings" class="opehuone-widgets">

    <?php if ( empty( $trainings ) ) : ?>
        <p class="info-message"><?php esc_html_e( 'Sinulla ei ole vielä julkaistuja koulutuksia.' ); ?></p>
    <?php else : ?>
        <table>
            <thead>
                <tr>
                    <th scope="col"><?php esc_html_e( 'Päivämäärä' ); ?></th>
                    <th scope="col"><?php esc_html_e( 'Koulutus' ); ?></th>
                </tr>
            </thead>
            <?php foreach ( $trainings as $post ) : ?>
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