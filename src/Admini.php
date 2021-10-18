<?php

namespace Sungmee\Admini;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Admini {
    public $config;
    public $locale;
    public $languageMaps;
    public $language;
    public $table;
    public $orderBy;
    public $order;
    public $limit;
    public $posts;
    public $thumbnail;

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

        $this->orderBy = 'created_at';
        $this->order = 'DESC';
        $this->limit = 15;
        $this->thumbnail = $config['post_thumbnail'];
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

    public function orderBy(string $orderBy = 'created_at')
    {
        $this->orderBy = $orderBy;
        return $this;
    }

    public function order(string $order)
    {
        $this->order = $order;
        return $this;
    }

    public function limit(int $limit)
    {
        $this->limit = $limit;
        return $this;
    }

    public function get()
    {
        return $this->posts
            ->orderBy($this->orderBy, $this->order)
            ->limit($this->limit)
            ->get();
    }

    public function paginate(int $per_page = 15)
    {
        $posts = $this->posts
            ->orderBy($this->orderBy, $this->order)
            ->paginate($per_page);

        $posts->getCollection()->transform(function($item) {
            preg_match ("<img.*src=[\"](.*?)[\"].*?>", $item->pc, $match);
            $color = mt_rand(0, 0xFFFFFF);
            $text  = explode(' ', $item->title)[0];
            $src = $match[1] ?? "https://placehold.it/{$this->thumbnail}/$color/ffffff?text=$text";
            $item->thumbnail = $src;
            return $item;
        });

        return $posts;
    }

    public function posts(string $type = 'post')
    {
        $this->posts = DB::table('posts')
            ->where('type', str_replace('s', '', $type))
            ->join($this->table, 'posts.id', '=', "{$this->table}.post_id");

        return $this;
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

        $post->title = $post->{$this->language}->title ?? Str::title($post->slug);

        return $post;
    }

    public function post(string $slug, $subslug = null)
    {
        $post = $this->getPost($subslug ?? $slug);

        if ( ! empty($post) ) {
            $post->locale = $this->locale;
            $post->meta = json_decode($post->meta, true);
            $post->suptitle = $subslug ? ($this->getPost($slug)->title ?? Str::title($slug)) : null;
            $post->tags = $this->getTags($post->post_id);
        }

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
        return ($email == session('auth') ? $email : false) || Auth::user()->isAdmin();
    }

    public function attempt($req)
    {
        return $req['email'] == $this->config['email'] && $req['password'] == $this->config['password'];
    }
}