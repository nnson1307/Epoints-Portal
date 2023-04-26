<div class="m-datatable m-datatable--default">
    <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
    	@if ($paginator->hasPages())
        <ul class="m-datatable__pager-nav" style="float: right">
        	{{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li><a title="First" class="m-datatable__pager-link m-datatable__pager-link--first m-datatable__pager-link--disabled" disabled="disabled"><i class="la la-angle-double-left"></i></a></li>
                <li><a title="Previous" class="m-datatable__pager-link m-datatable__pager-link--prev m-datatable__pager-link--disabled" disabled="disabled"><i class="la la-angle-left"></i></a></li>
            @else
                <li><a title="First" class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1" onclick="layoutWork.searchPage(1)"><i class="la la-angle-double-left"></i></a></li>
                <li><a title="Previous" class="m-datatable__pager-link m-datatable__pager-link--prev" onclick="layoutWork.searchPage({{ $paginator->currentPage() - 1 }})" data-page="{{ $paginator->currentPage() - 1 }}"><i class="la la-angle-left"></i></a></li>
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
                            <li><a class="m-datatable__pager-link m-datatable__pager-link--active"  onclick="layoutWork.searchPage({{ $page }})" data-page="{{ $page }}" title="{{ $page }}">{{ $page }}</a></li>
                        @else
                        	<li><a class="m-datatable__pager-link" onclick="layoutWork.searchPage({{ $page }})" data-page="{{ $page }}">{{ $page }}</a></li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            {{-- Next Page Link --}}
            @if ($paginator->currentPage() == $paginator->lastPage())
                <li><a title="Next" class="m-datatable__pager-link m-datatable__pager-link--next m-datatable__pager-link--disabled" disabled="disabled"><i class="la la-angle-right"></i></a></li>
            	<li><a title="Last" class="m-datatable__pager-link m-datatable__pager-link--last m-datatable__pager-link--disabled" disabled="disabled"><i class="la la-angle-double-right"></i></a></li>
            @else
                <li><a title="Next" class="m-datatable__pager-link m-datatable__pager-link--next" onclick="layoutWork.searchPage({{ $paginator->currentPage() + 1 }})" data-page="{{ $paginator->currentPage() + 1 }}"><i class="la la-angle-right"></i></a></li>
            	<li><a title="Last" class="m-datatable__pager-link m-datatable__pager-link--last" onclick="layoutWork.searchPage({{ $paginator->lastPage() }})" data-page="{{ $paginator->lastPage() }}"><i class="la la-angle-double-right"></i></a></li>
            @endif
        </ul>
        @endif
        <div class="m-datatable__pager-info" style="float: left" >
            <span class="m-datatable__pager-detail">@lang('Hiển thị') {{ $paginator->firstItem() }} - {{ $paginator->lastItem() }} @lang('của') {{ $paginator->total() }}</span>
        </div>
    </div>
</div>
