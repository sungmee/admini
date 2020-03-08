<?php

namespace Sungmee\Admini;

use Illuminate\Database\Eloquent\Model as M;

class Model extends M
{
    protected $table = 'posts';

    protected $fillable = ['type', 'slug'];

    // public function __call($method, $arguments)
    // {
    //     if (in_array($method, ['en','vi','cn','tw'])) {
    //         $class = \Str::studly($method);
    //         return $this->hasOne($class);
    //     } else {
    //         parent::__call($method, $arguments);
    //     }
    // }

    public function en()
    {
        return $this->hasOne(En::class, 'post_id');
    }
    public function vi()
    {
        return $this->hasOne(Vi::class, 'post_id');
    }
    public function cn()
    {
        return $this->hasOne(Cn::class, 'post_id');
    }
    public function tw()
    {
        return $this->hasOne(Tw::class, 'post_id');
    }
}

class Language extends M
{
    public $timestamps = false;
    protected $fillable = ['post_id', 'title', 'pc', 'mobile'];

    // public function __construct($table = 'cns')
    // {
    // }

    public function post()
    {
        return $this->belongsTo('App\Post', 'post_id');
    }
}

// foreach (config('admini.languages') as $item) {
    // class $item['language'] extends Language {}
// }
class En extends Language {}
class Vi extends Language {}
class Cn extends Language {}
class Tw extends Language {}