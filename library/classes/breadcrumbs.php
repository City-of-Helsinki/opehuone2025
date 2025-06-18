<?php

use const Opehuone\CustomPosts\Training\TRAINING_SLUG;

/**
 * Opehuone Breadbcrumbs
 *
 * Original author Dominik Schilling
 * https://gist.github.com/ocean90/1225412
 *
 */
class Opehuone_Breadcrumbs {


	private static $instance = null;
	/**
	 * The list of breadcrumb items.
	 *
	 * @var array
	 * @since 1.0.0
	 */
	public $breadcrumb;
	/**
	 * Templates for link, current/standard state and before/after.
	 *
	 * @var array
	 */
	public $templates;
	/**
	 * Various strings.
	 *
	 * @var array
	 */
	public $strings;
	/**
	 * Various options.
	 *
	 * @var array
	 * @access public
	 */
	public $options;

	/**
	 * Constructor.
	 *
	 * @param array $templates An array with templates for link, current/standard state and before/after.
	 * @param array $options An array with options.
	 * @param array $strings An array with strings.
	 * @param bool $autorun Autorun or not.
	 *
	 * @return string
	 */
	private function __construct( $templates = [], $options = [], $strings = [], $autorun = true ) {
		$separator       = '>';
		$this->templates = wp_parse_args(
			$templates,
			[
				'link'     => '<a href="%s" class="breadcrumbs__link">%s</a>',
				'current'  => '<li class="breadcrumbs__list-item breadcrumbs__list-item--active">%s</li>',
				'standard' => '<li class="breadcrumbs__list-item">%s</li>',
				'before'   => '<nav aria-label="' . esc_attr__( 'Muropolku', 'helsinki-universal' ) . '"><ol id="breadcrumbs" class="breadcrumbs">',
				'after'    => '<li class="breadcrumbs__divider breadcrumbs__divider--last">' . $separator . '</li></ol></nav>',
			]
		);
		$this->options   = wp_parse_args( $options, [
			'separator'      => '<li class="breadcrumbs__divider" aria-hidden="true">' . $separator . '</li>',
			'posts_on_front' => 'posts' === get_option( 'show_on_front' ) ? true : false,
			'page_for_posts' => get_option( 'page_for_posts' ),
			'show_pagenum'   => true, // support pagination
			'show_htfpt'     => false, // show hierarchical terms for post types
			'home_title'     => esc_html__( 'Etusivu', 'helsinki-universal' ),
		] );
		$this->strings   = wp_parse_args( $strings, [
			'home'      => $this->options['home_title'],
			'search'    => [
				'singular' => '<em>%s</em>',
				'plural'   => '<em>%s</em>',
			],
			'paged'     => 'Page %d',
			'404_error' => esc_html__( 'Sivua ei löytynyt', 'helsinki-universal' ),
		] );

		return;

	}

	public static function get_instance( $options = [] ) {
		if ( null == self::$instance ) {
			self::$instance = new self( [], $options );
		}


		return self::$instance;

	}

	/**
	 * Return the final breadcrumb.
	 *
	 * @return string
	 */
	public function output() {

		if ( empty( $this->breadcrumb ) ) {
			$this->generate();
		}

		// Add home svg icon to breadcrumb
		if ( strpos( $this->breadcrumb['home'], 'icon-home-breadcrumb' ) === false ) {
			// Home icon disabled
		}

		$separator_svg = '<svg width="6" height="10" viewBox="0 0 6 10" fill="none" xmlns="http://www.w3.org/2000/svg">
<path fill-rule="evenodd" clip-rule="evenodd" d="M3.9974 5.00033L0.664062 1.66699L1.66406 0.666992L5.9974 5.00033L1.66406 9.33366L0.664062 8.33366L3.9974 5.00033Z" fill="#1A1A1A"/>
</svg>';

		$separator_with_html = sprintf( '<li class="breadcrumbs__divider">%s</li>', $separator_svg );

		$breadcrumb = (string) implode( $separator_with_html, $this->breadcrumb );

		return $this->templates['before'] . $breadcrumb . $this->templates['after'];

	}

