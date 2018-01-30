# Better WordPress Admin API

## Field types

- [Text](#text)
- [Select](#select)
- [Checkbox](#checkbox)
- [Radio](#radio)
- [Hidden](#hidden)
- [Code](#code)
- [HTML](#html)
- [Color](#color)
- [Content](#content)

### Text

![Text field](assets/field-text.png)

```php
$page->add_field([

    //'tab => 'default',

    'type'    => 'text',
    'id'      => 'text_field',
    'label'   => __( 'My Text field' ),
    'desc'    => __( 'some description.' ),
    'default' => '',

    'props'   => [
        // input properies
        // 'placeholder' => 'type your text',
        // 'type'        => 'number',
        // 'class'       => 'small-text my-class',
        // ...
    ],

    //'before'  => 'html rendered before <input>',
    //'after'   => 'html rendered after <input>',

    // sanitize_callback => 'custom_callback_to_sanitize_this_field',
]);
```

### Select

![Select field](assets/field-select.png)

```php
$page->add_field([

    //'tab => 'default',

    'type'    => 'select',
    'id'      => 'select_field',
    'label'   => __( 'My Select field' ),
    'desc'    => __( 'some description.' ),
    'default' => '',

    'choices'   => [
        'opt-1'             => 'Option',
        'My option group'   => [
            'opt-2'         => 'Another Option'
        ],
    ],

    //'before'  => 'html rendered before <select>',
    //'after'   => 'html rendered after </select>',

    // sanitize_callback => 'custom_callback_to_sanitize_this_field',
]);
```

### Checkbox

![Checkbox field](assets/field-checkbox.png)

```php
$page->add_field([

    //'tab => 'default',

    'type'    => 'checkbox',
    'id'      => 'checkbox_field',
    'label'   => __( 'My Checkbox field' ),
    'desc'    => __( 'some description.' ),
    'default' => '',

    //'before'  => 'html rendered before the checkbox',
    'after'   => 'html rendered after the checkbox',

    // sanitize_callback => 'custom_callback_to_sanitize_this_field',
]);
```

> Note: use the "after" parameter as label for the checkbox.

### Radio

![Radio field](assets/field-radio.png)

```php
$page->add_field([

    //'tab => 'default',

    'type'    => 'radio',
    'id'      => 'radio_field',
    'label'   => 'My Radio fields',
    'desc'    => 'some description.',
    'default' => '',

    'choices'   => [
        'BR'    => 'Brazil',
        'SV'    => 'El Salvador',
        'DE'    => 'Germany',
        'IT'    => 'Italy',
    ],

    //'before'  => 'html rendered before the checkbox',
    //'after'   => 'html rendered after the checkbox',

    // sanitize_callback => 'custom_callback_to_sanitize_this_field',
]);
```

### Hidden

```php
$page->add_field([

    //'tab => 'default',

    'type'    => 'hidden',
    'id'      => 'hidden_field',
    'default' => '',

    //'before'  => 'html rendered before the <input>',
    //'after'   => 'html rendered after the <input>',

    // sanitize_callback => 'custom_callback_to_sanitize_this_field',
]);
```

### Code

Code editor powered by [ace](https://ace.c9.io/).

![Code field](assets/field-code.png)

```php
$page->add_field([

    //'tab => 'default',

    'type'    => 'code',
    'id'      => 'code_field',
    'label'   => 'My Code field',
    'desc'    => 'some description.',
    'default' => '',

    //'lang'    => 'javascript',
    //'theme'   => 'monokai',
    //'height'  => 200,

    //'before'  => 'html rendered before the checkbox',
    //'after'   => 'html rendered after the checkbox',

    // sanitize_callback => 'custom_callback_to_sanitize_this_field',
]);
```

> You can check all *themes* and *modes* (languages) here: https://github.com/ajaxorg/ace-builds/tree/master/src

### HTML

![HTML field](assets/html-code.png)

```php
$the_page->add_field([

    //'tab => 'default',

    'type'    => 'html',
    'id'      => 'html_field',
    'label'   => 'My HTML field',
    'default' => '',

    'content' => 'custom_render_html_field',

    //sanitize_callback => 'custom_callback_to_sanitize_this_field',
]);

function custom_render_html_field ( $field, $the_page ) {
    $value = $the_page->get_field_value( $field['id'] );
    $html = '<div class="card">Made with HTML field<br>';
    $html .= '<input type="text" name="' . $field['id'] . '" value="' . esc_attr( $value ) . '">';
    $html .= '</div>';
    echo $html;
}
```

Note: `html` fields don't has `before`, `after` parameters.

### Color

![Color field](assets/color-code.png)

```php
$the_page->add_field( [
    //'tab' => 'default',

    'type'    => 'color',
    'id'      => 'color_field',
    'label'   => 'My Color picker',
    'desc'    => 'some description.',
    'default' => '#fff',

    //'before'  => 'html rendered before the checkbox',
    //'after'   => 'html rendered after the checkbox',

    // sanitize_callback => 'custom_callback_to_sanitize_this_field',
] );
```

### Content

TinyMCE content editor.

![Content field](assets/color-content.png)

```php
$the_page->add_field( [
    //'tab' => 'default',

    'type'    => 'content',
    'id'      => 'content_field',
    'label'   => 'My Content picker',
    'desc'    => 'some description.',
    'default' => '',

    //'height'  => 200,
    //'wpautop' => true,

    //'before'  => 'html rendered before the checkbox',
    //'after'   => 'html rendered after the checkbox',

    // sanitize_callback => 'custom_callback_to_sanitize_this_field',
] );
```