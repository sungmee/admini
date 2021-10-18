@extends('admini::layouts.app')

@section('title', $title ?? trans('admini::post.title.default'))

@php
    $index = -1;
    $color = ['#007bff','#e83e8c','#6f42c1'];
@endphp

@section('content')
<div class="my-3 p-3 bg-white rounded shadow-sm">
    <h6 class="border-bottom border-gray pb-2 mb-0">{{ trans('admini::post.index.recent_update') }}</h6>
    @forelse ($posts->items() as $item)
    @php $index = $index < 2 ? $index + 1 : 0; @endphp
    <div class="media text-muted pt-3" id="item-{{ $item->post_id }}">
        <svg class="bd-placeholder-img mr-2 rounded" width="32" height="32" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid slice" focusable="false" role="img" aria-label="Placeholder: 32x32"><title>Placeholder</title><rect width="100%" height="100%" fill="{{ $color[$index] }}"/><text x="50%" y="50%" fill="{{ $color[$index] }}" dy=".3em">32x32</text></svg>
        <p class="media-body pb-3 mb-0 small lh-125 border-bottom border-gray">
            <strong class="d-block text-gray-dark"><a href="{{ route('page', ['page' => $item->slug]) }}" title="{{ trans('admini::post.index.view') }}" target="_blank">{{ $item->title ?? Str::title($item->slug) }}</a></strong>
            {{ trans('admini::post.index.created_at') }}: {{ $item->created_at }}
            | {{ trans('admini::post.index.updated_at') }}: {{ $item->updated_at }}
            | <a class="" href="{{ route('admini.posts.edit', ['type' => $type, 'id' => $item->post_id]) }}">{{ trans('admini::post.index.edit_pc_version') }}</a>
            @if (config('admini.mobile_version'))
            | <a class="" href="{{ route('admini.posts.edit', ['type' => $type, 'id' => $item->post_id]) }}?client=mobile">{{ trans('admini::post.index.edit_mobile_version') }}</a>
            @endif
            @if (in_array($type, config('admini.delete_botton_in_post_types')))
            | <a class="del" href="javascript:;" data-id="{{ $item->post_id }}" data-title="{{ $item->title }}" data-url="{{ route('admini.posts.destory', ['type' => $type, 'id' => $item->post_id]) }}">{{ trans('admini::post.index.destory') }}</a>
            @endif
        </p>
    </div>
    @empty
        <p>{{ trans('admini::post.index.no_data') }}</p>
    @endforelse
    <small class="d-block mt-3">
        {{ $posts->links() }}
    </small>
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