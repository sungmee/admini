<?php

namespace Sungmee\Admini;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Support\Str;

class Post extends Eloquent
{
    protected $table = 'posts';

    protected $casts = [
        'meta' => 'array',
    ];

    protected $fillable = ['type', 'slug', 'meta'];

    public function __call($method, $arguments)
    {
        if ($lang = collect(config('admini.languages'))->firstWhere('language', $method)) {
            $class = '\\Sungmee\\Admini\\'.Str::studly($lang['language']);
            return $this->hasOne($class, 'post_id');
        }

        return parent::__call($method, $arguments);
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}

class Language extends Eloquent
{
    protected $table = null;
    public $timestamps = false;
    protected $fillable = ['post_id', 'title', 'pc', 'mobile'];

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id');
    }
}

class Tag extends Eloquent
{
    protected $table = 'tags';

    protected $fillable = ['type', 'slug', 'name'];

    public function posts(): MorphToMany
    {
        return $this->morphedByMany(Post::class, 'taggable');
    }
}
