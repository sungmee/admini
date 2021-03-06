<?php

namespace Sungmee\Admini;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model as M;

class Post extends Model {}
class En extends Language {}
class Cn extends Language {}

class Model extends M
{
    protected $table = 'posts';

    protected $casts = [
        'meta' => 'array',
    ];

    protected $fillable = ['type', 'slug', 'meta'];

    public function __call($method, $arguments)
    {
        if ($lang = collect(config('admini.languages'))->firstWhere('language', $method)) {
            // $table = Str::plural($lang['language']);
            // return $this->hasOne(new \Sungmee\Admini\Language($table), 'post_id');
            $class = '\\Sungmee\\Admini\\'.Str::studly($lang['language']);
            return $this->hasOne($class, 'post_id');
        }

        return parent::__call($method, $arguments);
    }

    /**
     * 获得有此产品的分类。
     */
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}

class Language extends M
{
    protected $table = null;
    public $timestamps = false;
    protected $fillable = ['post_id', 'title', 'pc', 'mobile'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}

class Tag extends Model
{
    protected $fillable = ['type', 'slug', 'name'];

    public function posts()
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}