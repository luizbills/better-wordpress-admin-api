# Better WordPress Admin API

## Getting Started

### How to create a page

```php
$page_details = [
    // required arguments
    'id'        => 'my_page_id', // also it's the page slug
    'menu_name' => 'My Page',
    'prefix'    => 'my_prefix_', // prefix to all fields of this page
    
    // others optional arguments

    //
    //'parent' => null, // id of parent, if blank, then this is a top level menu
    //'capability' => 'manage_options', // User role
    
    // Menu icon for top level menus only https://developer.wordpress.org/resource/dashicons/
    //'icon' => 'dashicons-admin-generic',
    
    // Menu position. Can be used for both top and sub level menus
    //'position' => null,
];

// create your page
$your_page = wp_create_admin_page( $page_details );

// done!
```

#### Retrieving a page instance

```php
$your_page = wp_get_admin_page( 'my_page_id' );
```

### How to create a field

```php
$field_details = [
    'id'        => 'your_field_id',
    'label'     => 'your_field_label',
    'type'      => 'text', // to create a text input
    
    //other args...
];

// create your field
$your_page->add_field( $field_details );

// done!
```

Notes:
 - There are several types of fields that you can create, check the [field type documentation](field-types.md).
 - You can write [markdown](https://guides.github.com/features/mastering-markdown/) in `desc` argument and *allowed HTML tags for post content*.

#### Getting field value

```php
$your_field_value = $your_page->get_field_value( 'your_field_id' );

// or with prefix
$your_field_value = $your_page->get_field_value( 'my_prefix_your_field_id' );

// or without a page object
$your_field_value = wp_get_page_field_value( 'my_page_id', 'your_field_id' );
```

### How to create a tab

```php
$the_page->set_tab([
    'id'    => 'pro',
    
    // title of the page tab
    'name'  => 'Premium',
    
    // description that appears below the tab.
    'desc'  => 'Lorem ipsum dolor sit amet...', 
]);
```

Notes:

- You can write [markdown](https://guides.github.com/features/mastering-markdown/) in `desc` argument and *allowed HTML tags for post content*.
- If you do not create other tabs, no tabs will be shown on your page.

#### The default tab

By default, a tab is always created. Its `id` is "default" and the name/title is the page `menu_name` parameter. You can edit the default tab with the same method shown above:

```php
$the_page->set_tab([
    'id'    => 'default',
    
    // title of the page tab
    'name'  => 'Settings',
    
    // description that appears below the tab.
    'desc'  => 'Lorem ipsum dolor sit amet...', 
]);
```

### Validation and Sanitization

You can pass a `sanitize_callback` argument to sanitize and validate your field. It's a function passed by [`sanitize_option_{$field_id}`](https://codex.wordpress.org/Plugin_API/Filter_Reference/sanitize_option_$option) filter before saving your field. 

Also, you can use [`add_settings_error`](https://codex.wordpress.org/Function_Reference/add_settings_error) function to show admin notice errors.

```php
$the_page->add_field([
    'type'              => 'text',
    'id'                => 'my_text_field',
    'label'             => 'My text field',

    // example: make a field required
    'sanitize_callback' => function ( $new_value, $field_id, $the_page ) {
        if ( empty( $new_value ) ) {
            // show an notice error
            add_settings_error( $field_id, $field_id, '"My text field" is required' );

            // don't save the invalid value
            $old_value = $the_page->get_field_value( $field_id );
            return $old_value;
        } else {
            return $new_value;
        }
    }
]);
```
