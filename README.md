# Better WordPress Admin API

A better way to build options pages for you plugins/themes.

## Installation

- clone this repo into your theme/plugin
- include the `better-wp-admin-api/init.php` file

## Usage

```php
include_once __DIR__ . '/path/to/your/better-wp-admin-api/init.php';

// creates a page
$my_page = wp_create_admin_page( [
    'menu_name'         => 'My Page',

    // page slug
    'id'                => 'my-page',

    // prefix for all option names to fields of this page
    'options_prefix'    => 'my_prefix_',

    // use "parent" parameter to create as a sub-menu
    //'parent' => 'options-general.php',
    //'parent' => 'themes.php',

    // more options...
    //'icon'              => 'dashicons-admin-post',
    //'position'          => 10,
] );

// hook your setup function
$my_page->on_setup( 'my_prefix_setup_my_page' );

// setup your page (add fields, add hooks, etc)
function my_prefix_setup_my_page ( $the_page, $hook_suffix ) {

    // creates a text field on this page
    $the_page->add_field( [
        'type'      => 'text',
        'id'        => 'text_field',
        'label'     => 'Text field',
        'desc'      => 'Field description. **You can use markdown here**.',
        'props'     => [
            // optional tag properties
            'placeholder' => 'type something...'
        ],
        //'default' => 'hello world',
    ] );

}
```

More at [/examples](https://github.com/luizbills/better-wordpress-admin-api/tree/master/examples) folder.

### Avaliable field types

- `text` (text-based inputs: text, email, url, ...)
- `select`
- `checkbox` (single checkbox)
- `radio`
- `hidden` (for **input[type="hidden"]**)
- `code` (code editor powered by [ace](https://ace.c9.io/))
- `html` (useful to create your own field template)

## Documentation

Soon... W.I.P.

## Roadmap

- More field types:
    - Color picker field
    - Image upload field
    - Multiple checkboxes

## LICENSE

GPL v2
