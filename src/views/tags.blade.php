@extends('admini::layouts.app')

@section('title', trans('admini::post.tags.title'))

@php
    $index = -1;
    $color = ['#007bff','#e83e8c','#6f42c1'];
@endphp

@section('content')
<div class="row">
    <div class="col-4">
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">{{ isset($tag) ? trans('admini::post.tags.edit') : trans('admini::post.editor.add') }}{{ trans('admini::post.tags.tag') }}</h6>
            <div class="media text-muted pt-3">
                <form action="{{ isset($tag) ? route('admini.tags.update', $tag->id) : route('admini.tags.store') }}" method="POSt" style="width:100%">
                    <div class="form-group{{ $errors->has('names') ? ' has-error' : '' }}">
                        <label for="names">{{ trans('admini::post.tags.name') }}</label>
                        @foreach (config('admini.languages') as $item)
                        <input
                            type="text"
                            class="form-control tag-name {{ $loop->first ? 'tag-name-first' : ($loop->last ? 'tag-name-last' : '') }}"
                            name="names[{{ $item['language'] }}]"
                            value="{{ old('names.'.$item['language'], $tag->names[$item['language']] ?? '') }}"
                            placeholder="{{ trans('admini::locale.'.$item['language']) }}{{ trans('admini::post.tags.name') }}">
                        @endforeach
                        @if ($errors->has('names'))
                            <span class="help-block">
                                <strong>{{ $errors->first('names') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('slug') ? ' has-error' : '' }}">
                        <label for="agent_id">{{ trans('admini::post.editor.slug') }}</label>
                        <input
                            type="text"
                            step="1"
                            class="form-control"
                            name="slug"
                            value="{{ old('slug', $tag->slug ?? '') }}"
                            placeholder="{{ trans('admini::post.editor.slug') }}">
                        @if ($errors->has('slug'))
                            <span class="help-block">
                                <strong>{{ $errors->first('slug') }}</strong>
                            </span>
                        @endif
                    </div>

                    <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                        <label for="type">{{ trans('admini::post.tags.type') }}</label>
                        <select
                            class="form-control"
                            name="type">
                            @foreach (config('admini.post_type') as $k => $v)
                            @if ($v)
                                <option value="{{ $k }}"{{ old('type', $tag->type ?? '')==$k ? ' selected' : '' }}>{{ trans("admini::post.post_type.$k") }}</option>
                            @endif
                            @endforeach
                        </select>
                        @if ($errors->has('type'))
                            <span class="help-block">
                                <strong>{{ $errors->first('type') }}</strong>
                            </span>
                        @endif
                    </div>

                    @csrf

                    @if (isset($tag))
                        @method('PUT')
                    @endif

                    <button type="submit" class="btn btn-primary">{{ isset($tag) ? trans('admini::post.tags.update') : trans('admini::post.editor.add') }}</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-8">
        <div class="my-3 p-3 bg-white rounded shadow-sm">
            <h6 class="border-bottom border-gray pb-2 mb-0">{{ trans('admini::post.tags.list') }}</h6>
            @forelse ($tags as $item)
            @php $index = $index < 2 ? $index + 1 : 0; @endphp
            <div class="media text-muted pt-3" id="item-{{ $item->id }}">
                <svg class="bd-placeholder-img mr-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 32x32"><title>Placeholder</title><rect width="100%" height="100%" fill="{{ $color[$index] }}"/><text x="50%" y="50%" fill="{{ $color[$index] }}" dy=".3em">32x32</text></svg>
                <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
                    <strong class="d-block text-gray-dark"><a href="#" title="{{ trans('admini::post.index.view') }}" target="_blank">{{ join(' / ', $item->names) }}</a></strong>
                    {{ trans('admini::post.tags.type') }}: {{ trans("admini::post.post_type.{$item->type}") }}
                    | <a class="" href="{{ route('admini.tags.edit', ['tag' => $item->id]) }}">{{ trans('admini::post.tags.edit') }}</a>
                    | <a class="del" href="javascript:;" data-id="{{ $item->id }}" data-title="{{ join(' / ', $item->names) }}" data-url="{{ route('admini.tags.destroy', ['tag' => $item->id]) }}">{{ trans('admini::post.index.destory') }}</a>
                </p>
            </div>
            @empty
                <p>{{ trans('admini::post.index.no_data') }}</p>
            @endforelse
            <small class="d-block text-right mt-3">
                <a href="?list=all">{{ trans('admini::post.index.all') }}</a>
            </small>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script>
$('.del').on('click', function() {
    let id = $(this).data('id')
    let title = $(this).data('title')
    let url = $(this).data('url')
    let msg = `{{ trans('admini::post.index.destory_confirm') }}`
    let conf = {
        _method: "delete",
        _token: $('meta[name="csrf-token"]').attr('content')
    }
    if (confirm(msg) == true) {
        $.post(url, conf, res => {
            $('#item-'+id).fadeOut()
        })
    }
})
</script>
@endpush