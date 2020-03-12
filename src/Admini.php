<?php

namespace Sungmee\Admini;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Admini {
    public $config;
    public $locale;
    public $languageMaps;
    public $language;
    public $table;

    public function __construct()
    {
        $config = config('admini');
        $locale = app()->getLocale();
        $languageMaps = collect($config['languages'])
            ->pluck('language', 'locale')
            ->all();

        $this->config = $config;
        $this->locale = $locale;
        $this->languageMaps = $languageMaps;
        $this->language = $languageMaps[$locale];
        $this->table = Str::plural($languageMaps[$locale]);
    }

    public function posts(string $type)
    {
        return DB::table('posts')
            ->where('type', str_replace('s', '', $type))
            ->join($this->table, 'posts.id', '=', "{$this->table}.post_id")
            ->get();
    }

    public function postFull(int $id)
    {
        $table = 'ens';
        $post = DB::table('posts')->find($id);
        $post->meta = json_decode($post->meta, true);

        foreach ($this->config['languages'] as $item) {
            $table = Str::plural($item['language']);
            $post->{$item['language']} = DB::table($table)
                ->where('post_id', $post->id)
                ->first();
        }

        return $post;
    }

    public function post(string $slug, $subslug = null)
    {
        $post = $this->getPost($subslug ?? $slug);
        $post->locale = $this->locale;
        $post->meta = json_decode($post->meta, true);
        $post->suptitle = $subslug ? $this->getPost($slug)->title : null;

        return $post;
    }

    public function getPost(string $slug)
    {
        return DB::table('posts')
            ->where('slug', $slug)
            ->join($this->table, 'posts.id', '=', "{$this->table}.post_id")
            ->first();
    }

    public function explodePath(string $slug, $subslug)
    {
        $slugs = explode('/', $slug);
        return (count($slugs)==2 && is_null($subslug)) ? $slugs : [$slug, $subslug];
    }

    public function auth()
    {
        $email = $this->config['email'];
        return $email == session('auth') ? $email : false;
    }

    public function attempt($req)
    {
        return $req['email'] == $this->config['email'] && $req['password'] == $this->config['password'];
    }
}