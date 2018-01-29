<?php
/*
    Simple Page
    /wp-admin/options-general.php
*/

include_once __DIR__ . '/../../better-wp-admin-api/init.php';

// creates a page
$my_page = wp_create_admin_page( [
    'menu_name'         => 'Page with Tabs',

    // page slug
    'id'                => 'page-with-tabs',

    // prefix for all option names to fields of this page
    'options_prefix'    => 'my_prefix_',

    // use "parent" parameter to create as a sub-menu
    //'parent' => 'options-general.php',
    //'parent' => 'themes.php',

    // more options...
    //'icon'              => 'dashicons-admin-post',
    //'position'          => 10,
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