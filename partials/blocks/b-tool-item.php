<?php
use Opehuone\Utils;

$tool_post_id = isset( $args['post_id'] ) ? $args['post_id'] : null;
$tool_title   = isset( $args['title'] ) ? $args['title'] : null;
$tool_url     = isset( $args['url'] ) ? $args['url'] : null;
$tool_icon    = isset( $args['icon_url'] ) ? $args['icon_url'] : null;
?>
<div class="tools-column" data-tool-id="<?php echo esc_attr( $tool_post_id ); ?>">
    <div class="tools-column__content">
        <a href="<?php echo esc_url( $tool_url ); ?>" class="tools-column__link" target="_blank" aria-label="<?php echo esc_html( $tool_title . Utils\get_open_new_tab_text() ); ?>">
            <img src="<?php echo esc_url( $tool_icon ); ?>"
                    alt="<?php echo esc_attr( $tool_title ); ?>"
                    class="tools-column__image"/>
            <span class="tools-column__tooltip"><?php echo esc_html( $tool_title ); ?></span>
        </a>
    </div>
</div>
