<div class="table-responsive">
    <table class="table table-striped m-table ss--header-table ss--nowrap" id="tb-service-card">
        <thead>
        <tr>
            <th class="ss--font-size-th">#</th>
            <th class="ss--font-size-th">{{__('TÊN THẺ')}}</th>
            <th class="ss--font-size-th" style="text-align: right">{{__('GIÁ BÁN')}}</th>
            @if(Auth::user()->is_admin==1)
                <th class="ss--font-size-th" style="text-align: center;white-space:nowrap">{{__('SỐ LƯỢNG')}}</th>
            @endif
            <th class="ss--font-size-th" style="text-align: center;white-space:nowrap">{{__('TRẠNG THÁI')}}</th>
            @foreach($BRANCH as $key=>$value)
                <th class="ss--font-size-th" style="text-align: center">{{$value['branch_name']}}</th>
            @endforeach
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST ))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td class="ss--font-size-13">{{$key+1}}</td>
                    <td class="ss--font-size-13">
                        <a title="Chi tiết" class="test ss--text-black"
                           href='{{route("admin.service-card.detail",$item['id'])}}'>
                            {{$item['name']}}
                        </a>
                    </td>
                    <td class="ss--font-size-13" style="text-align: right">
                        {{number_format($item['price'],isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    @if(Auth::user()->is_admin==1)
                        <td class="ss--font-size-13" style="text-align:center;width:100px;max-width: 100px">
                            {{number_format($item['quantity'],0,"",",")}}
                        </td>
                    @endif
                    <td class="ss--font-size-13" style="width: 110px;text-align: center">
                        @if(in_array('admin.service-card.change-status',session('routeList')))
                            @if ($item['is_actived']==1)
                                {{--<button class="m-badge  m-badge--success m-badge--wide">{{__('Hoạt động')}}</button>--}}
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 0px; padding-top: 0px">
                                    <input type="checkbox"
                                           onclick="ServiceCard.changeStatus(this, '{!! $item['id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                {{--<button class="m-badge  m-badge--danger m-badge--wide">Tạm ngưng</button>--}}
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 0px; padding-top: 0px">
                                    <input type="checkbox"
                                           onclick="ServiceCard.changeStatus(this, '{!! $item['id'] !!}', 'unPublish')"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @else
                            @if ($item['is_actived']==1)
                                {{--<button class="m-badge  m-badge--success m-badge--wide">{{__('Hoạt động')}}</button>--}}
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 0px; padding-top: 0px">
                                    <input type="checkbox"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                {{--<button class="m-badge  m-badge--danger m-badge--wide">Tạm ngưng</button>--}}
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 0px; padding-top: 0px">
                                    <input type="checkbox"
                                           class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @endif
                    </td>
                    @foreach($item['branch'] as $idBranch=>$valueBranch)
                        <td class="ss--font-size-13" style="text-align:center;width:200px;max-width: 300px">
                            {{$valueBranch}}
                        </td>
                    @endforeach

                    <td class="pull-right ss--font-size-13">
                        @if(in_array('admin.service-card.edit',session('routeList')))
                            <a href="{{route('admin.service-card.edit',$item['id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                               title="{{__('Cập nhật')}}">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                        @if(in_array('admin.service-card.delete',session('routeList')))
                            <button onclick="ServiceCard.remove(this, {{$item['id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="Xóa">
                                <i class="la la-trash"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

<div class="m-datatable m-datatable--default">
    <div class="m-datatable__pager m-datatable--paging-loaded clearfix">
        <ul class="m-datatable__pager-nav" style="float: right">
            @if(count($data)>10)
                @if($page>1)
                    <li><a onclick="firstOrLastPageSearch(1)" title="First"
                           class="m-datatable__pager-link m-datatable__pager-link--first" data-page="1"><i
                                    class="la la-angle-double-left">
                            </i></a></li>
                    <li><a onclick="firstOrLastPageSearch({{$page-1}})" title="Previous"
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
                        <li><a class="m-datatable__pager-link m-datatable__pager-link--active" onclick="pageClick(this)"
                               title="1">{{ $i }}</a></li>
                    @else
                        <li><a class="m-datatable__pager-link" onclick="pageSearchClick(this)">{{ $i }}</a></li>
                    @endif
                @endfor
                {{-- Next Page Link --}}
                @if($page<(int)(count($LIST)/10)+1)
                    <li><a title="Next" class="m-datatable__pager-link" onclick="firstOrLastPageSearch({{$page+1}})"
                           data-page=""><i class="la la-angle-right"></i></a></li>
                    <li><a title="Last" onclick="firstOrLastPageSearch({{$totalPage-1}})"
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
                 @if(count($LIST)==0)
                    {{__('Hiển thị 0 - 0  của 0')}}
                @else
                    {{__('Hiển thị')}} {{($page-1)*10+1}} - {{($page-1)*10 + count($LIST)}} {{__('của')}} {{ count($data) }}
                @endif
            </span>
        </div>
    </div>
</div>

