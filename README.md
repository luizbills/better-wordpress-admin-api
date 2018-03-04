# Better WordPress Admin API

A better way to build admin pages and options for you plugins/themes.

## Installation

Clone or download this repo and put inside of your theme/plugin
```
cd wp-content/plugins/my-plugin
git clone https://github.com/luizbills/better-wordpress-admin-api/
```

Include the `framework/init.php` file
```php
include_once 'path/to/framework/init.php';
```

## Usage

```php
include_once __DIR__ . '/path/to/your/framework/init.php';

// page details
$page_args = [
    'menu_name'         => 'Your Page',

    // page slug
    'id'                => 'your-page-id',

    // automatically prefix all field ids
    'prefix'    => 'your_prefix_',

    // use "parent" parameter to create as a sub-menu
    //'parent' => 'themes.php',

    // more options...
    //'icon'              => 'dashicons-admin-post',
    //'position'          => 10,
    //'capability'        => 'manage_options',
];

// create the page
$your_page = wp_create_admin_page( $page_args );

// add fields

// field details
$field_args = [
    'type'      => 'text',
    'id'        => 'your_text_field',
    'label'     => 'Your Text field',
    'desc'      => 'Your field description. **You can use markdown here**.',
    'props'     => [
        // optional tag properties
        'placeholder' => 'type something...'
    ],
    //'default' => 'hello world',
];

// creates a text field
$your_page->add_field( $field_args );
```

Generates:

![basic usage example page](docs/assets/page-usage-example.png)

```php
// get your page instance
$your_page = wp_get_page_instance( 'your-page-id' );

// get a field value
$your_text_value = $my_page->get_field_value( 'your_text_field' );

// or with your prefix used above on $page_details (but it is not necessary)
$your_text_value = $my_page->get_field_value( 'your_prefix_your_text_field' );


// or just put all together
$your_text_value = wp_get_page_field_value( 'your-page-id', 'your_text_field' );
```

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

- [Getting started](docs/getting-started.md)
- [Field Types](docs/field-types.md)

## Roadmap

- [x] ~~Color picker~~
- [x] ~~TinyMCE editor~~
- [x] ~~Fields Documentation~~
- [x] ~~Multiple checkboxes~~
- [x] ~~Upload field~~
- [ ] Metaboxes

## Contributing

- Found a bug? Report it on [GitHub Issues](https://github.com/luizbills/better-wordpress-admin-api/issues) and include a code sample. Please state which version of WordPress your are using.

- Before submitting a Pull Request, if you are modifying some JavaScript or CSS file run: `npm run minify` (requires NodeJS)

More details:
1. install NodeJS
1. open your terminal in the root of this repository
1. run `npm install`
1. then run `npm run minify` to update the minified assets.

> Pro tip: add `define( 'WP_DEBUG_SCRIPT', true );` to your `wp-config.php` when you're coding some PR.

## LICENSE

GPL v2

---

Made with ❤ in Brazil
