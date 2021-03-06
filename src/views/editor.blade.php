@extends('admini::layouts.app')

@section('title', $title ?? trans('admini::post.title.default'))

@php
    $config = config('admini');
    $language = collect($config['languages'])->pluck('language', 'locale');
    $language = $language[app()->getLocale()];
@endphp

@section('content')
<form class="pb-3" action="{{ $action }}" method="POST">
    <div class="my-3 p-3 bg-white rounded shadow-sm {{ $client ?? '' }}">
        <div class="row media text-muted pt-3">
            <div class="form-group col-12">
                <label for="slug">{{ trans('admini::post.editor.slug_label') }}</label>
                <div class="input-group">
                    <div class="input-group-prepend">
                        <div class="input-group-text">{{ config('app.url') }}/</div>
                    </div>
                    <input type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" id="slug" name="slug" value="{{ old('slug', $post->slug ?? '') }}" placeholder="{{ time() }}" autofocus>
                </div>
                @if ($errors->has('slug'))
                <div class="invalid-feedback">
                    {{ $errors->first('slug') }}
                </div>
                @endif
            </div>
            @foreach ($config['post_meta'] as $item)
            <div class="form-group col-{{ $item['col'] ?? 12 }}">
                @php
                    $value = old('meta.'.$item['name'], $post->meta[$item['name']] ?? '');
                @endphp
                @switch($item['type'])
                    @case('radio')
                    @case('checkbox')
                        @foreach ($item['radios'] as $i)
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="{{ $item['type'] }}" name="meta[{{ $item['name'] }}]" id="meta_{{ $item['name'] }}_{{ $i['value'] }}" value="{{ $i['value'] }}"{{ $value == $i['value'] ? ' checked' : '' }}>
                            <label class="form-check-label" for="meta[{{ $item['name'] }}]">{{ trans($i['label']) }}</label>
                        </div>
                        @endforeach
                    @break
                    @case('textarea')
                        <label for="meta[{{ $item['name'] }}]">{{ trans($item['label']) }}</label>
                        <textarea class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" id="meta_{{ $item['name'] }}" name="meta[{{ $item['name'] }}]">{{ $value }}</textarea>
                    @break
                    @default
                        <label for="meta[{{ $item['name'] }}]">{{ trans($item['label']) }}</label>
                        <input type="{{ $item['type'] }}" class="form-control" id="meta_{{ $item['name'] }}" name="meta[{{ $item['name'] }}]" value="{{ $value }}">
                @endswitch
            </div>
            @endforeach
        </div>
    </div>

    @if ($tags->count())
    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">{{ trans('admini::post.tags.tags') }}</h6>
        <div class="row media text-muted pt-3">
            <div class="form-group col-12">
                @forelse ($tags as $item)
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="checkbox" name="tags[]" id="tag_{{ $item->id }}" value="{{ $item->id }}"{{
                        in_array($item->id, $taggables) ? ' checked' : '' }}>
                    <label class="form-check-label" for="tags">{{ json_decode($item->names, true)[$language] }}</label>
                </div>
                @empty
                <a class="btn btn-link" href="{{ route('admini.tags.index') }}">{{ trans('admini::post.tags.title') }}</a>
                @endforelse
            </div>
        </div>
    </div>
    @endif

    @foreach ($config['languages'] as $item)
    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">{{ trans('admini::locale.'.$item['language']) }}</h6>
        <div class="row media text-muted pt-3">
            <div class="form-group col-12 {{ $client ?? '' }}">
                <input type="text" class="form-control{{ $errors->has('title_'.$item['language']) ? ' is-invalid' : '' }}" name="title_{{ $item['language'] }}" value="{{ old('title_'.$item['language'], $post->{$item['language']}->title ?? '') }}" placeholder="{{ trans('admini::locale.'.$item['language']) }} {{ trans('admini::post.editor.title') }}" required>
                @if ($errors->has('title_'.$item['language']))
                <div class="invalid-feedback">
                    {{ $errors->first('title_'.$item['language']) }}
                </div>
                @endif
            </div>
            <div class="form-group col-12 {{ $client ?? '' }} {{ $config['post_subtitle'] ? '' : 'dn' }}">
                <input type="text" class="form-control{{ $errors->has('subtitle_'.$item['language']) ? ' is-invalid' : '' }}" name="subtitle_{{ $item['language'] }}" value="{{ old('subtitle_'.$item['language'], $post->{$item['language']}->subtitle ?? '') }}" placeholder="{{ trans('admini::locale.'.$item['language']) }} {{ trans('admini::post.editor.subtitle') }}">
                @if ($errors->has('subtitle_'.$item['language']))
                <div class="invalid-feedback">
                    {{ $errors->first('subtitle_'.$item['language']) }}
                </div>
                @endif
            </div>
            <div class="form-group col-12 {{ $client ?? '' }} {{ $config['post_addition'] ? '' : 'dn' }}">
                <input type="text" class="form-control{{ $errors->has('addition_'.$item['language']) ? ' is-invalid' : '' }}" name="addition_{{ $item['language'] }}" value="{{ old('addition_'.$item['language'], $post->{$item['language']}->addition ?? '') }}" placeholder="{{ trans('admini::locale.'.$item['language']) }} {{ trans('admini::post.editor.addition') }}">
                @if ($errors->has('addition_'.$item['language']))
                <div class="invalid-feedback">
                    {{ $errors->first('addition_'.$item['language']) }}
                </div>
                @endif
            </div>
            <div class="form-group col-12">
                <div id="{{ $item['language'] }}">
                </div>
                <textarea class="form-control dn content" id="c{{ $item['language'] }}" name="content_{{ $item['language'] }}">{!! old('content_'.$item['language'], $post->{$item['language']}->{$client} ?? '') !!}</textarea>
            </div>
        </div>
    </div>
    @endforeach

    @csrf

    @if (isset($post) && !empty($post))
    @method('PUT')
    @endif

    <button class="btn btn-outline-success btn-block my-2 my-sm-0" type="submit">{{ trans('admini::post.editor.submit') }}</button>
</form>
@stop

@push('scripts')
<script>
    let codeToggler = {
        init: e => $(`#${e} .w-e-toolbar`).prepend(`<div class="w-e-menu btn-code"><a class="_wangEditor_btn_code" href="javascript:;" onclick="codeToggler.toggle('${e}')">HTML</a></div>`),
        toggle: e => {
            let btn = $(`#${e} ._wangEditor_btn_code`)
            $(`#${e} .w-e-text-container, #${e} .w-e-menu, #c${e}`).toggle()
            $(`#${e} .btn-code`).show()
            btn.text() == 'HTML' ? btn.text('Visual Editor') : btn.text('HTML')
        }
    }

    let languages = @json($config['languages'])

    let et = {}, E = window.wangEditor, config = lang => {
        return {
            onchange: html => $(`#c${lang}`).val(html),
            uploadImgServer: '/admini/upload',
            uploadImgMaxLength: 1,
            uploadImgParams: {
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        }
    }

    languages.forEach(i => {
        et[i.language] = new E(`#${i.language}`)
        et[i.language].customConfig = config(i.language)
        et[i.language].create()
        codeToggler.init(i.language)
        et[i.language].txt.html($(`#c${i.language}`).val())
        $(`#c${i.language}`).change(function() {
            et[i.language].txt.html($(this).val())
        })
    })
</script>
@endpush