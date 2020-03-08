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
];