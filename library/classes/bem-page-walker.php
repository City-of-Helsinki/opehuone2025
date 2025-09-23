<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BEM_Page_Walker extends Walker_Page {

	private $curpage;

	/**
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param int $depth Depth of page. Used for padding.
	 * @param array $args
	 *
	 * @since 2.1.0
	 *
	 * @see Walker::start_lvl()
	 */
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$indent = str_repeat( "\t", $depth );

		$menu_class = 'sidemenu-nav';

		$parent_depth = $depth + 2;
		$button_depth = $parent_depth - 1;

		$aria_label = sprintf( esc_html__( 'Avaa sivun %s alanavigaatio' ), $this->curpage->post_title );

		$icon = '<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<rect width="24" height="24" fill="#F2F2F2"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M12 13.5L17 8.5L18.5 10L12 16.5L5.5 10L7 8.5L12 13.5Z" fill="#1A1A1A"/>
</svg>
';

		$output .= "\n$indent<button class=\"${menu_class}-lvl-${button_depth}__sub-menu-toggle sidemenu-toggle\" data-page-nav-toggle=\"sub-menu\" aria-label=\"${aria_label}\" aria-haspopup=\"true\" aria-expanded=\"false\">{$icon}</button>\n";

		$output .= "\n$indent<ul class=\"${menu_class}-lvl-${parent_depth} ${menu_class}-lvl\">\n";
	}

	/**
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $page Page data object.
	 * @param int $depth Depth of page. Used for padding.
	 * @param int $current_page Page ID.
	 * @param array $args
	 *
	 * @since 2.1.0
	 *
	 * @see Walker::start_el()
	 */
	function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {
	
		$args['link_before'] = isset($args['link_before']) ? $args['link_before'] : '';
		$args['link_after'] = isset($args['link_after']) ? $args['link_after'] : '';

		$this->curpage = $page;

		$indent = str_repeat( "\t", $depth );

		$menu_class = 'sidemenu-nav';

		$lvl = $depth + 1;

		$class_names   = array();
		$class_names[] = sprintf( '%s-lvl-%s__item', $menu_class, $lvl );
		$class_names[] = 'sidemenu-page-item';

		if ( ! empty( $current_page ) ) {
			$_current_page = get_post( $current_page );
			
		if ( in_array( $page->ID, $_current_page->ancestors, true ) ) {
			$class_names[] = sprintf( '%s-lvl-%s__item--current-page-ancestor sidemenu-current-page-ancestor', $menu_class, $lvl );
		}
		if ( $page->ID === $current_page ) {
			$class_names[] = sprintf( '%s-lvl-%s__item--current sidemenu-current-page', $menu_class, $lvl );
		} elseif ( $_current_page && $page->ID === $_current_page->post_parent ) {
			$class_names[] = sprintf( '%s-lvl-%s__item--current-page-parent sidemenu-current-page-parent', $menu_class, $lvl );
		}
			
		} elseif ( get_option( 'page_for_posts' ) === $page->ID ) {
			$class_names[] = sprintf( '%s-lvl-%s__item--current-page-parent', $menu_class, $lvl );
		}

		if ( $page->ID === $current_page ) {
			$aria_current = ' aria-current="page"';
		} else {
			$aria_current = '';
		}

		$class_names = implode( ' ', $class_names );

		// Add aria-label to describe path of current item.
		$parent_page_title = get_the_title( wp_get_post_parent_id( $page->ID ) );
		$aria_label        = sprintf( esc_html__( 'Sivu %s' ), $page->post_title );
		if ( $parent_page_title ) {
			$aria_label = sprintf( esc_html__( 'Sivu %s, yläsivu %s' ), $page->post_title, $parent_page_title );
		}

		$cornerlabels = \Opehuone\Utils\get_cornerlabels_term_ids( $page->ID );

		$output .= $indent . '<li data-has-cornerlabels="' . esc_attr( $cornerlabels ) . '" class="' . $class_names . '">';

		$output .= '<a class="' . $menu_class . '-lvl-' . $lvl . '__link sidemenu-page-link" href="' . get_permalink( $page->ID ) . '" ' . $aria_current . ' aria-label="' . $aria_label . '">' . $args['link_before'] . apply_filters( 'the_title',
				$page->post_title, $page->ID ) . $args['link_after'] . '</a>';
	}

}


class Filtered_BEM_Page_Walker extends BEM_Page_Walker {
    private $allowed_ids;

    // Add this property
    public $current_page = 0;

    public function __construct($allowed_ids = []) {
        $this->allowed_ids = $allowed_ids;
    }

    public function start_el(&$output, $page, $depth = 0, $args = [], $current_page = 0) {
        // If filtering and this page is not in the allowed list, skip it
        if (!empty($this->allowed_ids) && !in_array($page->ID, $this->allowed_ids)) {
            return;
        }

        // Use current_page property if not passed
        if (empty($current_page)) {
            $current_page = $this->current_page;
        }

        // Call parent to render normally
        parent::start_el($output, $page, $depth, $args, $current_page);
    }
}
