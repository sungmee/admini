<?php

return [
    'email' => env('ADMINI_EMAIL', 'admin@app.dev'),
    'password' => env('ADMINI_PASSWORD', '123456'),

    'languages' => [
        [
            'language' => 'en',
            'locale' => 'en',
            'class' => '\App\En'
        ],
        [
            'language' => 'vi',
            'locale' => 'vi',
            'class' => '\App\Vi'
        ],
        [
            'language' => 'cn',
            'locale' => 'zh-CN',
            'class' => '\App\Cn'
        ],
        [
            'language' => 'tw',
            'locale' => 'zh-TW',
            'class' => '\App\Tw'
        ]
    ],
];