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

    public function tag(string $slug)
    {
        $tid = DB::table('tags')
            ->where('slug', $slug)
            ->value('id');

        $pids = DB::table('taggables')
            ->where('tag_id', $tid)
            ->pluck('taggable_id')
            ->all();

        return DB::table('posts')
            ->whereIn('id', $pids)
            ->join($this->table, 'posts.id', '=', "{$this->table}.post_id")
            ->orderBy('created_at', 'DESC')
            ->get();
    }

    public function pages()
    {
        return $this->posts('page');
    }

    public function news()
    {
        return $this->posts('new');
    }

    public function notices()
    {
        return $this->posts('notice');
    }

    public function files()
    {
        return $this->posts('file');
    }

    public function posts(string $type = 'post')
    {
        return DB::table('posts')
            ->where('type', str_replace('s', '', $type))
            ->join($this->table, 'posts.id', '=', "{$this->table}.post_id")
            ->orderBy('created_at', 'DESC')
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
        $post->tags = $this->getTags($post->post_id);

        return $post;
    }

    public function getPost(string $slug)
    {
        return DB::table('posts')
            ->where('slug', $slug)
            ->join($this->table, 'posts.id', '=', "{$this->table}.post_id")
            ->first();
    }

    public function getTags(int $id)
    {
        $ids = DB::table('taggables')
            ->where('taggable_id', $id)
            ->pluck('tag_id')
            ->all();

        return DB::table('tags')
            ->whereIn('id', $ids)
            ->get()
            ->map(function ($item, $key) {
                $item->names = json_decode($item->names, true);
                return $item;
            });
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