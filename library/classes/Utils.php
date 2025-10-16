<?php

namespace LuuptekWP;

use function Opehuone\Helpers\asset_local;
use function Opehuone\Helpers\the_svg;



class Utils {

    /**
     * Display navigation to next/previous pages when applicable
     *
     * @param $nav_id
     */
    function content_nav( $nav_id ) {
        global $wp_query, $post;

        if ( is_single() ) {
            $previous = ( is_attachment() ) ? get_post( $post->post_parent ) : get_adjacent_post( false, '', true );
            $next     = get_adjacent_post( false, '', false );

            if ( ! $next && ! $previous ) {
                return;
            }
        }

        if ( $wp_query->max_num_pages < 2 && ( is_home() || is_archive() || is_search() ) ) {
            return;
        }

        $nav_class = ( is_single() ) ? 'post-navigation' : 'paging-navigation';

        ?>
        <nav role="navigation" id="<?php echo esc_attr( $nav_id ); ?>" class="<?php echo $nav_class; ?>">
            <ul class="pager">
                <?php if ( is_single() ) : ?>
                    <?php previous_post_link( '<li class="nav-previous previous">%link</li>',
                        '<span class="meta-nav">' . _x( '&larr;', 'Previous post link',
                            TEXT_DOMAIN ) . '</span> %title' ); ?>
                    <?php next_post_link( '<li class="nav-next next">%link</li>',
                        '%title <span class="meta-nav">' . _x( '&rarr;', 'Next post link',
                            TEXT_DOMAIN ) . '</span>' ); ?>

                <?php elseif ( $wp_query->max_num_pages > 1 && ( is_home() || is_archive() || is_search() ) ) : // navigation links for home, archive, and search pages ?>
                    <?php if ( get_next_posts_link() ) : ?>
                        <li class="nav-previous previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Vanhemmat julkaisut',
                                TEXT_DOMAIN ) ); ?></li>
                    <?php endif; ?>
                    <?php if ( get_previous_posts_link() ) : ?>
                        <li class="nav-next next"><?php previous_posts_link( __( 'Uudemmat julkaisut <span class="meta-nav">&rarr;</span>',
                                TEXT_DOMAIN ) ); ?></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </nav>
        <?php
    }

    /**
     * Get pagination
     */
    public function pagination() {
        if ( is_singular() ) {
            return;
        }

        global $wp_query;
        $paged             = get_query_var( 'paged' ) ? absint( get_query_var( 'paged' ) ) : 1;
        $max               = intval( $wp_query->max_num_pages );
        $default_classname = 'pagination--item';

        if ( $wp_query->max_num_pages <= 1 ) {
            return;
        }

        if ( $paged >= 1 ) {
            $links[] = $paged;
        }

        if ( $paged >= 3 ) {
            $links[] = $paged - 1;
            $links[] = $paged - 2;
        }

        if ( ( $paged + 2 ) <= $max ) {
            $links[] = $paged + 2;
            $links[] = $paged + 1;
        }

        echo '<ul class="pagination__list">' . "\n";

        if ( get_previous_posts_link() ) {
            printf( '<li class="post--link pagination--item">%s</li>' . "\n",
                get_previous_posts_link( '<i class="fa fa-angle-left"></i>' ) );
        }

        if ( ! in_array( 1, $links ) ) {
            $class = 1 == $paged ? ' class="pagination--item current"' : ' class="pagination--item"';

            printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( 1 ) ), '1' );

            if ( ! in_array( 2, $links ) ) {
                echo '<li class="pagination--item"><a href="#">&hellip;</a></li>';
            }
        }

        sort( $links );
        foreach ( (array) $links as $link ) {
            $class = $paged == $link ? ' class="pagination--item current"' : ' class="pagination--item"';
            printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $link ) ), $link );
        }

        if ( ! in_array( $max, $links ) ) {
            if ( ! in_array( $max - 1, $links ) ) {
                echo '<li class="pagination--item"><a href="#">&hellip;</a></li>' . "\n";
            }

            $class = $paged == $max ? ' class="current pagination--item"' : ' class="pagination--item"';
            printf( '<li%s><a href="%s">%s</a></li>' . "\n", $class, esc_url( get_pagenum_link( $max ) ), $max );
        }

        if ( get_next_posts_link() ) {
            printf( '<li class="post--link pagination--item">%s</li>' . "\n",
                get_next_posts_link( '<i class="fa fa-angle-right"></i>' ) );
        }

        echo '</ul>' . "\n";
    }

    /**
     * Get custom CPTs
     *
     * @return array
     */
    public function get_custom_post_types() {
        return get_post_types( [
            'public'   => true,
            '_builtin' => false
        ] );
    }

    /**
     * Get first category-item
     *
     * @param string $taxonomy
     *
     * @return mixed
     */
    public function get_category( $taxonomy = 'category' ) {
        $categories = wp_get_object_terms( get_the_ID(), $taxonomy );

        return ! empty( $categories ) ? wp_get_object_terms( get_the_ID(), $taxonomy )[0] : null;
    }

    /**
     * Get whole category-hierarchy
     *
     * @param string $taxonomy
     *
     * @return array
     */
    public function get_category_hierarchy( $taxonomy = 'category' ) {

        $cats     = [];
        $category = wp_get_object_terms( get_the_ID(), $taxonomy )[0];
        $cat_tree = get_ancestors( $category->term_id, $taxonomy );
        array_push( $cat_tree, $category->term_id );
        asort( $cat_tree );

        foreach ( $cat_tree as $cat ) {
            $cats[] = get_term_by( 'id', $cat, $taxonomy );
        }

        return $cats;
    }

    /**
     * Get parent-most category
     *
     * @param string $taxonomy
     *
     * @return mixed
     */
    public function get_parent_category( $taxonomy = 'category' ) {
        $cats = self::get_category_hierarchy( $taxonomy );

        return $cats[0];
    }

    /**
     * Get build images uri
     *
     * @return string
     */
    public function get_image_uri() {
        return asset_uri( 'images' );
    }

    /**
     * Get svg-image
     *
     * @return string
     */
    public function get_svg_image_url( $file_name ) {
        return asset_uri( 'images' ) . '/' . $file_name . '.svg';
    }

    /**
     * Echoes svg directly from images-folder
     *
     * @param $file_name
     */
    function the_svg( $file_name ) {
        readfile( asset_local( 'images' ) . '/' . $file_name . '.svg' );
    }

    /**
     * Get default image
     *
     * @param string $size
     *
     * @return array|false
     */
    public function get_default_image( $size = 'full' ) {
        $option   = get_option( 'options_post_default_image' );
        $image_id = isset( $option ) ? $option : null;

        return wp_get_attachment_image_src( $image_id, $size )[0];
    }

    /**
     * Get first paragraph from text content.
     *
     * @param $text
     *
     * @return string
     */
    public function get_first_paragraph( $text ) {
        $str = wpautop( $text );
        $str = substr( $str, 0, strpos( $str, '</p>' ) + 4 );
        $str = strip_tags( $str, '<a><strong><em>' );
        $str = preg_replace( "/\[.*\]\s*/", "", $str );

        return '<p>' . $str . '</p>';
    }

    /**
     * Retrive post thumbnail (featured image) if defined,
     * if not, retrieve default post image that's defined in theme settings
     *
     * @param string $size
     * @param null $postId
     *
     * @return false|string
     */
    public function get_the_featured_image_url( $size = 'full', $postId = null ) {
        $featuredImageUrl = get_the_post_thumbnail_url( $postId, $size );

        if ( $featuredImageUrl ) {
            return $featuredImageUrl;
        } else {
            return $this->get_default_image( $size );
        }
    }

    /**
     * Return post type name by post id
     *
     * @param $post_id
     * @param string $name (can be set to name or singular_name)
     *
     * @return mixed
     */
    function get_post_type_name_by_post( $post_id, $name = 'name' ) {
        $post_type        = get_post_type( $post_id );
        $post_type_object = get_post_type_object( $post_type );

        return $post_type_object->labels->{$name};
    }

    function get_page_theme_color() {

        $colors = [
            'fog'         => '#9fc9eb',
            'copper'      => '#00d7a7',
            'suomenlinna' => '#f5a3c7',
            'coat'        => '#0072c6',
            'silver'      => '#dedfe1',
        ];

        $color_theme = get_field( 'header_color' );

        if ( $color_theme ) {
            return [
                'color_name' => $color_theme,
                'color_hex'  => $colors[ $color_theme ],
            ];
        }


    }

    function get_tiedotteet_page_link() {
        $announcements_link = get_permalink( get_option( 'page_for_posts' ) );

        return $announcements_link;
    }

    function get_current_day_and_date() {
        //var_dump(get_locale());
        $day = new \DateTime();
        $day->setTimezone( new \DateTimeZone( 'Europe/Helsinki' ) );
        $string = __( 'Tänään on', TEXT_DOMAIN ) . ' ';
        //$string .= setlocale('LC_TIME', 0);
        $week_number = ' (vk ' . $day->format( 'W' ) . ') ';

        $string .= date_i18n( 'l' ) . ' ' . $day->format( 'd.m.Y' ) . $week_number . __( 'ja kello on', TEXT_DOMAIN ) . ' <span id="current-time"></span>';

        return $string;
    }

    function get_current_date_minified() {
        $day = new \DateTime();
        $day->setTimezone( new \DateTimeZone( 'Europe/Helsinki' ) );
        $week_number = $day->format( 'W' );

        $string = '<span class="week">' . __( 'viikko', TEXT_DOMAIN ) . ' ' . $week_number . '</span> ' . '<span class="date">' . date_i18n( 'D' ) . ' ' . $day->format( 'j.n.' ) . '</span>' . ' ' . '<span class="header-the-time">klo ' . ' <span id="current-time"></span></span><br>';

        return $string;
    }

    function is_current_category( $term_id ) {
        $current = false;

        if ( is_category() ) {
            $queried_object = get_queried_object();

            if ( $queried_object->term_id === $term_id ) {
                $current = true;
            }
        }

        return $current;
    }

    function get_user_favs() {
        if ( is_user_logged_in() ) {
            return Utils()->get_favs_from_user_meta();
        } else {
            return Utils()->get_favs_from_cache();
        }
    }

    function get_favs_from_cache() {
        if ( isset( $_COOKIE['opehuone_favs'] ) ) {
            $cookie      = $_COOKIE['opehuone_favs'];
            $cookie      = stripslashes( $cookie );
            $posts_array = json_decode( $cookie );

            return $posts_array;

        } else {
            return [];
        }
    }

    function get_favs_from_user_meta() {
        $user = wp_get_current_user();

        $posts = get_user_meta( $user->ID, 'opehuone_favs', true ) ? get_user_meta( $user->ID, 'opehuone_favs', true ) : [];

        return $posts;
    }

    function get_user_data_meta() {
        $user = wp_get_current_user();
        $meta = get_user_meta( $user->ID, 'user_data', true ) ? get_user_meta( $user->ID, 'user_data', true ) : 'not found';

        return $meta;
    }

    function user_data_meta_exists() {
        if ( ! is_user_logged_in() ) {
            return false;
        }

        $user = wp_get_current_user();
        $meta = get_user_meta( $user->ID, 'user_data', true );

        if ( ! empty( $meta ) ) {
            return true;
        }

        return false;
    }

    function get_number_of_posts_since( $year = '1970', $month = '1', $day = '1' ) {
        $args = [
            'post_type'      => 'post',
            'posts_per_page' => - 1,
            'date_query'     => [
                'after'     => [
                    'year'  => $year,
                    'month' => $month,
                    'day'   => $day,
                ],
                'inclusive' => false,
            ],
        ];

        $posts = get_posts( $args );

        $number = count( $posts ) > 99 ? '99+' : count( $posts );

        return $number;
    }

    function get_last_visited_archive_year() {
        if ( isset( $_COOKIE['last_visited_archive_year'] ) ) {
            return $_COOKIE['last_visited_archive_year'];
        } else {
            return '1970';
        }
    }

    function get_last_visited_archive_month() {
        if ( isset( $_COOKIE['last_visited_archive_month'] ) ) {
            return $_COOKIE['last_visited_archive_month'];
        } else {
            return '1';
        }
    }

    function get_last_visited_archive_day() {
        if ( isset( $_COOKIE['last_visited_archive_day'] ) ) {
            return $_COOKIE['last_visited_archive_day'];
        } else {
            return '1';
        }
    }

    function display_fav_handler() {
        if ( is_single() || ( is_page() && ! get_field( 'hide_fav_control' ) ) ) {
            return true;
        } else {
            return false;
        }
    }

    function display_share_handler() {
        if ( is_single() || ( is_page() && ! get_field( 'hide_share_control' ) ) ) {
            return true;
        } else {
            return false;
        }
    }

    function display_post_actions() {
        if ( Utils()->display_fav_handler() || Utils()->display_share_handler() ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * to be used to check or get cornerlabels for post
     *
     * @return bool|false|\WP_Error|\WP_Term[]
     */
    function has_post_cornerlabels() {
        $returning = false;
        if ( is_single() ) {
            $terms = get_the_terms( get_the_ID(), 'cornerlabels' );
            if ( ! is_wp_error( $terms ) ) {
                if ( $terms ) {
                    return $terms;
                }
            }
        }

        return $returning;
    }

    /**
     * Get terms with a specific non-empty meta key
     *
     * @param string $meta_key The meta key to check for.
     * @param string $taxonomy The taxonomy slug or `null` to query all public taxonomies
     *
     * @return array|bool(false)
     */
    function get_terms_with_meta_value( $meta_key, $meta_value, $taxonomy = null ) {
        /* Do Query
        ----------------*/
        $term_args = array(
            'taxonomy'     => $taxonomy,
            'hide_empty'   => false,
            'fields'       => 'all',
            'count'        => true,
            'meta_key'     => $meta_key,
            'meta_value'   => $meta_value,
            'meta_compare' => '=',
        );

        $term_query = new \WP_Term_Query( $term_args );

        /* Do we have any terms?
        -----------------------------*/
        if ( empty( $term_query->terms ) ) {
            return false;
        }

        /* Get the terms :)
        ------------------------*/

        return $term_query->terms;
    }

    /**
     * Return internationalised number of phone number
     *
     * @param $unformatted_number
     *
     * @return bool|string
     */
    function get_internationalized_phone_number( $unformatted_number ) {
        $formmatted_number = substr( $unformatted_number, 1 ); //remove first zero
        $formmatted_number = '358' . str_replace( ' ', '', trim( $formmatted_number ) ); // add +358 and remove extra spce

        return $formmatted_number;
    }

    /**
     * Return full user name by user ID
     *
     * @param $user_id
     *
     * @return string
     */
    function get_user_full_name( $user_id ) {
        $first_name = get_user_meta( $user_id, 'first_name', true );
        $last_name  = get_user_meta( $user_id, 'last_name', true );

        return $first_name . ' ' . substr( $last_name, 0, 1 ) . '.';
    }

    function get_tiedotteet_page_shortcode_by_user_settings() {
        $shortcode        = 'ajax_load_more container_type="div" id="ajankohtaiset_query" post_type="post" posts_per_page="10" button_label="Lataa lisää" sticky_posts="true" scroll="false" button_loading_label="Ladataan"';
        $user_settings    = new \User_settings();
        $current_settings = $user_settings->get_user_settings();
        $categories       = $current_settings['what_to_show_categories']['category'];
        $cornerlabels     = $current_settings['what_to_show_categories']['cornerlabels'];
        $article_langs    = $current_settings['what_to_show_categories']['article_lang'];

        if ( is_array( $categories ) ) {
            $count             = count( $categories );
            $categories_string = '';
            $i                 = 1;
            if ( $count > 0 ) {
                foreach ( $categories as $category ) {
                    $term              = get_term( $category, 'category' );
                    if ($term !== null) {
                      $categories_string .= $term->slug;
                    }

                    if ( $i < $count ) {
                        $categories_string .= ',';
                    }

                    $i ++;
                }
            }

            if ( $categories_string !== '' ) {
                $shortcode .= ' category="' . $categories_string . '"';
            }
        }

        if ( is_array( $cornerlabels ) ) {
            $count               = count( $cornerlabels );
            $cornerlabels_string = '';
            $i                   = 1;
            if ( $count > 0 ) {
                foreach ( $cornerlabels as $cornerlabel ) {
                    $term                = get_term( $cornerlabel, 'cornerlabels' );
                    if ($term !== null) {
                      $cornerlabels_string .= $term->slug;
                    }

                    if ( $i < $count ) {
                        $cornerlabels_string .= ',';
                    }

                    $i ++;
                }
            }
        }

        if ( is_array( $article_langs ) ) {
            $count                = count( $article_langs );
            $article_langs_string = '';
            $i                    = 1;
            if ( $count > 0 ) {
                foreach ( $article_langs as $article_lang ) {
                    $term                 = get_term( $article_lang, 'article_lang' );
                    if ($term !== null) {
                      $article_langs_string .= $term->slug;
                    }

                    if ( $i < $count ) {
                        $article_langs_string .= ',';
                    }

                    $i ++;
                }
            }
        }

        $taxonomies = '';

        if ( ! empty( $cornerlabels_string ) ) {
            $taxonomies          = 'cornerlabels';
            $taxonomies_operator = 'IN';
            $taxonomy_terms      = $cornerlabels_string;
        }

        if ( ! empty( $article_langs_string ) ) {
            if ( ! empty( $cornerlabels_string ) ) {
                $taxonomies          = 'cornerlabels:article_lang';
                $taxonomies_operator = 'IN:IN';
                $taxonomy_terms      = $cornerlabels_string . ':' . $article_langs_string;
            } else {
                $taxonomies          = 'article_lang';
                $taxonomies_operator = 'IN';
                $taxonomy_terms      = $article_langs_string;
            }
        }

        if ( ! empty( $taxonomies ) ) {
            $shortcode .= ' taxonomy="' . $taxonomies . '" taxonomy_terms="' . $taxonomy_terms . '" taxonomy_operator="' . $taxonomies_operator . '"';
        }

        return '[' . $shortcode . ']';
    }

    function get_search_page_alm_shortcode() {
        $shortcode            = 'ajax_load_more id="relevanssi" container_type="div" posts_per_page="10" button_label="Lataa lisää" scroll="false" button_loading_label="Ladataan"';
        $search_cornerlabels  = $_GET['search_cornerlabels'];
        if (isset($_GET['search_categories'])) {
          $search_categories    = $_GET['search_categories'];
        }
        $search_article_langs = $_GET['search_article_languages'];
        $search_post_ids      = $_GET['search_post_ids'];
        $search_post_types    = ! empty( $_GET['search_post_types'] ) ? $_GET['search_post_types'] : 'post,page,training';

        $shortcode .= ' search="' . $_GET['s'] . '"';

        if ( ! empty( $search_categories ) ) {
            $shortcode .= ' category="' . $search_categories . '"';
        }

        $taxonomies = '';

        if ( ! empty( $search_cornerlabels ) ) {
            $taxonomies          = 'cornerlabels';
            $taxonomies_operator = 'IN';
            $taxonomy_terms      = $search_cornerlabels;
        }

        if ( ! empty( $search_article_langs ) ) {
            if ( ! empty( $search_cornerlabels ) ) {
                $taxonomies          = 'cornerlabels:article_lang';
                $taxonomies_operator = 'IN:IN';
                $taxonomy_terms      = $search_cornerlabels . ':' . $search_article_langs;
            } else {
                $taxonomies          = 'article_lang';
                $taxonomies_operator = 'IN';
                $taxonomy_terms      = $search_article_langs;
            }
        }

        if ( ! empty( $taxonomies ) ) {
            $shortcode .= ' taxonomy="' . $taxonomies . '" taxonomy_terms="' . $taxonomy_terms . '" taxonomy_operator="' . $taxonomies_operator . '"';
        }

        if ( ! empty( $search_post_ids ) ) {
            $shortcode .= ' post__in="' . $search_post_ids . '"';
        }

        $shortcode .= ' post_type="' . $search_post_types . '"';

        return '[' . $shortcode . ']';
    }

    function get_koulutus_page_shortcode_by_user_settings() {
        $shortcode        = 'ajax_load_more orderby="meta_value_num" order="ASC" meta_type="NUMERIC" meta_key="training_start_date" container_type="div" post_type="training" posts_per_page="10" button_label="Lataa lisää" button_loading_label="Ladataan"';
        $user_settings    = new \User_settings();
        $current_settings = $user_settings->get_user_settings();
        $cornerlabels     = $current_settings['what_to_show_categories']['cornerlabels'];
        $article_langs    = $current_settings['what_to_show_categories']['article_lang'];

        if ( is_array( $cornerlabels ) ) {
            $count               = count( $cornerlabels );
            $cornerlabels_string = '';
            $i                   = 1;
            if ( $count > 0 ) {
                foreach ( $cornerlabels as $cornerlabel ) {
                    $term                = get_term( $cornerlabel, 'cornerlabels' );
                    if ($term !== null) {
                      $cornerlabels_string .= $term->slug;
                    }

                    if ( $i < $count ) {
                        $cornerlabels_string .= ',';
                    }

                    $i ++;
                }
            }
        }

        if ( is_array( $article_langs ) ) {
            $count                = count( $article_langs );
            $article_langs_string = '';
            $i                    = 1;
            if ( $count > 0 ) {
                foreach ( $article_langs as $article_lang ) {
                    $term                 = get_term( $article_lang, 'article_lang' );
                    if ($term !== null) {
                      $article_langs_string .= $term->slug;
                    }

                    if ( $i < $count ) {
                        $article_langs_string .= ',';
                    }

                    $i ++;
                }
            }
        }

        $taxonomies = '';

        if ( ! empty( $cornerlabels_string ) ) {
            $taxonomies          = 'cornerlabels';
            $taxonomies_operator = 'IN';
            $taxonomy_terms      = $cornerlabels_string;
        }

        if ( ! empty( $article_langs_string ) ) {
            if ( ! empty( $cornerlabels_string ) ) {
                $taxonomies          = 'cornerlabels:article_lang';
                $taxonomies_operator = 'IN:IN';
                $taxonomy_terms      = $cornerlabels_string . ':' . $article_langs_string;
            } else {
                $taxonomies          = 'article_lang';
                $taxonomies_operator = 'IN';
                $taxonomy_terms      = $article_langs_string;
            }
        }

        if ( ! empty( $taxonomies ) ) {
            $shortcode .= ' taxonomy="' . $taxonomies . '" taxonomy_terms="' . $taxonomy_terms . '" taxonomy_operator="' . $taxonomies_operator . '"';
        }

        return '[' . $shortcode . ']';
    }

    function get_all_term_ids_of_taxonomy( $taxonomy_name ) {
        $array = [];

        $terms = get_terms(
            [
                'taxonomy'   => $taxonomy_name,
                'hide_empty' => false,
            ]
        );

        if ( ! is_wp_error( $terms ) ) {
            foreach ( $terms as $term ) {
                $array[] = $term->term_id;
            }
        }

        return $array;
    }

    function get_training_nice_date( $start_date, $start_time = null, $end_date = null, $end_time = null ) {
        $is_multiday = false;

        if ( empty( $start_date ) ) {
            return null;
        }

        if ( $end_date !== $start_date && ! empty( $end_date ) ) {
            $is_multiday = true;
        }

        $string = '';

        if ( $is_multiday ) {
            $start_string = $start_date;

            if ( ! empty( $start_time ) ) {
                $start_string .= ' klo ' . $start_time;
            }

            if ( ! empty( $end_date ) ) {
                $string = $start_string . ' - ' . $end_date;
            }

            if ( ! empty( $end_time ) ) {
                $string .= ' klo ' . $end_time;
            }
        } else {
            $string = $start_date;

            if ( ! empty( $start_time ) ) {
                $string .= ' klo ' . $start_time;
            }

            if ( ! empty( $end_time ) ) {
                $string .= ' - ' . $end_time;
            }
        }

        return $string;
    }

    /**
     * Return user display name with or without last name
     *
     * @param bool $use_full_name
     *
     * @return false|string|void
     */
    function get_user_display_name( $use_full_name = false ) {
        if ( is_user_logged_in() ) {
            $current_user = wp_get_current_user();
            $name         = ! empty( get_user_meta( $current_user->ID, 'user_displayname', true ) ) ? get_user_meta( $current_user->ID, 'user_displayname', true ) : $current_user->display_name;

            if ( $use_full_name ) {
                return $name;
            } else {
                return substr( $name, 0, strrpos( $name, ' ' ) ); // no last name
            }
        } else {
            return __( 'Tuntematon', TEXT_DOMAIN );
        }
    }

    function get_o365_profile_pic_url( $user_id = null ) {
        if ( $user_id === null ) {
            return false;
        }

        $url_path = O365_PROFILE_IMG_PATH . $user_id . '.png';

        if ( $this->does_url_exists( $url_path ) ) {
            return $url_path;
        } else {
            return false;
        }
    }

    function does_url_exists( $url ) {
        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_NOBODY, true );
        curl_exec( $ch );
        $code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );

        if ( 200 === $code ) {
            $status = true;
        } else {
            $status = false;
        }
        curl_close( $ch );

        return $status;
    }

    /**
     * Get all subpages of current page imploded by comma
     *
     * @return string|null
     */
    function get_search_sub_pages() {
        if ( $this->is_training_page_or_subpage() ) {
            return null;
        }

        if ( ! is_singular( 'page' ) ) {
            return null;
        }

        $pages = get_pages( [ 'child_of' => $this->get_post_ancestor_id() ] );
        $ids   = [];
        foreach ( $pages as $page ) {
            array_push( $ids, $page->ID );
        }

        return implode( ',', $ids );
    }

    /**
     * Get post types needed by search
     *
     * @return string
     */
    function get_search_post_types() {
        if ( $this->is_training_page_or_subpage() ) {
            return 'training';
        }

        if ( is_singular( 'post' ) ) {
            return 'post,page';
        }

        if ( is_front_page() ) {
            return 'post,page,training';
        }

        return 'page';
    }

    /**
     * This function will get array with ids as a parameter
     *
     * It will create a new array with term names based by id
     *
     * @param $array
     *
     * @return array
     */
    function get_taxonomy_term_slugs_by_id( $array ) {
        if ( ! is_array( $array ) ) {
            return [];
        }

        $return_array = [];

        foreach ( $array as $value ) {
            $object    = get_term( $value );
            if ($object !== null) {
              $term_name = $object->slug;
            }
            array_push( $return_array, $term_name );
        }

        return $return_array;
    }

    function get_post_ancestor_id() {
        if ( ! is_singular( 'page' ) && ! is_singular( 'page' ) ) {
            return null;
        }
        global $post;
        $parents = get_post_ancestors( $post );
        $id      = $post->ID;
        /* Get the ID of the 'top most' Page */
        if ( ! empty( $parents ) ) {
            $id = array_pop( $parents );
        }

        return $id;
    }

    function get_search_placeholder() {
        $post_ancestor_id = $this->get_post_ancestor_id();

        if ( $post_ancestor_id === null ) {
            return __( 'Hae opehuoneesta', TEXT_DOMAIN );
        }

        $placeholder = get_field( 'opehuone_search_placeholder', $post_ancestor_id );

        if ( ! empty( $placeholder ) ) {
            return $placeholder;
        } else {
            return __( 'Hae opehuoneesta', TEXT_DOMAIN );
        }
    }

    function is_training_page_or_subpage() {
        if ( is_page_template( 'custom-templates/training.php' ) ) {
            return true;
        }

        $ancestor_id = $this->get_post_ancestor_id();

        if ( $ancestor_id ) {
            $ancentor_template = get_page_template_slug( $ancestor_id );

            if ( $ancentor_template === 'custom-templates/training.php' ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Helper to echo own services list
     *
     * @param bool $is_active To echo active or non active own services
     */
    function the_own_services_row( $is_active ) {
        $visible             = true === $is_active ? 1 : 0;
        $active_own_services = $this->get_user_own_services( $visible );

        foreach ( $active_own_services as $own_service ) {

            $args = [
                'title'                  => $own_service->service_name,
                'url'                    => $own_service->service_url,
                'own_service_id'         => $own_service->id,
                'own_service_identifier' => $own_service->identifier,
                'icon_url'               => '',
                'icon_alt'               => '',
                'active_service'         => $is_active,
            ];

            get_template_part( 'partials/blocks/b-whole-dock-item', '', $args );
        }
    }

    /**
     * Function to get user own services from DB
     *
     * @param int $visible Is service visible or not 0/1 (tinyint in db)
     *
     * @return array|object|null Results from SQL-query
     */
    function get_user_own_services( $visible = 1 ) {
        global $wpdb;
        $user_id = get_current_user_id();

        $table_name = "{$wpdb->prefix}user_own_services";
        $sql        = $wpdb->prepare( "SELECT * FROM `$table_name` WHERE visible = %d AND user_id = %d", $visible, $user_id );

        $results = $wpdb->get_results( $sql, OBJECT );

        return $results;
    }

    function get_oppiaste_and_training_theme_names_as_array_for_post( $post_id ) {
        $array = [];

        $post_terms = wp_get_post_terms( $post_id, 'cornerlabels' );

        if ( $post_terms ) {
            foreach ( $post_terms as $post_term ) {
                $color = ! empty( get_term_meta( $post_term->term_id, 'button_color_theme', true ) ) ? get_term_meta( $post_term->term_id, 'button_color_theme', true ) : 'silver';
                array_push( $array, [ 'name' => $post_term->name, 'theme' => $color ] );
            }
        }

        $post_terms = wp_get_post_terms( $post_id, 'training_theme' );

        if ( $post_terms ) {
            foreach ( $post_terms as $post_term ) {
                $color = ! empty( get_term_meta( $post_term->term_id, 'button_color_theme', true ) ) ? get_term_meta( $post_term->term_id, 'button_color_theme', true ) : 'silver';
                array_push( $array, [ 'name' => $post_term->name, 'theme' => $color ] );
            }
        }

        return $array;
    }

    function the_oppiaste_and_training_theme_tags_list( $post_id ) {
        $items        = $this->get_oppiaste_and_training_theme_names_as_array_for_post( $post_id );
        $num_of_items = count( $items );
        if ( $num_of_items === 0 ) {
            return;
        }

        ?>
        <ul class="category-tags">
            <?php
            foreach ( $items as $item ) {
                ?>
                <li class="category-tags__item category-tags__item--theme-<?php echo esc_attr( $item['theme'] ); ?>">
                    <?php echo esc_html( $item['name'] ); ?>
                </li>
                <?php
            }
            ?>
        </ul>
        <?php
    }
}
