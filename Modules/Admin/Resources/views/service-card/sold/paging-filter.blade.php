<div class="m-datatable m-datatable--default">
    <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
        <ul class="m-datatable__pager-nav" style="float: right">
            @if(count($data)>10)
                @if($page>1)
                    <li><a onclick="pageClickFilter(1)" title="First"
                           class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1"><i
                                    class="la la-angle-double-left">
                            </i></a></li>
                    <li><a onclick="pageClickFilter({{$page-1}})" title="Previous"
                           class="m-datatable__pager-link m-datatable__pager-link--prev"><i
                                    class="la la-angle-left"></i></a></li>
                @else
                    <li><a title="First"
                           class="m-datatable__pager-link m-datatable__pager-link--first m-datatable__pager-link--disabled"
                           disabled="disabled"><i class="la la-angle-double-left"></i></a></li>
                    <li><a title="Previous"
                           class="m-datatable__pager-link m-datatable__pager-link--prev m-datatable__pager-link--disabled"
                           disabled="disabled"><i class="la la-angle-left"></i></a></li>
                @endif
                <?php
                $totalPage = 0;
                if (is_int(count($data) / 10) == true) {
                    $totalPage = (count($data) / 10) + 1;
                } else {
                    $totalPage = (int)(count($data) / 10) + 2;
                }
                ?>
                @for ($i=1;$i<$totalPage;$i++)
                    @if($i==$page)
                        <li><a class="m-datatable__pager-link m-datatable__pager-link--active"
                               onclick="pageClickFilter({{$i}})"
                               title="1">{{ $i }}</a></li>
                    @else
                        <li><a class="m-datatable__pager-link" onclick="pageClickFilter({{ $i }})">{{ $i }}</a></li>
                    @endif
                @endfor
                @if($page<$totalPage-1)
                    <li><a title="Next" class="m-datatable__pager-link" onclick="pageClickFilter({{$page+1}})"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last" onclick="pageClickFilter({{$totalPage-1}})"
                           class="m-datatable__pager-link m-datatable__pager-link--last"
                           data-page=""><i class="la la-angle-double-right"></i></a></li>
                @else
                    <li><a title="Next" class="m-datatable__pager-link m-datatable__pager-link--disabled"
                           disabled="disabled"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last"
                           class="m-datatable__pager-link m-datatable__pager-link--last m-datatable__pager-link--disabled"
                           disabled="disabled"
                           data-page=""><i class="la la-angle-double-right"></i></a></li>
                @endif
            @endif
        </ul>
        <div class="m-datatable__pager-info" style="float: left">
            <span class="m-datatable__pager-detail">
                {{--{{__('Hiển thị')}} {{($page-1)*10+1}} - {{($page-1)*10 + count($LIST)}} {{__('của')}} {{ count($data) }}--}}
                @if(count($data)>0)
                    {{__('Hiển thị')}} {{($page-1)*10+1}}
                    - {{($page-1)*10 + count($LIST)}}
                    {{__('của')}} {{ count($data) }}
                @else
                    {{__('Hiển thị 0 - 0 của 0')}}
                @endif
            </span>
        </div>
    </div>
</div>
