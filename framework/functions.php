<?php

if ( ! defined( 'WPINC' ) ) die;

function wp_create_admin_page ( $settings ) {
	return new _WP_Admin_Page( $settings );
}

function wp_get_admin_page ( $page_id = '' ) {
	return _WP_Admin_Page::get_instance_by_id( $page_id );
}

function wp_get_page_field_value ( $page_id, $field_id ) {
	$the_page = _WP_Admin_Page::get_instance_by_id( $page_id );

	if ( ! empty( $the_page ) ) {
		return $the_page->get_field_value( $field_id );
	}

	return false;
}

function wp_get_metabox_field_value ( $metabox_id, $field_id ) {
	// TODO
	return false;
}
