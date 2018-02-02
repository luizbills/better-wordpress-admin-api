<?php
/*
    Simple Page
*/

include_once __DIR__ . '/../../better-wp-admin-api/init.php';

// creates a page
$my_page = wp_create_admin_page( [
    'menu_name'   => 'Simple Page',
    'id'          => 'simple-page',
    'prefix'      => 'simple_',
] );

// creates a text field on this page
$my_page->add_field( [
    'type'      => 'text',
    'id'        => 'text_field',
    'label'     => 'Text field',
    'desc'      => 'Field description. **You can use markdown here**.',
    //'default' => 'hello world',
    'props'     => [
        // optional tag properties
        'placeholder' => 'type something...'
    ]
] );
