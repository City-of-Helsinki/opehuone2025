<?php

/**
 * Used to update users dock items
 *
 * Class Dock_updater
 */
class Dock_updater {

    public static function update_all_docks() {
        $default_dock_items = User_settings::get_default_dock_items();
        $users              = get_users( [ 'orderby' => 'ID' ] );

        foreach ( $users as $user ) {
            $user_dock = get_user_meta( $user->ID, 'user_dock_items', true ) ? get_user_meta( $user->ID, 'user_dock_items', true ) : false;

            if ( ! self::is_valid_dock( $user_dock ) ) {
                continue;
            }

            self::delete_dock_items( $user->ID, $default_dock_items, $user_dock );
            self::add_dock_items( $user->ID, $default_dock_items, $user_dock );

            if ( self::is_update_needed( $default_dock_items, $user_dock ) ) {
                self::update_user_dock( $user->ID, $default_dock_items, $user_dock );
            }
        }
    }

    private static function add_dock_items( $user_id, $default_dock_items, $user_dock ) {
        $add_arrays = [];

        foreach ($default_dock_items as $default_dock_item) {
            // Look if item is found from user's dock..
            $single_item_found = false;
            foreach ($user_dock as $single_dock_item) {
                if ( $single_dock_item['id'] === $default_dock_item['id'] && $single_dock_item['url'] === $default_dock_item['url'] ) {
                    $single_item_found = true;
                }
            }

            // If single item not found, assume it's needed to be add
            if ( false === $single_item_found ) {
                $add_arrays[] = $default_dock_item;
            }
        }

        // If something to add, lets add items in the end of user dock
        if ( count( $add_arrays ) > 0 ) {
            foreach ($add_arrays as $array) {
                $user_dock[] = $array;
            }

            // and finally save new dock
            update_user_meta( $user_id, 'user_dock_items', $user_dock );
        }
    }

    private static function delete_dock_items( $user_id, $default_dock_items, $user_dock ) {
        $deleted_ids = [];
        $new_dock    = [];

        foreach ( $user_dock as $single_dock_item ) {
            // Look if item is found from defaults..
            $single_item_found = false;
            foreach ( $default_dock_items as $default_dock_item ) {
                if ( $single_dock_item['id'] === $default_dock_item['id'] && $single_dock_item['url'] === $default_dock_item['url']) {
                    $single_item_found = true;
                }
            }

            if ( false === $single_item_found ) {
                $deleted_ids[] = $single_dock_item['id'];
            }
        }

        // set $new_dock with items not found from deleted ids array
        if ( count( $deleted_ids ) > 0 ) {
            foreach ( $user_dock as $single_dock_item ) {
                if ( in_array( $single_dock_item['id'], $deleted_ids ) ) {
                    continue;
                }
                $new_dock[] = $single_dock_item;
            }

            // and finally save new dock
            update_user_meta( $user_id, 'user_dock_items', $new_dock );
        }
    }

    private static function update_user_dock( $user_id, $default_dock_items, $user_dock ) {
        $new_dock = [];

        foreach ( $user_dock as $single_dock_item ) {
            foreach ( $default_dock_items as $default_dock_item ) {
                if ( $single_dock_item['id'] === $default_dock_item['id'] ) {
                    $single_dock_item['title']      = $default_dock_item['title'];
                    $single_dock_item['url']      = $default_dock_item['url'];
                    $single_dock_item['icon_url'] = $default_dock_item['icon_url'];
                }
            }
            $new_dock[] = $single_dock_item;
        }

        update_user_meta( $user_id, 'user_dock_items', $new_dock );
    }

    private static function is_update_needed( $default_dock_items, $user_dock ) {
        foreach ( $user_dock as $single_dock_item ) {
            foreach ( $default_dock_items as $default_dock_item ) {
                if ( $single_dock_item['id'] === $default_dock_item['id'] ) {
                    if ( $single_dock_item['url'] !== $default_dock_item['url'] || $single_dock_item['icon_url'] !== $default_dock_item['icon_url'] || $single_dock_item['id'] !== $default_dock_item['id'] ) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    private static function is_valid_dock( $dock ) {
        if ( false === $dock ) {
            return false;
        }

        if ( is_array( $dock ) ) {
            if ( count( $dock ) < 1 ) {
                return false;
            }
        }

        return true;
    }
}
