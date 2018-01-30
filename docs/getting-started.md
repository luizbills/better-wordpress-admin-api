# Better WordPress Admin API

## Getting Started

WIP

### How to create a page



### How to create a field



### How to create a tab

By default a tab is always created, the default tab. Its `id` is 'default' and the name is the page "name" parameter.

To create new tabs:

```php
$the_page->set_tab([
    'id'    => 'pro',
    'name'  => 'Premium',
    'desc'  => 'Lorem ipsum dolor sit amet...',
]);
```

To delete a tab and its fields:

$the_page->unset_tab('pro');

### Validation and Sanitization

You can pass a `"sanitize_callback"` parameter to validate your field.
It's a function called in the field `"sanitize_option_{$field_id}`.

```php
$the_page->add_field([

    'type'              => 'text',
    'id'                => 'my_text_field',
    'label'             => 'My text field',

    'sanitize_callback' => function ( $value, $field_id, $the_page ) {
        if ( empty( $value ) ) {
            $old = $the_page->get_field_value( $field_id, false );
            add_settings_error( $field_id, $field_id, "$value" );

            // don't save the invalid value
            return $old;
        } else {
            return $value;
        }
    }

]);
```