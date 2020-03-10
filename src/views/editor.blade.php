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
                            <input class="form-check-input" type="{{ $item['type'] }}" name="meta[{{ $item['name'] }}]" id="meta_{{ $item['name'] }}" value="{{ $i['value'] }}"{{ $value == $i['value'] ? ' checked' : '' }}>
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
                    {!! old('content_'.$item['language'], $post->{$item['language']}->{$client} ?? '') !!}
                </div>
                <textarea class="form-control dn" id="c{{ $item['language'] }}" name="content_{{ $item['language'] }}"></textarea>
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
    var E = window.wangEditor

    var config = function (e) {
        let onchange = function (html) {
            e.val(html)
        }
        return {
            onchange,
            uploadImgServer: '/admini/upload',
            uploadImgMaxLength: 1,
            uploadImgParams: {
                _token: $('meta[name="csrf-token"]').attr('content')
            }
        }
    }

    let languages = @json($config['languages'])

    languages.forEach(item => {
        let e = item.language
        let d = 'c' + item.language
        window[e] = new E('#'+item.language)
        window[d] = $('#c'+item.language)
        window[e].customConfig = config(window[d])
        window[e].create()
        window[d].val(window[e].txt.html())
    })
</script>
@endpush