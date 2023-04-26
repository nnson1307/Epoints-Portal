<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">#</th>
            <th class="tr_thead_list">{{__('Tên chi nhánh')}}</th>
            <th class="tr_thead_list">{{__('Mã đại diện')}}</th>
            <th class="tr_thead_list">{{__('Số điện thoại')}}</th>
            <th class="tr_thead_list">{{__('Địa chỉ')}}</th>
            <th class="tr_thead_list">{{__('Trạng thái')}}</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        @if(isset($page))
                            {{ ($page-1)*10 + $key+1}}
                        @else
                            {{$key+1}}
                        @endif
                    </td>
                    <td>{{$item['branch_name']}}</td>
                    <td>{{$item['representative_code']}}</td>
                    <td>{{$item['phone']}}</td>
                    <td>
                        @if($item['address'] != null)
                            {{$item['address'].', '.$item['district_type'].' '.$item['district_name'].', '.
                                    $item['province_type'].' '.$item['province_name']}}
                        @endif
                    </td>
                    <td>
                        @if(in_array('admin.branch.change-status',session('routeList')))
                            @if ($item['is_actived'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="branch.changeStatus(this, '{!! $item['branch_id'] !!}', 'publish')"
                                           checked class="manager-btn" name="">
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           onclick="branch.changeStatus(this, '{!! $item['branch_id'] !!}', 'unPublish')"
                                           class="manager-btn">
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @else
                            @if ($item['is_actived'])
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           checked class="manager-btn" name="" disabled>
                                    <span></span>
                                </label>
                            </span>
                            @else
                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox"
                                           class="manager-btn" disabled>
                                    <span></span>
                                </label>
                            </span>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if(in_array('admin.branch.edit',session('routeList')))
                            <a href="{{route('admin.branch.edit',$item['branch_id'])}}"
                               class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                        @if(in_array('admin.branch.delete',session('routeList')))
                            <button onclick="branch.remove(this, {{$item['branch_id']}})"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="Delete">
                                <i class="la la-trash"></i>
                            </button>
                        @endif
                        @if(in_array('estimate.quota.quota-estimate',session('routeList')))
                            <a href="{{route('estimate.quota.quota-estimate',$item['branch_id'])}}"
                               class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill">
                                <i class="la la-gear"></i>
                            </a>
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
