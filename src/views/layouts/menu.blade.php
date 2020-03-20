@php
    $path = str_replace('/admini/', '', request()->getPathInfo());
    $pt = config('admini.post_type');
@endphp

@if ($pt['new'])
<li class="nav-item">
    <a class="nav-link{{ 'news' == $path ? ' active' : '' }}" href="{{ route('admini.posts.index', ['type' => 'news']) }}">{{ trans('admini::post.editor.news_index') }}</a>
</li>
<li class="nav-item">
    <a class="nav-link{{ 'news/create' == $path ? ' active' : '' }}" href="{{ route('admini.posts.create', ['type' => 'news']) }}">{{ trans('admini::post.editor.add_new') }}</a>
</li>
@endif
@if ($pt['notice'])
<li class="nav-item">
    <a class="nav-link{{ 'notices' == $path ? ' active' : '' }}" href="{{ route('admini.posts.index', ['type' => 'notices']) }}">{{ trans('admini::post.editor.notices_index') }}</a>
</li>
<li class="nav-item">
    <a class="nav-link{{ 'notices/create' == $path ? ' active' : '' }}" href="{{ route('admini.posts.create', ['type' => 'notices']) }}">{{ trans('admini::post.editor.add_notice') }}</a>
</li>
@endif
@if ($pt['post'])
<li class="nav-item">
    <a class="nav-link{{ 'posts' == $path ? ' active' : '' }}" href="{{ route('admini.posts.index', ['type' => 'posts']) }}">{{ trans('admini::post.editor.posts_index') }}</a>
</li>
<li class="nav-item">
    <a class="nav-link{{ 'posts/create' == $path ? ' active' : '' }}" href="{{ route('admini.posts.create', ['type' => 'posts']) }}">{{ trans('admini::post.editor.add_post') }}</a>
</li>
@endif
@if ($pt['page'])
<li class="nav-item">
    <a class="nav-link{{ 'pages' == $path ? ' active' : '' }}" href="{{ route('admini.posts.index', ['type' => 'pages']) }}">{{ trans('admini::post.editor.pages_index') }}</a>
</li>
<li class="nav-item">
    <a class="nav-link{{ 'pages/create' == $path ? ' active' : '' }}" href="{{ route('admini.posts.create', ['type' => 'pages']) }}">{{ trans('admini::post.editor.add_page') }}</a>
</li>
@endif
<li class="nav-item">
    <a class="nav-link{{ Str::contains($path, 'tags') ? ' active' : '' }}" href="{{ route('admini.tags.index') }}">{{ trans('admini::post.tags.title') }}</a>
</li>
<!-- <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" id="dropdown01" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Settings</a>
    <div class="dropdown-menu" aria-labelledby="dropdown01">
        <a class="dropdown-item" href="#">Action</a>
        <a class="dropdown-item" href="#">Another action</a>
        <a class="dropdown-item" href="#">Something else here</a>
    </div>
</li> -->