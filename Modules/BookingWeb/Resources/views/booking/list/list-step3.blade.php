@if(isset($LIST_SERVICE))
    <table class="table">
        <tbody>
        @foreach($LIST_SERVICE['data'] as $item)
            <tr>
                <td class="td-img">
                    @if(isset($item['service_avatar']))
                        <img src="{{asset($item['service_avatar'])}}">
                    @else
                        <img src="{{asset('static/booking-template/image/default-placeholder.png')}}">
                    @endif

                </td>
                <td>
                    <span class="kt-font-bold">{{$item['service_name']}}</span><br/>
                    @if($setting[2]['is_actived'])
                        <span class="weight-400"><i class="la la-clock-o"></i>{{$item['time']}} phút</span><br/>
                    @endif
                    @if($setting[1]['is_actived'])
                        <span class="kt-font-bold"><i class="la la-money"></i>{{number_format($item['new_price'])}} đ</span>
                    @endif
                </td>
                <td>
                    <label>
                        @if(in_array($item['service_id'],$arr_service))
                            <input type="checkbox" name="check_service" class="checkbox-service"
                                   value="{{$item['service_id']}}" onclick="step3.check_service(this)" checked/>
                        @else
                            <input type="checkbox" name="check_service" class="checkbox-service"
                                   value="{{$item['service_id']}}" onclick="step3.check_service(this)"/>
                        @endif

                        <span class="span-checkbox-service"></span>
                    </label>
                </td>
            </tr>
        @endforeach

        </tbody>
    </table>
@endif

{{--<div class="kt-clearfix">--}}
{{--    <div class="kt-pagination  kt-pagination--brand float-right">--}}
{{--        <ul class="kt-pagination__links ">--}}
{{--            <li class="kt-pagination__link--first">--}}
{{--                <a href="#"><i class="fa fa-angle-double-left kt-font-brand"></i></a>--}}
{{--            </li>--}}
{{--            <li class="kt-pagination__link--next">--}}
{{--                <a href="#"><i class="fa fa-angle-left kt-font-brand"></i></a>--}}
{{--            </li>--}}

{{--            <li>--}}
{{--                <a href="" class="kt-pagination__link--active">1</a>--}}
{{--            </li>--}}
{{--            <li>--}}
{{--                <a href="#">2</a>--}}
{{--            </li>--}}
{{--            <li>--}}
{{--                <a href="#">3</a>--}}
{{--            </li>--}}
{{--            <li class="kt-pagination__link--prev">--}}
{{--                <a href="#"><i class="fa fa-angle-right kt-font-brand"></i></a>--}}
{{--            </li>--}}
{{--            <li class="kt-pagination__link--last">--}}
{{--                <a href="#"><i class="fa fa-angle-double-right kt-font-brand"></i></a>--}}
{{--            </li>--}}
{{--        </ul>--}}
{{--    </div>--}}
{{--</div>--}}

<div class="kt-clearfix">
    <div class="kt-pagination  kt-pagination--brand float-right">
        <ul class="kt-pagination__links">
            @if($LIST_SERVICE['current_page']>1)
                <li>
                    <a onclick="step3.firstAndLastPage(1)" title="First"
                       class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1">
                        <i class="la la-angle-double-left"></i>
                    </a>
                </li>
                <li>
                    <a onclick="step3.firstAndLastPage({{$LIST_SERVICE['current_page']-1}})" title="Previous"
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
            if (($LIST_SERVICE['total'] / 10) == true) {
                $totalPage = ($LIST_SERVICE['total'] / 10) + 1;
            } else {
                $totalPage = ($LIST_SERVICE['total'] / 10) + 2;
            }
            ?>
            @for ($i=1;$i<$totalPage;$i++)
                @if($i==$LIST_SERVICE['current_page'])
                    <li><a class="kt-pagination__link--active" onclick="step3.pageClick({{$i}})"
                           title="1">{{ $i }}</a></li>
                @else
                    <li><a class="m-datatable__pager-link" onclick="step3.pageClick({{$i}})">{{ $i }}</a></li>
                @endif
            @endfor
            {{-- Next Page Link --}}
            @if($LIST_SERVICE['current_page']<$totalPage-1)
                <li><a title="Next" class="m-datatable__pager-link"
                       onclick="step3.firstAndLastPage({{$LIST_SERVICE['current_page']+1}})"
                       data-page=""><i class="la la-angle-right"></i></a></li>
                <li><a title="Last" onclick="step3.firstAndLastPage({{$LIST_SERVICE['last_page']}})"
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
        </ul>

    </div>

</div>