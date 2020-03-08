<?php

namespace Sungmee\Admini;

use Illuminate\Support\Facades\Facade as F;

class Facade extends F {
    /**
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Admini';
    }
}