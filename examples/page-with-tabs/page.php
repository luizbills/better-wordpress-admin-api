<?php
/*
    Page with Tabs
*/

include_once __DIR__ . '/../../framework/init.php';

// creates a page
$page_with_tabs = wp_create_admin_page( [
    'menu_name'   => 'Page with Tabs',
    'id'          => 'page-with-tabs',
    'prefix'      => 'with_tabs_',
] );


// changes default tab title
$page_with_tabs->set_tab([
    'id'   => 'default',
    'name' => 'General',
    'desc' => 'Tabs can have description and with **markdown**! Also <u>some html tags are allowed too.</u>',
]);

// creates a second tab
$page_with_tabs->set_tab([
    'id'   => 'extra',
    'name' => 'Extra'
]);

// creates a third tab
$page_with_tabs->set_tab([
    'id'   => 'about',
    'name' => 'About'
]);

// creates a text field in default tab
$page_with_tabs->add_field( [
    // fields without "tab" parameter will appears in default tab
    'type'      => 'text',
    'id'        => 'text_field',
    'label'     => 'Text field',
] );

// creates a select field in "extra" tab
$page_with_tabs->add_field( [
    'tab'       => 'extra',

    'type'      => 'code',
    'id'        => 'code_field',
    'label'     => 'Code editor field',

    'lang'      => 'javascript',
    'default'   => 'console.log(\'hello world\');',
] );

$page_with_tabs->add_field( [
    'tab'       => 'extra',

    'type'      => 'content',
    'id'        => 'content_field',
    'label'     => 'Content editor field',

    'default'   => 'Hello world!',
] );

$page_with_tabs->add_field( [
    'tab'       => 'extra',

    'type'      => 'color',
    'id'        => 'color_field',
    'label'     => 'Color picker field',

    'default'   => 'rgb(255,0,0)', // or a hex code
] );

// creates a html field in "about" tab
$page_with_tabs->add_field( [
    'tab'       => 'about',

    'type'      => 'html',
    'id'        => 'html_field',
    'label'     => 'HTML field',
    'content'   => '<code>' . date('Y') . '. All rights reserved.</code>'
] );
