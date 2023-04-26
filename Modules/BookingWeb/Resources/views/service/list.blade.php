<div class="row height-row-list">
    @foreach($service as $value)
        <div class="col-6 col-sm-3">
                <a href="{{route('service.getServiceGroup.detail',['id' => $value['service_id']])}}">
{{--                    <img class="img-service" src="{{$value['service_avatar']}}">--}}
                    <div class="fix-img">
                        <img class="img-service" src="{{$value['service_avatar'] != null ? $value['service_avatar'] : asset('static/booking-template/image/default-placeholder.png')}}">
                    </div>
                    <p class="text-service">{{ str_limit($value['service_name'], $limit = 20, $end = '...') }}</p>
                </a>
        </div>
    @endforeach
</div>

<div class="kt-clearfix pagination">
        <div class="kt-pagination  kt-pagination--brand float-right">
                <ul class="kt-pagination__links">
                    @if($page['total'] != 0)
                        @if($page['current_page']>1)
                            <li>
                                <a onclick="step3.firstAndLastPage(1)" title="First"
                                   class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1">
                                    <i class="la la-angle-double-left"></i>
                                </a>
                            </li>
                            <li>
                                <a onclick="step3.firstAndLastPage({{$page['current_page']-1}})" title="Previous"
                                   class="m-datatable__pager-link m-datatable__pager-link--prev">
                                    <i class="la la-angle-left"></i></a>
                            </li>
                        @else
                            <li><a title="First"
                                   class="kt-datatable__pager-link kt-datatable__pager-link--prev kt-datatable__pager-link--disabled"
                                   disabled="disabled"><i class="la la-angle-double-left"></i></a></li>
                            <li><a title="Previous"
                                   class="kt-datatable__pager-link kt-datatable__pager-link--prev kt-datatable__pager-link--disabled"
                                   disabled="disabled"><i class="la la-angle-left"></i></a></li>
                        @endif
                        <?php
                        $totalPage = 0;
                        if (($page['total'] / $display) == true) {
                            $totalPage = ($page['total'] / $display) + 1;
                        } else {
                            $totalPage = ($page['total'] / $display) + 2;
                        }
                        ?>
                        @for ($i=1;$i<=$totalPage;$i++)
                            @if($i==$page['current_page'])
                                <li><a class="kt-pagination__link--active" onclick="step3.pageClick({{$i}})"
                                       title="1">{{ $i }}</a></li>
                            @else
                                <li><a class="m-datatable__pager-link" onclick="step3.pageClick({{$i}})">{{ $i }}</a></li>
                            @endif
                        @endfor
                        {{-- Next Page Link --}}
                        @if($page['current_page']<$totalPage-1)
                            <li><a title="Next" class="m-datatable__pager-link"
                                   onclick="step3.firstAndLastPage({{$page['current_page']+1}})"
                                   data-page=""><i class="la la-angle-right"></i></a></li>
                            <li><a title="Last" onclick="step3.firstAndLastPage({{$page['last_page']}})"
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

        </div>

</div>