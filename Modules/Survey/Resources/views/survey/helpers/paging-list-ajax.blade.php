@php
    $listOption = [25, 50, 75, 100, ];
    $selected = (isset($_GET['perpage'])) ? $_GET['perpage'] : PAGING_ITEM_PER_PAGE;
    $frm = isset($frm) ? $frm : 'form-filter';
    $displaySelect = isset($display) ? $display : true;
@endphp
<div class="kt-pagination kt-pagination--brand kt-pagination--circle">
    <div class="kt-pagination__toolbar">
        <select class="form-control kt-font-brand"
                name="perpage" id="perpage" onchange="Endpoint.loadSurvey(1)" style="width: 60px">
            <option value="25" @if($paginator->perPage()== 25) selected @endif>25</option>
            <option value="50" @if($paginator->perPage()== 50) selected @endif>50</option>
            <option value="75" @if($paginator->perPage()== 75) selected @endif>75</option>
            <option value="100" @if($paginator->perPage()== 100) selected @endif>100</option>
        </select>
        <span class="m-datatable__pager-detail">@lang('Hiển thị')
            {{ $paginator->firstItem() == null ? '0' : $paginator->firstItem() }}
            - {{ $paginator->lastItem() }}
            @lang('của') {{ $paginator->total() }}</span>
    </div>
    @if ($paginator->hasPages())
        <ul class="kt-pagination__links">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="kt-pagination__link--first">
                    <a title="First"
                       class="m-datatable__pager-link m-datatable__pager-link--first m-datatable__pager-link--disabled"
                       disabled="disabled">
                        <i class="fa fa-angle-double-left"></i>
                    </a>
                </li>
                <li class="kt-pagination__link--next">
                    <a title="Previous"
                       class="m-datatable__pager-link m-datatable__pager-link--prev m-datatable__pager-link--disabled"
                       disabled="disabled">
                        <i class="fa fa-angle-left"></i>
                    </a>
                </li>
            @else
                <li class="kt-pagination__link--next">
                    <a title="First" class="m-datatable__pager-link m-datatable__pager-link--first"
                       href="javascript:void(0)" onclick="Endpoint.loadSurvey(1)">
                        <i class="fa fa-angle-double-left kt-font-brand"></i>
                    </a>
                </li>
                <li class="kt-pagination__link--next">
                    <a title="Previous" class="m-datatable__pager-link m-datatable__pager-link--prev"
                       href="javascript:void(0)" onclick="Endpoint.loadSurvey('{{$paginator->currentPage() - 1}}')">
                        <i class="fa fa-angle-left kt-font-brand"></i>
                    </a>
                </li>
            @endif

            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <li><a disabled="disabled" title="More pages"
                           class="m-datatable__pager-link m-datatable__pager-link--more-next m-datatable__pager-link--disabled"><i
                                    class="la la-ellipsis-h"></i></a></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="kt-pagination__link--active">
                                <a href="javascript:void(0)" onclick="Endpoint.loadSurvey({{"$page"}})" title="{{ $page }}">{{ $page }}
                                </a>
                            </li>
                        @else
                            <li>
                                <a href="javascript:void(0)" onclick="Endpoint.loadSurvey({{"$page"}})">
                                    {{ $page }}
                                </a>
                            </li>
                        @endif
                    @endforeach
                @endif
            @endforeach
            {{-- Next Page Link --}}
            @if ($paginator->currentPage() == $paginator->lastPage())
                <li class="kt-pagination__link--prev">
                    <a title="Next"
                       class="m-datatable__pager-link m-datatable__pager-link--next m-datatable__pager-link--disabled"
                       disabled="disabled">
                        <i class="fa fa-angle-right"></i>
                    </a>
                </li>
                <li class="kt-pagination__link--last">
                    <a title="Last"
                       class="m-datatable__pager-link m-datatable__pager-link--last m-datatable__pager-link--disabled"
                       disabled="disabled">
                        <i class="fa fa-angle-double-right"></i>
                    </a>
                </li>
            @else
                <li class="kt-pagination__link--prev">
                    <a title="Next" class="m-datatable__pager-link m-datatable__pager-link--next"
                       href="javascript:void(0)" onclick="Endpoint.loadSurvey('{{$paginator->currentPage() + 1}}')">
                        <i class="fa fa-angle-right kt-font-brand"></i>
                    </a>
                </li>
                <li class="kt-pagination__link--last">
                    <a title="Last" class="m-datatable__pager-link m-datatable__pager-link--last"
                       href="javascript:void(0)" onclick="Endpoint.loadSurvey('{{$paginator->lastPage()}}')">
                        <i class="fa fa-angle-double-right kt-font-brand"></i>
                    </a>
                </li>
            @endif
        </ul>
    @endif
</div>
