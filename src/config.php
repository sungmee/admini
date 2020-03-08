<?php

return [
    'email' => env('ADMINI_EMAIL', 'admin@app.dev'),
    'password' => env('ADMINI_PASSWORD', '123456'),

    'languages' => [
        [
            'language' => 'en',
            'title' => trans('admini::locale.en'),
            'locale' => 'en',
            'class' => '\App\En'
        ],
        [
            'language' => 'vi',
            'title' => trans('admini::locale.vi'),
            'locale' => 'vi',
            'class' => '\App\Vi'
        ],
        [
            'language' => 'cn',
            'title' => trans('admini::locale.cn'),
            'locale' => 'zh-CN',
            'class' => '\App\Cn'
        ],
        [
            'language' => 'tw',
            'title' => trans('admini::locale.tw'),
            'locale' => 'zh-TW',
            'class' => '\App\Tw'
        ]
    ],
];