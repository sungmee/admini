<?php

return [
    'email' => env('ADMINI_EMAIL', 'admin@app.dev'),
    'password' => env('ADMINI_PASSWORD', '123456'),

    'auto_language' => true,
    'languages' => [
        [
            'language' => 'en',
            'locale' => 'en'
        ],
        [
            'language' => 'cn',
            'locale' => 'zh-CN'
        ],
    ],

    'mobile_version' => false,
    'post_subtitle' => true,
    'post_addition' => true,

    'post_type' => [
        'post'      => false,
        'page'      => true,
        'new'       => true,
        'notice'    => false,
        'file'      => false,
        'menu'      => false,
        'homepage'  => false
    ],

    'post_meta' => [
        // [
        //     'type' => 'radio', // radio, checkbox
        //     'name' => 'radio',
        //     'radios' => [
        //         ['value' => 1, 'label' => 'One'],
        //         ['value' => 2, 'label' => 'Two'],
        //     ],
        //     'col' => 6
        // ]
        [
            'type' => 'textarea', // text, number, phone, password, textarea
            'name' => 'style',
            'label' => 'custom_style',
            'col' => 12
        ],
    ],

    'delete_botton_in_post_types' => ['news', 'pages'],
];