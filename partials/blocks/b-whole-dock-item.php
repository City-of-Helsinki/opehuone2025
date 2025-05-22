<?php
$dock_url   = $args['url'];
$dock_title = $args['title'];
$icon_url   = $args['icon_url'];
$icon_alt   = $args['icon_alt'];
$first_char = substr( $dock_title, 0, 1 );
?>
<li class="whole-dock-item">
    <a href="<?php echo $dock_url ?>" target="_blank" class="whole-dock-link"
       aria-label="<?php echo esc_attr( $dock_title ); ?>">
        <?php if ( ! empty( $icon_url ) ) : ?>
            <img src="<?php echo $icon_url; ?>" class="whole-dock-icon"/>
        <?php endif; ?>
        <?php if ( empty( $icon_url ) ) : ?>
            <div class="whole-dock-icon-char">
                <?php echo $first_char; ?>
            </div>
        <?php endif; ?>
        <span class="whole-dock-item-title">
            <?php echo $dock_title; ?>
        </span>
    </a>
</li>
