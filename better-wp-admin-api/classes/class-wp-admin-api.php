<?php

if ( ! defined( 'WPINC' ) ) die;

class _WP_Admin_API {

	private function __construct () {}

	// based on: https://wordpress.stackexchange.com/a/45712
	public static function get_asset_file_url( $file_path = '' ) {
		$file_path = BETTER_WP_ADMIN_API_DIR . '/assets/' . $file_path;
		$fixed_file_path = str_replace( WP_CONTENT_DIR, "", $file_path );
		if ( ! empty( $fixed_file_path ) ) {
			return content_url( $fixed_file_path );
		}
		return false;
	}

}