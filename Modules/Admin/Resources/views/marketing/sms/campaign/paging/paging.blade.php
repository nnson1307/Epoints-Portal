<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table">
        <thead>
        <tr class="ss--nowrap">
            <th class="ss--font-size-th ss--text-center">#</th>
            <th class="ss--font-size-th">{{__('CHIẾN DỊCH')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('NGƯỜI TẠO')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('NGÀY TẠO')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('NGƯỜI GỬI')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('NGÀY GỬI')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('SỐ TIN NHẮN')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('THÀNH CÔNG')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('LỖI')}}</th>
            <th class="ss--font-size-th ss--text-center">{{__('TRẠNG THÁI')}}</th>
            <th class="ss--font-size-th ss--text-center"></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $value)
                <tr class="ss--font-size-13">
                    <td class="ss--text-center">{{$key+1}}</td>
                    <td>
                        <a href="{{route('admin.campaign.detail',$value['campaign_id'])}}" class="ss--text-black">
                            {{$value['sms_campaign_name']}}
                        </a>
                    </td>
                    <td class="ss--text-center ss--nowrap">{{$value['created_by']}}</td>
                    <td class="ss--text-center ss--nowrap">{{(new DateTime($value['created_at']))->format('d/m/Y')}}</td>
                    <td class="ss--text-center ss--nowrap">{{$value['sent_by']}}</td>
                    <td class="ss--text-center ss--nowrap">{{($value['time_sent'] != null)?(new DateTime($value['time_sent']))->format('d/m/Y'):''}}</td>
                    <td class="ss--text-center ss--nowrap">
                        {{$value['totalMessage']}}
                    </td>
                    <td class="ss--font-size-13 ss--text-center ss--nowrap">
                        @if($value['status']=='cancel')
                            <span class="m--font-success">0</span>
                        @else
                            <span class="m--font-success">{{$value['messageSuccess']}}</span>
                        @endif
                    </td>
                    <td class="ss--font-size-13 ss--text-center ss--nowrap">
                        @if($value['status']=='cancel')
                            <span class="m--font-danger">0</span>
                        @else
                            <span class="m--font-danger">{{$value['messageError']}}</span>
                        @endif
                    </td>
                    <td class="ss--text-center ss--nowrap">
                        @if($value['status']=='new')
                            <span class="m--font-info">{{__('Mới')}}</span>
                        @elseif($value['status']=='sent')
                            <span class="m--font-success">{{__('Hoàn thành')}}</span>
                        @elseif($value['status']=='cancel')
                            <span class="m--font-danger">{{__('Hủy')}}</span>
                        @endif
                    </td>
                    @if($value['status']=='new')
                        <td class="ss--text-center">
                            @if(in_array('admin.campaign.edit',session('routeList')))
                                <a href="{{route('admin.campaign.edit',$value['campaign_id'])}}"
                                   title="{{__('Cập nhật')}}"
                                   class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                    <i class="la la-edit"></i>
                                </a>

                                <button onclick="SmsCampaign.remove(this, '{{ $value['campaign_id']}}')"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{__('Hủy')}}"><i class="la la-trash"></i>
                                </button>
                            @endif
                        </td>
                    @else
                        <td></td>
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>

<div class="m-datatable m-datatable--default">
    <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
        <ul class="m-datatable__pager-nav" style="float: right">
            @if($page>1)
                <li><a onclick="firstAndLastPage(1)" title="First"
                       class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1"><i
                                class="la la-angle-double-left">
                        </i></a></li>
                <li><a onclick="firstAndLastPage({{$page-1}})" title="Previous"
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
                    <li><a class="m-datatable__pager-link m-datatable__pager-link--active" onclick="pageClick({{$i}})"
                           title="1">{{ $i }}</a></li>
                @else
                    <li><a class="m-datatable__pager-link" onclick="pageClick({{$i}})">{{ $i }}</a></li>
                @endif
            @endfor
            {{-- Next Page Link --}}
        <!--                --><?php //dd($page,$totalPage)?>
            @if($page<$totalPage-1)
                <li><a title="Next" class="m-datatable__pager-link" onclick="firstAndLastPage({{$page+1}})"
                       data-page=""><i class="la la-angle-right"></i></a></li>
                <li><a title="Last" onclick="firstAndLastPage({{(int)(count($LIST)/10)+1}})"
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
        <div class="m-datatable__pager-info" style="float: left">
            <span class="m-datatable__pager-detail">
                {{__('Hiển thị')}} {{($page-1)*10+1}} - {{($page-1)*10 + count($LIST)}} {{__('của')}} {{ count($data) }}
            </span>
        </div>
    </div>

</div>
