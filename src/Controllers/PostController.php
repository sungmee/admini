<?php

namespace Sungmee\Admini;

use Illuminate\Support\Str;
use Sungmee\Admini\Model as Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index(Request $request, $type)
    {
        $language = collect(config('admini.languages'))
            ->firstWhere('locale', $request->getLocale())['language'];
        $posts = Post::where('type', str_replace('s', '', $type))
            ->with($language)
            ->orderBy('updated_at', 'DESC')
            ->get();
        $title = trans("admini::post.post_type.$type") . ' ' . trans('admini::post.editor.list');
        $subtitle = trans('admini::post.subtitle.total', ['total' => count($posts)]);

        return view('admini::index', compact('type', 'posts', 'title', 'subtitle', 'language'));
    }

    public function create(Request $request, $type)
    {
        $client = 'pc';
        $title = trans('admini::post.editor.add') . ' ' . trans("admini::post.post_type.$type");
        $action = route('admini.posts.store', compact('type'));

        return view('admini::editor', compact('client', 'title', 'action'));
    }

    public function store(Request $request, $type)
    {
        $request->validate($this->rules());
        $post = new Post;
        $post->type = str_replace('s', '', $type);
        $post->slug = $request->slug ? Str::slug($request->slug, '-') : time();
        $post->save();
        $this->content($request, $post->id);

        $request->session()->flash('alert', trans('admini::post.editor.store_success'));
        $request->session()->flash('alert-contextual', 'success');
        return redirect()->route('admini.posts.edit', compact('type','post'));
    }

    public function edit(Request $request, $type, Post $post)
    {
        $client = $request->client == 'mobile' ? 'mobile' : 'pc';
        $action = route("admini.posts.update", compact('type','post')) . "?client=$client";
        $language = collect(config('admini.languages'))
            ->firstWhere('locale', $request->getLocale())['language'];
        $title = $post->{$language}->title ?? Str::title($post->slug);
        $subtitle = $client == 'pc'
            ? trans('admini::post.subtitle.now_edit_client.pc')
            : trans('admini::post.subtitle.now_edit_client.mobile');

        return view('admini::editor', compact('client', 'title', 'subtitle', 'action', 'post'));
    }

    public function update(Request $request, $type, Post $post)
    {
        $request->validate($this->rules($post->slug));
        $post->slug = $request->slug ? Str::slug($request->slug, '-') : time();
        $post->save();
        $this->content($request, $post->id);

        $request->session()->flash('alert', trans('admini::post.editor.update_success'));
        $request->session()->flash('alert-contextual', 'success');
        return redirect(url()->previous());
    }

    public function destory($type, Post $post)
    {
        $post->delete();

        if (request()->ajax()) {
            return response()->json(['errno' => 0], 202);
        } else {
            $request->session()->flash('alert', trans('admini::post.editor.destory_success'));
            $request->session()->flash('alert-contextual', 'success');
            return redirect()->route('admini.posts.index', compact('type'));
        }
    }

    private function content($request, $post_id)
    {
        $post_id = compact('post_id');
        $content_key = $request->client == 'mobile' ? 'mobile' : 'pc';

        foreach (config('admini.languages') as $lan) {
            $title = "title_{$lan['language']}";
            $content = "content_{$lan['language']}";
            $lan['class']::updateOrCreate($post_id,[
                'title' => $request->$title,
                $content_key => $request->$content
            ]);
        }
    }

    public function upload(Request $request)
    {
        $data = array_reduce($request->file(), function($carry, $file) {
            $carry[] = $path = Storage::url($file->store('public'));
            Post::create(['type' => 'file', 'slug' => $path]);
            return $carry;
        }, []);

        return [
            'errno' => 0,
            'data' => $data
        ];
    }

    private function rules($slug = null)
    {
        $slug_rule = 'nullable|alpha_dash|max:128';
        $slug_rule .= empty($slug) ? '|unique:posts' : '';

        $rules = [
            'client' => 'nullable|in:pc,mobile',
            'slug' => $slug_rule,
        ];

        foreach (config('admini.languages') as $lan) {
            $rules["title_{$lan['language']}"] = 'required|string|max:100';
            $rules["content_{$lan['language']}"] = 'nullable|string';
        }

        return $rules;
    }

    public function __construct()
    {
        $this->middleware(function ($request, $next){
            return ( !!! (new Admini)->auth() )
                ? redirect()->route('admini.auth.login')
                : $next($request);
        });
    }
}