	/**
	 * Generate the breadcrumb.
	 *
	 * @return void
	 */
	public function generate() {
		$post_type      = get_post_type();
		$queried_object = get_queried_object();
		$is_home        = is_home();
		$is_singular    = is_singular();

		$this->options['show_pagenum'] = ( $this->options['show_pagenum'] && is_paged() ) ? true : false;


		// Home & Front Page
		$home_url                 = function_exists( 'pll_home_url' ) ? pll_home_url() : home_url( '/' );
		$this->breadcrumb['home'] = $this->template( $this->strings['home'], 'current' );
		$home_linked              = $this->template( [
			'link'  => $home_url,
			'title' => $this->strings['home'],
		] );


		if ( $this->options['posts_on_front'] ) {
			if ( ! $is_home || $this->options['show_pagenum'] ) {
				$this->breadcrumb['home'] = $home_linked;
			}
		} else {
			if ( ! is_front_page() ) {
				$this->breadcrumb['home'] = $home_linked;
			}

			if ( $is_home && ! $this->options['show_pagenum'] ) {
				$this->breadcrumb['blog'] = $this->template( get_the_title( $this->options['page_for_posts'] ), 'current' );
			}

			if ( ( 'post' == $post_type && ! is_search() && ! $is_home ) || ( 'post' == $post_type && $this->options['show_pagenum'] ) ) {
				$this->breadcrumb['blog'] = $this->template( [
					'link'  => get_permalink( $this->options['page_for_posts'] ),
					'title' => get_the_title( $this->options['page_for_posts'] ),
				] );
			}
		}

		// Post Type Archive as index.
		if ( ( $is_singular && 'post' !== $post_type ) || ( is_archive() && ! is_post_type_archive() && $is_home ) || is_search() || $this->options['show_pagenum'] ) {
			if ( $post_type_link = get_post_type_archive_link( $post_type ) ) {
				$post_type_label = $this->get_archive_page_breadcrumb_label( $post_type, TRAINING_SLUG, 'trainings_page' );

				$this->breadcrumb["archive_{$post_type}"] = $this->template(
					[
						'link'  => $post_type_link,
						'title' => $post_type_label,
					]
				);
			}
		}

		if ( $is_singular ) { // Posts, (Sub)Pages, Attachments and Custom Post Types
			if ( ! is_front_page() ) {
				if ( $this->options['show_htfpt'] ) {
					$_id        = $queried_object->ID;
					$_post_type = $post_type;

					if ( is_attachment() ) {
						// Show terms of the parent page
						$_id        = $queried_object->post_parent;
						$_post_type = get_post_type( $_id );
					}

					$taxonomies = get_object_taxonomies( $_post_type, 'objects' );
					$taxonomies = array_values( wp_list_filter( $taxonomies, [
						'hierarchical' => true,
					] ) );

					if ( ! empty( $taxonomies ) ) {
						$taxonomy = $taxonomies[0]->name; // Get the first taxonomy
						$terms    = get_the_terms( $_id, $taxonomy );

						if ( ! empty( $terms ) ) {
							$terms = array_values( $terms );
							$term  = $terms[0]; // Get the first term

							if ( 0 != $term->parent ) {
								$this->generate_tax_parents( $term->term_id, $taxonomy );
							}
							$this->breadcrumb["archive_{$taxonomy}"] = $this->template(
								[
									'link'  => get_term_link( $term->slug, $taxonomy ),
									'title' => $term->name,
								]
							);
						}
					}
				}

				if ( 0 != $queried_object->post_parent ) { // Get Parents
					$parents = array_reverse( get_post_ancestors( $queried_object->ID ) );
					foreach ( $parents as $parent ) {
						$this->breadcrumb["archive_{$post_type}_{$parent}"] = $this->template(
							[
								'link'  => get_permalink( $parent ),
								'title' => get_the_title( $parent ),
							]
						);
					}
				}

				if ( is_singular( 'tribe_events' ) ) {
					$queried_object                           = get_queried_object();
					$obj                                      = get_post_type_object( 'tribe_events' );
					$this->breadcrumb['archive_tribe_events'] = $this->template(
						[
							'link'  => get_post_type_archive_link( $obj->name ),
							'title' => $obj->labels->name,
						]
					);
					$this->breadcrumb['single_tribe_event']   = $this->template( $queried_object->post_title, 'current' );
				} else {
					$this->breadcrumb["single_{$post_type}"] = $this->template( get_the_title(), 'current' );

				}
			}
		} elseif ( is_search() ) { // Search
			$total = $GLOBALS['wp_query']->found_posts;
			$text  = sprintf(
				_n(
					$this->strings['search']['singular'],
					$this->strings['search']['plural'],
					$total
				),
				$total,
				get_search_query()
			);

			$this->breadcrumb['search'] = $this->template( $text, 'current' );

			if ( $this->options['show_pagenum'] ) {
				$this->breadcrumb['search'] = $this->template( [
					'link'  => home_url( '?s=' . urlencode( get_search_query( false ) ) ),
					'title' => $text,
				] );
			}
		} elseif ( is_archive() ) { // All archive pages
			if ( is_category() || is_tag() || is_tax() ) { // Categories, Tags and Custom Taxonomies
				$taxonomy = $queried_object->taxonomy;

				// Get Parents
				if ( 0 != $queried_object->parent && is_taxonomy_hierarchical( $taxonomy ) ) {
					$this->generate_tax_parents( $queried_object->term_id, $taxonomy );
				}

				$this->breadcrumb["archive_{$taxonomy}"] = $this->template( $queried_object->name, 'current' );

				if ( $this->options['show_pagenum'] ) {
					$this->breadcrumb["archive_{$taxonomy}"] = $this->template(
						[
							'link'  => get_term_link( $queried_object->slug, $taxonomy ),
							'title' => $queried_object->name,
						]
					);
				}
			} elseif ( is_date() ) { // Date archive
				if ( is_year() ) { // Year archive
					$this->breadcrumb['archive_year'] = $this->template( get_the_date( 'Y' ), 'current' );

					if ( $this->options['show_pagenum'] ) {
						$this->breadcrumb['archive_year'] = $this->template( [
							'link'  => get_year_link( get_query_var( 'year' ) ),
							'title' => get_the_date( 'Y' ),
						] );
					}
				} elseif ( is_month() ) { // Month archive
					$this->breadcrumb['archive_year']  = $this->template( [
						'link'  => get_year_link( get_query_var( 'year' ) ),
						'title' => get_the_date( 'Y' ),
					] );
					$this->breadcrumb['archive_month'] = $this->template( get_the_date( 'F' ), 'current' );

					if ( $this->options['show_pagenum'] ) {
						$this->breadcrumb['archive_month'] = $this->template( [
							'link'  => get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) ),
							'title' => get_the_date( 'F' ),
						] );
					}
				} elseif ( is_day() ) { // Day archive
					$this->breadcrumb['archive_year']  = $this->template( [
						'link'  => get_year_link( get_query_var( 'year' ) ),
						'title' => get_the_date( 'Y' ),
					] );
					$this->breadcrumb['archive_month'] = $this->template( [
						'link'  => get_month_link( get_query_var( 'year' ), get_query_var( 'monthnum' ) ),
						'title' => get_the_date( 'F' ),
					] );
					$this->breadcrumb['archive_day']   = $this->template( get_the_date( 'j' ) );

					if ( $this->options['show_pagenum'] ) {
						$this->breadcrumb['archive_day'] = $this->template( [
							'link'  => get_month_link(
								get_query_var( 'year' ),
								get_query_var( 'monthnum' ),
								get_query_var( 'day' )
							),
							'title' => get_the_date( 'F' ),
						] );
					}
				}
			} elseif ( is_post_type_archive() && ! is_paged() ) { // Custom Post Type Archive
				if ( is_post_type_archive( 'tribe_events' ) ) {
					$queried_object                              = get_queried_object();
					$this->breadcrumb['archive_events_calendar'] = $this->template( $queried_object->labels->name, 'current' );
				} else {
                    $post_type_label = $this->get_archive_page_breadcrumb_label( $post_type, TRAINING_SLUG, 'trainings_page');

                    $this->breadcrumb["archive_{$post_type}"] = $this->template( $post_type_label, 'current' );
				}
			} elseif ( is_author() ) { // Author archive
				$this->breadcrumb['archive_author'] = $this->template( $queried_object->display_name, 'current' );
			}
		} elseif ( is_404() ) {
			$this->breadcrumb['404'] = $this->template( $this->strings['404_error'], 'current' );
		}

		if ( $this->options['show_pagenum'] ) {
			$this->breadcrumb['paged'] = $this->template(
				sprintf(
					$this->strings['paged'],
					get_query_var( 'paged' )
				),
				'current'
			);
		}
	}

	/**
	 * Build the item based on the type.
	 *
	 * @param string|array $item
	 * @param string $type
	 *
	 * @return string
	 */
	protected function template( $item, $type = 'standard' ) {
		if ( is_array( $item ) ) {
			$type = 'link';
		}

		switch ( $type ) {
			case 'link':
				return $this->template(
					sprintf(
						$this->templates['link'],
						esc_url( $item['link'] ),
						esc_html__( $item['title'], 'helsinki-universal' )
					)
				);
				break;
			case 'current':
				return sprintf( $this->templates['current'], $item );
				break;
			case 'standard':
				return sprintf( $this->templates['standard'], $item );
				break;
		}
	}

	/**
	 * Helper to generate taxonomy parents.
	 *
	 * @param mixed $term_id
	 * @param mixed $taxonomy
	 *
	 * @return void
	 */
	protected function generate_tax_parents( $term_id, $taxonomy ) {
		$parent_ids = array_reverse( get_ancestors( $term_id, $taxonomy ) );

		foreach ( $parent_ids as $parent_id ) {
			$term                                                 = get_term( $parent_id, $taxonomy );
			$this->breadcrumb["archive_{$taxonomy}_{$parent_id}"] = $this->template(
				[
					'link'  => get_term_link( $term->slug, $taxonomy ),
					'title' => $term->name,
				]
			);
		}
	}

    /**
     * @param mixed $post_type
     *
     * @return string
     *
     */
    public function get_archive_page_breadcrumb_label(mixed $post_type, string $taxonomy_slug, string $acf_field): string
    {
        // NOTE: This function could be made more dynamic by setting taxonomy slugs and acf_field values to an array of some sort,
        // then we could map to these values based on post_type. Then the function could only contain the $post_type parameter
        // $post_types = array(
        //  'training' => array(
        //  'taxonomy' => 'trainings_page',
        //  'acf_option' => 'trainings_page'
        //  ),
        // ...
        // );
        $post_type_object = get_post_type_object( $post_type );
        $post_type_label = $post_type_object->labels->name;

        if ( $post_type_object->name === $taxonomy_slug ) {
            $page_id = get_field( $acf_field, 'option' );

            if ( $page_id ) {
                $post_type_label = get_the_title( $page_id );
            }
        }
        return $post_type_label;
    }
}
