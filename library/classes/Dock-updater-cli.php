<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

class Opehuone_Update_User_Docks_Command {
    /**
     * Registers the WP-CLI command.
     */
    public static function register_commands() {
        WP_CLI::add_command( 'opehuone update-user-docks', [ __CLASS__, 'execute' ] );
    }

    /**
     * Executes the command to update all user docks.
     */
    public static function execute() {
        if ( ! class_exists( 'Dock_Updater' ) ) {
            WP_CLI::error( 'Dock_Updater class not found. Ensure the theme is loaded correctly.' );

            return;
        }

        WP_CLI::log( 'Starting user docks update...' );

        Dock_Updater::update_all_docks();

        WP_CLI::success( 'User docks update completed successfully.' );
    }
}

// Register the command with WP-CLI.
if ( defined( 'WP_CLI' ) && WP_CLI ) {
    Opehuone_Update_User_Docks_Command::register_commands();
}
