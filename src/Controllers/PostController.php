<?php

namespace Sungmee\Admini\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Sungmee\Admini\Admini;

class PostController extends Controller
{
    public function index(Request $request, string $type)
    {
        $posts = (new Admini)->posts($type)->paginate();
        $title = trans("admini::post.post_type.$type") . ' ' . trans('admini::post.editor.list');
        $subtitle = trans('admini::post.subtitle.total', ['total' => $posts->total()]);

        return view('admini::index', compact('type', 'posts', 'title', 'subtitle'));
    }

    public function create(Request $request, string $type)
    {
        $client = 'pc';
        $title = trans('admini::post.editor.add') . ' ' . trans("admini::post.post_type.$type");
        $action = route('admini.posts.store', compact('type'));
        $tags = DB::table('tags')->where('type', Str::singular($type))->get();
        $taggables = [];

        return view('admini::editor', compact('client', 'title', 'action', 'tags', 'taggables'));
    }

    public function store(Request $request, string $type)
    {
        $request->validate($this->rules());

        $now = now();

        $id = DB::table('posts')->insertGetId([
            'type' => Str::singular($type),
            'slug' => $request->slug ? Str::slug($request->slug, '-') : time(),
            'meta' => json_encode($request->meta),
            'created_at' => $now,
            'updated_at' => $now
        ]);
        $this->content($request, $id);

        if (! empty($request->tags)) {
            $taggables = [];
            foreach ($request->tags as $item) {
                $taggables[] = [
                    'tag_id' => $item,
                    'taggable_id' => $id
                ];
            }
            DB::table('taggables')->insert($taggables);
        }

        $request->session()->flash('alert', trans('admini::post.editor.store_success'));
        $request->session()->flash('alert-contextual', 'success');
        return redirect()->route('admini.posts.edit', compact('type','id'));
    }

    public function edit(Request $request, string $type, int $id)
    {
        $client = $request->client ?? 'pc';
        $action = route("admini.posts.update", compact('type','id')) . "?client=$client";
        $post = (new Admini)->postFull($id);
        $title = $post->title;
        $subtitle = trans("admini::post.subtitle.now_edit_client.$client");
        $tags = DB::table('tags')->where('type', Str::singular($type))->get();
        $taggables = DB::table('taggables')->where('taggable_id', $id)->pluck('tag_id')->all();
        return view('admini::editor', compact('client', 'title', 'subtitle', 'action', 'post', 'tags', 'taggables'));
    }

    public function update(Request $request, string $type, int $id)
    {
        $post = DB::table('posts')->find($id);

        $request->validate($this->rules($post->id));

        DB::table('posts')
            ->where('id', $id)
            ->update([
                'slug' => $request->slug ? Str::slug($request->slug, '-') : time(),
                'meta' => json_encode($request->meta),
                'updated_at' => now()
            ]);
        $this->content($request, $id);

        DB::table('taggables')->where('taggable_id', $id)->delete();
        if ($request->tags) {
            $taggables = [];
            foreach ($request->tags as $item) {
                $taggables[] = [
                    'tag_id' => $item,
                    'taggable_id' => $id
                ];
            }
            DB::table('taggables')->insert($taggables);
        }

        $request->session()->flash('alert', trans('admini::post.editor.update_success'));
        $request->session()->flash('alert-contextual', 'success');
        return redirect(url()->previous());
    }

    public function destory(Request $request, string $type, int $id)
    {
        DB::table('posts')->where('id', $id)->delete();
        DB::table('taggables')->where('taggable_id', $id)->delete();

        if (request()->ajax()) {
            return response()->json(['errno' => 0], 202);
        } else {
            $request->session()->flash('alert', trans('admini::post.editor.destory_success'));
            $request->session()->flash('alert-contextual', 'success');
            return redirect()->route('admini.posts.index', compact('type'));
        }
    }

    private function content(Request $request, int $post_id)
    {
        $post_id = compact('post_id');

        foreach (config('admini.languages') as $lang) {
            $table = Str::plural($lang['language']);
            $title = "title_{$lang['language']}";
            $subtitle = "subtitle_{$lang['language']}";
            $addition = "addition_{$lang['language']}";
            $content = "content_{$lang['language']}";
            $client = $request->client ?? 'pc';
            DB::table($table)->updateOrInsert($post_id,[
                'title' => $request->$title,
                'subtitle' => $request->$subtitle,
                'addition' => $request->$addition,
                $client => $request->$content
            ]);
        }
    }

    public function upload(Request $request)
    {
        $data = array_reduce($request->file(), function($carry, $file) {
            $carry[] = $path = Storage::url($file->store('public'));
            $now = now();
            DB::table('posts')->insertOrIgnore([
                'type' => 'file',
                'slug' => $path,
                'created_at' => $now,
                'updated_at' => $now
            ]);
            return $carry;
        }, []);

        return [
            'errno' => 0,
            'data' => $data
        ];
    }

    private function rules(int $id = null)
    {
        $rules = [
            'client' => 'nullable|string|in:pc,mobile',
            'meta' => 'nullable|array',
            'tags' => 'nullable|array',
            'slug' => ['nullable','alpha_dash','max:128',Rule::unique('posts')->ignore($id)]
        ];

        foreach (config('admini.languages') as $lang) {
            $rules["title_{$lang['language']}"] = 'required|string|max:100';
            $rules["subtitle_{$lang['language']}"] = 'nullable|string|max:512';
            $rules["addition_{$lang['language']}"] = 'nullable|string|max:512';
            $rules["content_{$lang['language']}"] = 'nullable|string';
        }

        return $rules;
    }

    public function __construct()
    {
        $this->middleware(function ($request, $next){
            if ( !!! (new Admini)->auth() ) {
                session(['url' => url()->current()]);
                return redirect()->route('admini.auth.login');
            } else return $next($request);
        });
    }
}