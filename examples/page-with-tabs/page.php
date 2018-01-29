<?php
/*
    Page with Tabs
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
function my_prefix_setup_my_page ( $the_page, $hook_suffix ) {

    // changes default tab title
    $the_page->set_tab([
        'id'   => 'default',
        'name' => 'General',
        'desc' => 'Tabs can have description and with **markdown**! Also <u>some html tags are allowed too.</u>',
    ]);

    // creates a second tab
    $the_page->set_tab([
        'id'   => 'extra',
        'name' => 'Extra'
    ]);

    // creates a third tab
    $the_page->set_tab([
        'id'   => 'about',
        'name' => 'About'
    ]);

    // creates a text field in default tab
    $the_page->add_field( [
        // fields withou "tag" parameter will appears in default tab
        'type'      => 'text',
        'id'        => 'text_field',
        'label'     => 'Text field',
    ] );

    // creates a select field in "extra" tab
    $the_page->add_field( [
        'tab'       => 'extra',

        'type'      => 'select',
        'id'        => 'select_field',
        'label'     => 'Select field',
        'choices'   => [
            'yes'       => 'Yes',
            'no'        => 'No',
            'maybe'     => 'Maybe... why not?'
        ]
    ] );

    // creates a html field in "about" tab
    $the_page->add_field( [
        'tab'       => 'about',

        'type'      => 'html',
        'id'        => 'html_field',
        'label'     => 'HTML field',
        'content'   => '<code>' . date('Y') . '. All rights reserved.</code>'
    ] );

}

// hook your setup function
$my_page->on_setup( 'my_prefix_setup_my_page' );