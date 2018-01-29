<?php
/*
    Simple Page
*/

include_once __DIR__ . '/../../better-wp-admin-api/init.php';

// creates a page
$my_page = wp_create_admin_page( [
    'menu_name'         => 'Simple Page',
    'id'                => 'simple-page',
    'options_prefix'    => 'simple_',
] );

// setup this page (add fields, add hooks, etc)


// hook your setup function
$my_page->on_setup( function ( $the_page, $hook_suffix ) {

    // creates a text field on this page
    $the_page->add_field( [
        'type'      => 'text',
        'id'        => 'text_field',
        'label'     => 'Text field',
        'desc'      => 'Field description. **You can use markdown here**.',
        //'default' => 'hello world',
        'props'     => [
            // tag properties
            'placeholder' => 'type something...'
        ]
    ] );

} );