<?php

if ( ! defined( 'WPINC' ) ) die;

if ( ! defined( 'BETTER_WP_ADMIN_API_VERSION' ) ) {

    define( 'BETTER_WP_ADMIN_API_VERSION', '0.3.0' );
    define( 'BETTER_WP_ADMIN_API_DOMAIN', 'better-wp-admin-api' );
    define( 'BETTER_WP_ADMIN_API_DIR', __DIR__ );
    define( 'BETTER_WP_ADMIN_API_FILE', __FILE__ );

    if ( ! class_exists( 'Parsedown' ) ) {
        require_once( __DIR__ . '/vendor/Parsedown/Parsedown.php' );
    }

    require_once( __DIR__ . '/classes/class-wp-admin-api.php' );
    require_once( __DIR__ . '/classes/class-wp-field-renderer.php' );
    require_once( __DIR__ . '/classes/class-wp-admin-page.php' );
    require_once( __DIR__ . '/functions.php' );
}