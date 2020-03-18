@extends('admini::layouts.app')

@section('title', $title ?? trans('admini::post.title.default'))

@php
    $config = config('admini');
@endphp

@section('content')
<form class="pb-3" action="{{ $action }}" method="POST">
    <div class="my-3 p-3 bg-white rounded shadow-sm {{ $client ?? '' }}">
        <div class="row media text-muted pt-3">
            <div class="form-group col-12">
                <label for="slug">{{ trans('admini::post.editor.slug_label') }}</label>
                <input type="text" class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" id="slug" name="slug" value="{{ old('slug', $post->slug ?? '') }}" autofocus>
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
                            <label class="form-check-label" for="meta[{{ $item['name'] }}]">{{ $i['label'] }}</label>
                        </div>
                        @endforeach
                    @break
                    @case('textarea')
                        <label for="meta[{{ $item['name'] }}]">{{ $item['label'] }}</label>
                        <textarea class="form-control{{ $errors->has('slug') ? ' is-invalid' : '' }}" id="meta_{{ $item['name'] }}" name="meta[{{ $item['name'] }}]">{{ $value }}</textarea>
                    @break
                    @default
                        <label for="meta[{{ $item['name'] }}]">{{ $item['label'] }}</label>
                        <input type="{{ $item['type'] }}" class="form-control" id="meta_{{ $item['name'] }}" name="meta[{{ $item['name'] }}]" value="{{ $value }}">
                @endswitch
            </div>
            @endforeach
        </div>
    </div>

    @foreach ($config['languages'] as $item)
    <div class="my-3 p-3 bg-white rounded shadow-sm">
        <h6 class="border-bottom border-gray pb-2 mb-0">{{ trans('admini::locale.'.$item['language']) }}</h6>
        <div class="row media text-muted pt-3">
            <div class="form-group col-12 {{ $client ?? '' }}">
                <input type="text" class="form-control{{ $errors->has('title_'.$item['language']) ? ' is-invalid' : '' }}" name="title_{{ $item['language'] }}" value="{{ old('title_'.$item['language'], $post->{$item['language']}->title ?? '') }}" placeholder="{{ trans('admini::locale.'.$item['language']) }}{{ trans('admini::post.editor.title') }}" required>
                @if ($errors->has('title_'.$item['language']))
                <div class="invalid-feedback">
                    {{ $errors->first('title_'.$item['language']) }}
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
        init: e => $(`#${e} .w-e-toolbar`).prepend(`<div class="w-e-menu btn-code"><a class="_wangEditor_btn_code" href="javascript:;" onclick="codeToggler.toggle('${e}')">CODE</a></div>`),
        toggle: e => {
            let btn = $(`#${e} ._wangEditor_btn_code`)
            $(`#${e} .w-e-text-container, #${e} .w-e-menu, #c${e}`).toggle()
            $(`#${e} .btn-code`).show()
            btn.text() == 'CODE' ? btn.text('HTML') : btn.text('CODE')
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
        // $(`#c${i.language}`).val(et[i.language].txt.html()).change(function() {
        $(`#c${i.language}`).change(function() {
            et[i.language].txt.html($(this).val())
        })
    })
</script>
@endpush