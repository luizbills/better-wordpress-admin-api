<?php

if ( ! defined( 'WPINC' ) ) die;

function wp_create_admin_page ( $settings ) {
    return new _WP_Admin_Page( $settings );
}