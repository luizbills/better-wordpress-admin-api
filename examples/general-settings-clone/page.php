<?php
/*
    General Settings Clone
    /wp-admin/options-general.php
*/

include_once __DIR__ . '/../../framework/init.php';

$general_page = wp_create_admin_page( [
    'id'          => 'general-settings-clone',
    'menu_name'   => 'General Clone',
    //'parent'    => 'options-general.php',
    'prefix'      => ''
] );

$general_page->set_tab([
    'id' => 'default',
    'name' => 'General Settings',
]);

$general_page->add_field([
    'id'        => 'blogname',
    'label'     => 'Site Title',
    'type'      => 'text',
]);

$general_page->add_field([
    'id'        => 'blogdescription',
    'label'     => 'Tagline',
    'type'      => 'text',
    'desc'      => 'In a few words, explain what this site is about.'
]);

$general_page->add_field([
    'id'        => 'siteurl',
    'label'     => 'WordPress Address (URL)',
    'type'      => 'text',
    'props'     => [
        'class'     => 'regular-text code',
        'type'      => 'url',
    ]
]);

$general_page->add_field([
    'id'        => 'home',
    'label'     => 'Site Address (URL)',
    'type'      => 'text',
    'desc'      => 'Enter the address here if [you want your site home page to be different from your WordPress installation directory.](#)',
    'props'     => [
        'class'     => 'regular-text code',
        'type'      => 'url',
    ]
]);

$general_page->add_field([
    'id'        => 'admin_email',
    'label'     => 'Email Address',
    'type'      => 'text',
    'desc'      => 'This address is used for admin purposes. If you change this we will send you an email at your new address to confirm it. **The new address will not become active until confirmed.**',
    'props'     => [
        'class'     => 'regular-text ltr',
        'type'      => 'email',
        'readonly'  => true
    ]
]);

$general_page->add_field([
    'id'        => 'users_can_register',
    'label'     => 'Membership',
    'type'      => 'checkbox',
    'after'     => ' Anyone can register'
]);

$general_page->add_field([
    'id'        => 'default_role',
    'label'     => 'New User Default Role',
    'type'      => 'select',
    'choices'   => array_reverse( wp_roles()->get_names() ),
]);

$general_page->add_field([
    'id'        => 'WPLANG',
    'label'     => 'Site Language',
    'type'      => 'select',
    'choices'   => [
        'Installed' => [
            'en'        => 'English (United States)',
        ],
        'Available' => [
            'af'        => 'Afrikaans',
            'ar'        => 'العربية المغربية',
            'ary'       => 'العربية المغربية',
        ]
    ],
    'default' => 'en'
]);

$general_page->add_field([
    'id'        => 'timezone_string',
    'label'     => 'Timezone',
    'type'      => 'select',
    'choices'   => [
        'Manual Offsets'    => [
            'UTC+0'             => 'UTC+0',
            'UTC+1'             => 'UTC+1',
            'UTC+2'             => 'UTC+2',
        ],
        'America'           => [
            'America/Sao_Paulo' => 'America/Sao_Paulo',
        ],
    ],
    'after'     => '<p class="description" id="timezone-description">Choose either a city in the same timezone as you or a UTC timezone offset.</p><p class="timezone-info"><span id="utc-time">Universal time (<abbr>UTC</abbr>) is <code>2018-01-29 12:39:18</code>.</span></p>',
]);

$general_page->add_field([
    'id'        => 'date_format',
    'label'     => 'Date Format',
    'type'      => 'radio',
    'choices'   => date_format_radio_choices(),
    'desc' => '',
    // description without ".description" class
    'after' =>  '<p><b>Preview</b>: January 29, 2018</p>'
]);

$general_page->add_field([
    'id'        => 'time_format',
    'label'     => 'Time Format',
    'type'      => 'radio',
    'choices'   => __time_format_radio_choices(),
    'desc' => '',
    // description without ".description" class
    'after' =>  '<p><b>Preview</b>: 1:51 pm</p><br><a href="#">Documentation on date and time formatting</a>'
]);

$general_page->add_field([
    'id'        => 'start_of_week',
    'label'     => 'Week Starts On',
    'type'      => 'select',
    'choices'   => __start_of_week_select_choices(),
    'default'   => 1,
]);

// function to generate the avaliable choices of the "Date format"
function date_format_radio_choices () {
    $formats = [];
    $choices = [];

    $formats['F j, Y'] = '<span class="date-time-text format-i18n">January 29, 2018</span><code>F j, Y</code>';
    $formats['Y-m-d'] = '<span class="date-time-text format-i18n">2018-01-29</span><code>Y-m-d</code>';
    $formats['m/d/Y'] = '<span class="date-time-text format-i18n">01/29/2018</span><code>m/d/Y</code>';
    $formats['d/m/Y'] = '<span class="date-time-text format-i18n">29/01/2018</span><code>d/m/Y</code>';

    $formats['custom'] = '<span class="date-time-text">Custom: </span>';
    $formats['custom'] .= '<span class="screen-reader-text"> enter a custom date format in the following field</span>';
    $formats['custom'] .= '<input type="text" name="date_format_custom" id="date_format_custom" value="F j, Y" class="small-text">';

    foreach ( $formats as $value => $html ) {
        $choices[ $value ] = function () use ( $html ) { echo $html; };
    }

    return $choices;
}

// generate the choices of the "Time format"
function __time_format_radio_choices () {
    $formats = [];
    $choices = [];

    $formats['g:i a'] = '<span class="date-time-text format-i18n">1:49 pm</span><code>g:i a</code>';
    $formats['g:i A'] = '<span class="date-time-text format-i18n">1:49 PM</span><code>g:i A</code>';
    $formats['H:i'] = '<span class="date-time-text format-i18n">11:50</span><code>H:i</code>';

    $formats['custom'] = '<span class="date-time-text">Custom: </span>';
    $formats['custom'] .= '<span class="screen-reader-text"> enter a custom time format in the following field</span>';
    $formats['custom'] .= '<input type="text" name="date_format_custom" id="date_format_custom" value="g:i a" class="small-text">';

    foreach ( $formats as $value => $html ) {
        $choices[ $value ] = function () use ( $html ) { echo $html; };
    }

    return $choices;
}

// generate the choices of the "Week Starts On"
function __start_of_week_select_choices () {
    $week_days = [];
    foreach ( range(0, 7) as $number ) {
        // get week day name
        $week_days[] = date('l', strtotime("Sunday +{$number} days"));
    }
    return $week_days;
}

$general_page->setup_page_hooks( function ( $hook_suffix, $the_page ) {

    // required css just for this page
    add_action( "admin_head-$hook_suffix", function () {
        ?>
        <style>
            .date-time-text {
                display: inline-block;
                min-width: 10em;
            }
        </style>
        <?php
    } );

} );
