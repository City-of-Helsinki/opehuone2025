<?php

class Opehuone_Menu_Walker extends Walker_Nav_Menu
{
	private string $list_item_tag = 'li';
	public $submenu_id = 0;

	function start_lvl(&$output, $depth = 0, $args = array())
	{
		$output .= sprintf(
			"\n%s<ul class=\"menu menu--sub\" aria-labelledby=\"%s\">\n",
			str_repeat("\t", $depth),
			$this->submenu_id ?? 0
		);
	}

	public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
	{

		global $wp;
		
		$indent = '';
		if ($this->discard_item_spacing($args) && $depth) {
			$indent = str_repeat("\t", $depth);
		}

		$classes = array('menu__item', 'menu__depth-' . $depth);

		if ($item->current == 1 || $item->current_item_ancestor == true) {
			$classes[] = 'menu__item--active';
		}

		// Extra check for category pages
		if (is_category() && $item->object == 'category') {
			$current_cat = get_queried_object();
			if ($current_cat && isset($current_cat->term_id) && $item->object_id == $current_cat->term_id) {
				$classes[] = 'menu__item--active';
			}
		}

		// Extra check for custom post type archive
		if (is_post_type_archive()) {
			$post_type     = get_post_type();
			$archive_url   = get_post_type_archive_link($post_type);
			$current_url   = home_url(add_query_arg([], $wp->request));
			$item_url_base = rtrim($item->url, '/');
		
			// Match against full archive URL or path
			if (
				rtrim($archive_url, '/') === $item_url_base ||  // exact archive URL
				untrailingslashit($item_url_base) === untrailingslashit($current_url) // matches request path
			) {
				$classes[] = 'menu__item--active';
			}
		}
		




		if ($args->walker->has_children) {
			$classes[] = 'menu__item--parent has-toggle';
		}

		/**
		 * Filters the arguments for a single nav menu item.
		 *
		 * @since 4.4.0
		 *
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param WP_Post  $item  Menu item data object.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$args = apply_filters('nav_menu_item_args', $args, $item, $depth);

		/**
		 * Filters the CSS classes applied to a menu item's list item element.
		 *
		 * @since 3.0.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string[] $classes Array of the CSS classes that are applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */

		$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
		$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

		/**
		 * Filters the ID applied to a menu item's list item element.
		 *
		 * @since 3.0.1
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param string   $menu_id The ID that is applied to the menu item's `<li>` element.
		 * @param WP_Post  $item    The current menu item.
		 * @param stdClass $args    An object of wp_nav_menu() arguments.
		 * @param int      $depth   Depth of menu item. Used for padding.
		 */
		// $id = apply_filters( 'nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth );
		// $id = $id ? ' id="' . esc_attr( $id ) . '"' : '';

		$output .= $indent . sprintf( '<%s%s>', $this->list_item_tag, $class_names );

		$atts = array(
			'title' => $item->attr_title ?? '',
			'target' => $item->target ?? '',
			'href' => $item->url ?? '',
			'aria-current' => $item->current ? 'page' : '',
			'rel' => '_blank' === $item->target && empty($item->xfn) ? 'noopener noreferrer' : $item->xfn,
		);

		/**
		 * Filters the HTML attributes applied to a menu item's anchor element.
		 *
		 * @since 3.6.0
		 * @since 4.1.0 The `$depth` parameter was added.
		 *
		 * @param array $atts {
		 *     The HTML attributes applied to the menu item's `<a>` element, empty strings are ignored.
		 *
		 *     @type string $title        Title attribute.
		 *     @type string $target       Target attribute.
		 *     @type string $rel          The rel attribute.
		 *     @type string $href         The href attribute.
		 *     @type string $aria_current The aria-current attribute.
		 * }
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

		$attributes = '';
		foreach ($atts as $attr => $value) {
			if (!empty($value)) {
				$value       = ('href' === $attr) ? esc_url($value) : esc_attr($value);
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		/** This filter is documented in wp-includes/post-template.php */
		$title = apply_filters('the_title', $item->title, $item->ID);

		/**
		 * Filters a menu item's title.
		 *
		 * @since 4.4.0
		 *
		 * @param string   $title The menu item's title.
		 * @param WP_Post  $item  The current menu item.
		 * @param stdClass $args  An object of wp_nav_menu() arguments.
		 * @param int      $depth Depth of menu item. Used for padding.
		 */
		$title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

		$item_output = sprintf(
			'%s<a %s>%s%s%s</a>',
			$args->before,
			$attributes,
			$args->link_before,
			$title,
			$args->link_after
		);

		/*if ($this->add_submenu_toggle($args, $depth)) {
			$this->submenu_id = $args->menu_id . '-submenu-toggle-' . $item->ID;
			$item_output .= submenu_toggle($this->submenu_id, $title, 'js-submenu-toggle');
		}*/

		$item_output .= $args->after;

		/**
		 * Filters a menu item's starting output.
		 *
		 * The menu item's starting output only includes `$args->before`, the opening `<a>`,
		 * the menu item's title, the closing `</a>`, and `$args->after`. Currently, there is
		 * no filter for modifying the opening and closing `<li>` for a menu item.
		 *
		 * @since 3.0.0
		 *
		 * @param string   $item_output The menu item's starting HTML output.
		 * @param WP_Post  $item        Menu item data object.
		 * @param int      $depth       Depth of menu item. Used for padding.
		 * @param stdClass $args        An object of wp_nav_menu() arguments.
		 */

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	public function end_el(&$output, $item, $depth = 0, $args = array())
	{
		$output .= sprintf( '</%s>', $this->list_item_tag );

		if ( $this->discard_item_spacing($args) ) {
			$output .= "\n";
		}
	}

	public function walk( $elements, $max_depth, ...$args )
	{
		if ( $this->has_only_one_item( $elements ) ) {
			$this->wrap_item_in_div( true );
			add_filter( 'wp_nav_menu', array( $this, 'replace_menu_ul_with_div' ), 10, 2 );
		} else {
			$this->wrap_item_in_div( false );
		}

		return parent::walk( $elements, $max_depth, ...$args );
	}

	public function replace_menu_ul_with_div( string $nav_menu, \stdClass $args ): string
	{
		$ul_open = strpos( $nav_menu, '<ul' );
		if ( $ul_open !== false ) {
		    $nav_menu = $this->substring_replace( '<ul', $nav_menu, '<div', $ul_open );

			$ul_close = strrpos( $nav_menu, '</ul>' );
			if ( $ul_close !== false ) {
			    $nav_menu = $this->substring_replace( '</ul>', $nav_menu, '</div>', $ul_close );
			}
		}

		remove_filter( 'wp_nav_menu', array( $this, 'replace_menu_ul_with_div' ), 10, 2 );

		return $nav_menu;
	}

	private function substring_replace( string $needle, string $haystack, string $replace, int $offset ): string
	{
		return substr_replace( $haystack, $replace, $offset, strlen( $needle ) );
	}

	private function has_only_one_item( array $elements ): bool
	{
		return count( $elements) === 1;
	}

	private function wrap_item_in_div( bool $use_div ): void
	{
		if ( $use_div ) {
			$this->list_item_tag = 'div';
		} else {
			$this->list_item_tag = 'li';
		}
	}

	/*public function add_submenu_toggle($args, $depth)
	{
		$show_toggle = $args->depth ? $args->depth > $depth + 1 : true;
		return $args->walker->has_children && $show_toggle;
	}*/

	public function discard_item_spacing($args)
	{
		return isset($args->item_spacing) && 'discard' === $args->item_spacing;
	}
}

class Opehuone_Submenu_Walker extends Walker_Nav_Menu {

