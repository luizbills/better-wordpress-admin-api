# Better WordPress Admin API

A better way to build options pages for you plugins/themes.

## Installation

Clone or download this repo and put inside of your theme/plugin
```
cd wp-content/plugins/my-plugin
git clone https://github.com/luizbills/better-wordpress-admin-api/
```

Include the `better-wp-admin-api/init.php` file
```php
include_once 'path/to/better-wp-admin-api/init.php';
```

## Usage

```php
include_once __DIR__ . '/path/to/your/better-wp-admin-api/init.php';

// page details
$page_args = [
    'menu_name'         => 'My Page',

    // page slug
    'id'                => 'my-page',

    // prefix for all option names to fields of this page
    'options_prefix'    => 'my_prefix_',

    // use "parent" parameter to create as a sub-menu
    //'parent' => 'themes.php',

    // more options...
    //'icon'              => 'dashicons-admin-post',
    //'position'          => 10,
    //'capability'        => 'manage_options',
];

// create the page
$my_page = wp_create_admin_page( $page_args );

// hook a setup function
$my_page->on_setup( 'my_prefix_setup_my_page' );

// setup the page (add fields, add hooks, etc)
function my_prefix_setup_my_page ( $the_page, $hook_suffix ) {
    
    // field details
    $field_args = [
        'type'      => 'text',
        'id'        => 'text_field',
        'label'     => 'Text field',
        'desc'      => 'Field description. **You can use markdown here**.',
        'props'     => [
            // optional tag properties
            'placeholder' => 'type something...'
        ],
        //'default' => 'hello world',
    ];
    
    // creates a text field
    $the_page->add_field( $field_args );
}

// access the field value
$text_field_value = $my_page->get_field_value( 'text_field' );

// or with your prefix used above on $page_details
$text_field_value = $my_page->get_field_value( 'my_prefix_text_field' );
```

Generates:

![basic usage example page](docs/assets/page-usage-example.png)

More at [examples](/examples) folder.

### Avaliable field types

- `text` (text-based inputs: text, email, url, ...)
- `select`
- `checkbox` (single checkbox)
- `checkbox_multi` (multiple checkboxes)
- `radio`
- `hidden` (for **input[type="hidden"]**)
- `code` (code editor powered by [ace](https://ace.c9.io/))
- `html` (useful to create your own field template)
- `color` (color picker)
- `content` (default WordPress TinyMCE editor)

## Documentation

- [Field Types](docs/field-types.md)

## Roadmap

- [x] ~~Color picker~~
- [x] ~~TinyMCE editor~~
- [x] ~~Fields Documentation~~
- [x] ~~Multiple checkboxes~~
- [ ] Hooks Documentation
- [ ] Upload field

## LICENSE

GPL v2

---

Made with ❤ in Brazil
