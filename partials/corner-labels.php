<?php
require_once get_stylesheet_directory() . '/library/classes/Utils.php';
use LuuptekWP\Utils;
use Opehuone\Helpers;

$utils = new Utils();

$terms = $utils->has_post_cornerlabels();
if ( ! is_wp_error( $terms ) ) {
    if ( $terms ) {
        ?>
        <ul class="page-inner-content__oppiaste-tags">
            <?php
            foreach ( $terms as $term) {
                ?>
                <li class="page-inner-content__oppiaste-tag">
                    <?php echo $term->name; ?>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
    }
}