    private $parent_id = 0;

    public function __construct() {
		$locations = get_nav_menu_locations();
	
		if (!isset($locations['main_menu'])) {
			return;
		}
	
		$menu = wp_get_nav_menu_object($locations['main_menu']);
		$menu_items = wp_get_nav_menu_items($menu->term_id);
		$current_url = trailingslashit(home_url(add_query_arg([], $_SERVER['REQUEST_URI'])));
		
		$current_item_id = 0;
		$parent_item_id = 0;
	
		// First try to match by object_id (for pages/posts)
		$queried_id = get_queried_object_id();
		foreach ($menu_items as $item) {
			if ((int)$item->object_id === $queried_id && $item->object !== 'custom') {
				$current_item_id = $item->ID;
				break;
			}
		}
	
		// If no match, try matching by URL (important for archives)
		if (!$current_item_id) {
			foreach ($menu_items as $item) {
				if (trailingslashit($item->url) === $current_url) {
					$current_item_id = $item->ID;
					break;
				}
			}
		}
	
		// Walk up to top-level parent
		if ($current_item_id) {
			foreach ($menu_items as $item) {
				if ($item->ID === $current_item_id && $item->menu_item_parent != 0) {
					$parent_item_id = $item->menu_item_parent;
					break;
				}
			}
	
			if ($parent_item_id === 0) {
				$parent_item_id = $current_item_id;
			}
	
			$this->parent_id = $parent_item_id;
		}
	}
	
	
	

    public function start_el(&$output, $item, $depth = 0, $args = [], $id = 0) {
        if ($depth !== 1 || $item->menu_item_parent != $this->parent_id) return;

        $classes = ['menu__item'];
        if (in_array('current-menu-item', $item->classes)) {
            $classes[] = 'menu__item--active';
        }

        $output .= '<li class="' . esc_attr(implode(' ', $classes)) . '">';
        $output .= '<a href="' . esc_url($item->url) . '" class="menu__link">' . esc_html($item->title) . '</a>';
    }

    public function end_el(&$output, $item, $depth = 0, $args = []) {
        if ($depth !== 1 || $item->menu_item_parent != $this->parent_id) return;
        $output .= '</li>';
    }
}
