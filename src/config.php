<?php

return [
    'email' => env('ADMINI_EMAIL', 'admin@app.dev'),
    'password' => env('ADMINI_PASSWORD', '123456'),

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
            'label' => '自定义样式表（CSS）',
            'col' => 12
        ],
    ]
];