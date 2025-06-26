<?php
// Before removing this file, please verify the PHP ini setting auto_prepend_file does not point to this.
define( 'WFWAF_STORAGE_ENGINE', 'mysqli' );
define( 'WFWAF_DB_NAME', getenv( 'WORDPRESS_DB_NAME' ) );
define( 'WFWAF_DB_USER', getenv( 'WORDPRESS_DB_USER' ) );
define( 'WFWAF_DB_PASSWORD', getenv( 'WORDPRESS_DB_PASSWORD' ) );
define( 'WFWAF_DB_HOST', getenv( 'WORDPRESS_DB_HOST' ) );
define( 'WFWAF_DB_CHARSET', 'utf8');
define( 'WFWAF_DB_COLLATE', '');
define( 'WFWAF_TABLE_PREFIX', 'wp_' );
define( 'WFWAF_MYSQL_CLIENT_FLAGS', MYSQLI_CLIENT_SSL );

if (file_exists(__DIR__.'/wp-content/plugins/wordfence/waf/bootstrap.php')) {
	define("WFWAF_LOG_PATH", '/tmp/wflogs/');
	include_once __DIR__.'/wp-content/plugins/wordfence/waf/bootstrap.php';
}