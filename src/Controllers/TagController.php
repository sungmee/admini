<?php

namespace Sungmee\Admini\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = DB::table('tags')->orderBy('type')->get()->map(function ($item, $key) {
            $item->names = json_decode($item->names, true);
            return $item;
        });
        $title = __('admini::post.tags.title');

        return view('admini::tags', compact('tags','title'));
    }

    public function store(Request $request)
    {
        $this->validate($request, $this->rules());

        DB::table('tags')->insertOrIgnore([
            'names' => json_encode($request->names),
            'slug' => $request->slug,
            'type' => $request->type
        ]);

        $request->session()->flash('alert', trans('admini::post.editor.store_success'));
        $request->session()->flash('alert-contextual', 'success');
        return back();
    }

    public function edit(Request $request, int $id = null)
    {
        $tags = DB::table('tags')->orderBy('type')->get()->map(function ($item, $key) {
            $item->names = json_decode($item->names, true);
            return $item;
        });
        if ($tag  = DB::table('tags')->find($id)) {
            $tag->names = json_decode($tag->names, true);
        }
        $title = __('admini::post.tags.title');

        return view('admini::tags', compact('tags', 'tag', 'title'));
    }

    public function update(Request $request, int $id)
    {
        $this->validate($request, $this->rules($id));

        DB::table('tags')
            ->where('id', $id)
            ->update([
                'names' => json_encode($request->names),
                'slug' => $request->slug,
                'type' => $request->type
            ]);

        $request->session()->flash('alert', trans('admini::post.editor.update_success'));
        $request->session()->flash('alert-contextual', 'success');
        return back();
    }

    public function destroy(int $id)
    {
        DB::table('tags')->where('id', $id)->delete();

        if (request()->ajax()) {
            return response()->json(['errno' => 0], 202);
        } else {
            $request->session()->flash('alert', trans('admini::post.editor.destory_success'));
            $request->session()->flash('alert-contextual', 'success');
            return back();
        }
    }

    public function rules(int $id = null)
    {
        $rules = [
            'names' => 'required|array',
            'type' => 'required|string|in:post,page,new,notice,file',
            'slug' => ['required','alpha_dash',Rule::unique('tags')->ignore($id)]
        ];

        return $rules;
    }
}
