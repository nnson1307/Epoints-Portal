@php
    $paramsChange = $params = Request::all();
    $route = Route::current()->getName();
    if(isset($paramsChange['display'])){
        unset($paramsChange['display']);
    }
    $currentUrl = route($route,$params);
@endphp
<div class="referral-page m-datatable m-datatable--default">
    <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
        @if ($paginator->hasPages())
            <ul class="m-datatable__pager-nav" style="float: right">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <li><a title="First" class="m-datatable__pager-link m-datatable__pager-link--first m-datatable__pager-link--disabled" disabled="disabled"><i class="la la-angle-double-left"></i></a></li>
                    <li><a title="Previous" class="m-datatable__pager-link m-datatable__pager-link--prev m-datatable__pager-link--disabled" disabled="disabled"><i class="la la-angle-left"></i></a></li>
                @else
                    <li><a href="{{ route($route, array_merge($params , ['page' => 1])) }}" title="First" class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1"><i class="la la-angle-double-left"></i></a></li>
                    <li><a href="{{ $paginator->previousPageUrl() }}" title="Previous" class="m-datatable__pager-link m-datatable__pager-link--prev" data-page="{{ $paginator->currentPage() - 1 }}"><i class="la la-angle-left"></i></a></li>
                @endif

                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <li><a disabled="disabled" title="More pages" class="m-datatable__pager-link m-datatable__pager-link--more-next m-datatable__pager-link--disabled"><i class="la la-ellipsis-h"></i></a></li>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <li><a class="m-datatable__pager-link m-datatable__pager-link--active" data-page="{{ $page }}" title="{{ $page }}">{{ $page }}</a></li>
                            @else
                                <li><a href="{{ $url}}" class="m-datatable__pager-link" data-page="{{ $page }}">{{ $page }}</a></li>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                {{-- Next Page Link --}}
                @if ($paginator->currentPage() == $paginator->lastPage())
                    <li><a title="Next" class="m-datatable__pager-link m-datatable__pager-link--next m-datatable__pager-link--disabled" disabled="disabled"><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last" class="m-datatable__pager-link m-datatable__pager-link--last m-datatable__pager-link--disabled" disabled="disabled"><i class="la la-angle-double-right"></i></a></li>
                @else
                    <li><a href="{{ $paginator->nextPageUrl()}}" title="Next" class="m-datatable__pager-link m-datatable__pager-link--next" data-page="{{ $paginator->currentPage() + 1 }}"><i class="la la-angle-right"></i></a></li>
                    <li><a href="{{ route($route, array_merge($params , ['page' => $paginator->lastPage()])) }}" title="Last" class="m-datatable__pager-link m-datatable__pager-link--last" data-page="{{ $paginator->lastPage() }}"><i class="la la-angle-double-right"></i></a></li>
                @endif
            </ul>
        @endif
        <div class="m-datatable__pager-info" style="float: left" >
            @php

            //Route::current()->getName()
                    $display = [10 => 10, 25 => 25, 50 => 50, 100 => 100];
                    //$display = [1 => 1, 2 => 2];
            @endphp
            {{--            {!! Form::select('display', $display, $paginator->perPage(), ['class' => 'm-datatable__pager-info selectpicker m-datatable__pager-size d-block width70']) !!}--}}
            <select onchange="window.location.href = '{{ route($route,$params) }}&display='+$('#select2-change').val()" id="select2-change"  class="pager-selected m-datatable__pager-info selectpicker d-block width70" >
                @foreach($display as $item)
                    <option @if($paginator->perPage() == $item) selected @endif value="{{$item}}">{{$item}}</option>
                @endforeach
            </select>
            <span class="m-datatable__pager-detail">@lang('Hiển thị') {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} @lang('của') {{ $paginator->total() }}</span>
        </div>
    </div>
</div>
<style>
    .referral-page .m-datatable__pager-info .selectpicker {
        width: 70px!important;
        display: inline!important;
    }
    .referral-page .bootstrap-select {
        display: inline-block !important;
        width:80px!important;
    }

</style>
<script>

    $(function(){
        $('#select2-change').select2();
    });
</script>
