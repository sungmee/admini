<?php

return [
    'email' => env('ADMINI_EMAIL', 'admin@app.dev'),
    'password' => env('ADMINI_PASSWORD', '123456'),

    'languages' => [
        [
            'language' => 'en',
            'locale' => 'en',
            'class' => '\Sungmee\Admini\En'
        ],
        [
            'language' => 'vi',
            'locale' => 'vi',
            'class' => '\Sungmee\Admini\Vi'
        ],
        [
            'language' => 'cn',
            'locale' => 'zh-CN',
            'class' => '\Sungmee\Admini\Cn'
        ],
        [
            'language' => 'tw',
            'locale' => 'zh-TW',
            'class' => '\Sungmee\Admini\Tw'
        ]
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