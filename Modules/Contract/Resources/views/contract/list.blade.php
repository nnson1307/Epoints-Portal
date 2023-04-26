<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            @if(json_decode(Cookie::get('arrColumn')) != null)
                @foreach(json_decode(Cookie::get('arrColumn')) as $key => $value)
                    <?php  $value = (array)$value; ?>
                        @switch($value['key'])
                            @case('stt')
                            <th class="tr_thead_list">{{$value['value']}}</th>
                            @break;
                        @endswitch
                @endforeach
            @endif
            <th class="tr_thead_list">@lang('Hành động')</th>
            <th class="tr_thead_list">@lang('Mã hợp đồng')</th>
            <th class="tr_thead_list">@lang('Tên hợp đồng')</th>
            @if(json_decode(Cookie::get('arrColumn')) != null)
                @foreach(json_decode(Cookie::get('arrColumn')) as $key => $value)
                    <?php  $value = (array)$value; ?>
                    @if($value['key'] != 'stt')
                    <th class="tr_thead_list">{{$value['value']}}</th>
                    @endif
                @endforeach
            @endif
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    <td hidden>
                        <input type="hidden" name="is_reason" value="{{$item['is_reason']}}">
                    </td>
                    @if(json_decode(Cookie::get('arrColumn')) != null)
                        @foreach(json_decode(Cookie::get('arrColumn')) as $key1 => $value1)
                            <?php $value1 = (array)$value1 ?>
                            @switch($value1['key'])
                                @case('stt')
                                @if(isset($page))
                                    <td>{{ ($page-1)*10 + $key+1}}</td>
                                @else
                                    <td>{{$key+1}}</td>
                                @endif
                                @break;
                            @endswitch
                        @endforeach
                    @endif
                    <td>
                        @if(in_array('contract.contract.edit',session('routeList')))
                            @if($item['is_edit_contract'] == 1 && $item['is_browse'] == 0)
                                <a href="{{route("contract.contract.edit",[ 'id' => $item['contract_id']])}}"
                                   class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="{{__('Sửa')}}">
                                    <i class="la la-edit"></i>
                                </a>
                            @endif
                        @endif
                        @if(in_array('contract.contract.destroy',session('routeList')))
                            @if($item['is_deleted_contract'] == 1 && $item['is_browse'] == 0)
                                <button onclick="listContract.clickRemove(this,{{$item['contract_id']}})"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="{{__('Xoá')}}">
                                    <i class="la la-trash"></i>
                                </button>
                            @endif
                        @endif
                    </td>
                    <td>
                        @if(in_array('contract.contract.show', session()->get('routeList')))
                            <a href="{{route("contract.contract.show",[ 'id' => $item['contract_id']])}}">
                                {{$item['contract_code']}}
                            </a>
                        @else
                            {{$item['contract_code']}}
                        @endif
                    </td>
                    <td>
                        @if(in_array('contract.contract.show', session()->get('routeList')))
                            <a href="{{route("contract.contract.show",[ 'id' => $item['contract_id']])}}">
                                {{$item['contract_name']}}
                            </a>
                        @else
                            {{$item['contract_name']}}
                        @endif
                    </td>
                    @if(json_decode(Cookie::get('arrColumn')) != null)
                        @foreach(json_decode(Cookie::get('arrColumn')) as $key1 => $value1)
                            <?php $value1 = (array)$value1 ?>
                            @switch($value1['key'])
                                @case('contract_code')
                                    <td>
                                        <a href="{{route("contract.contract.show",[ 'id' => $item['contract_id']])}}">
                                            {{$item['contract_code']}}
                                        </a>
                                    </td>
                                        @break;
                                @case('contract_no')
                                    <td>{{$item['contract_no']}}</td>
                                        @break;
                                @case('contract_name')
                                    <td>{{$item['contract_name']}}</td>
                                        @break;
                                @case('content')
                                    <td>{{$item['content']}}</td>
                                        @break;
                                @case('partner_name')
                                    <td>{{$item['partner_name']}}</td>
                                        @break;
                                @case('customer_group_id')
                                    <td>
                                        @switch($item['partner_object_type'])
                                            @case('personal')
                                                @lang('Cá nhân')
                                            @break
                                            @case('business')
                                                @lang('Doanh nghiệp')
                                            @break
                                            @case('supplier')
                                                @lang('Nhà cung cấp')
                                            @break
                                        @endswitch
                                    </td>
                                        @break;
                                @case('address')
                                    <td>{{$item['address']}}</td>
                                        @break;
                                @case('representative')
                                    <td>{{$item['representative']}}</td>
                                        @break;
                                @case('hotline')
                                    <td>{{$item['hotline']}}</td>
                                        @break;
                                @case('staff_title')
                                    <td>{{$item['staff_title_name']}}</td>
                                        @break;
                                @case('is_renew')
                                    <td>
                                        @switch($item['is_renew'])
                                            @case('1')
                                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                        <input type="checkbox" checked="" class="manager-btn" name="">
                                                        <span></span>
                                                    </label>
                                                </span>
                                                @break;
                                            @case('0')
                                                <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                                        <input type="checkbox" class="manager-btn" name="">
                                                        <span></span>
                                                    </label>
                                                </span>
                                            @break;
                                        @endswitch
                                    </td>
                                        @break;
                                @case('phone')
                                    <td>{{$item['phone']}}</td>
                                        @break;
                                @case('email')
                                    <td>{{$item['email']}}</td>
                                        @break;
                                @case('goods')
                                    <td>{{$item['list_object_name']}}</td>
                                        @break;
                                @case('contract_category_id')
                                    <td>{{$item['contract_category_name']}}</td>
                                        @break;
                                @case('effective_date')
                                    <td>{{$item['effective_date'] != "" ? date("d/m/Y",strtotime($item['effective_date'])) : ""}}</td>
                                        @break;
                                @case('expired_date')
                                    <td>{{$item['expired_date'] != "" ? date("d/m/Y",strtotime($item['expired_date'])) : ""}}</td>
                                        @break;
                                @case('sign_date')
                                    <td>{{$item['sign_date'] != "" ? date("d/m/Y",strtotime($item['sign_date'])) : ""}}</td>
                                        @break;
                                @case('status_code')
                                    <td>{{$item['status_name']}}</td>
                                        @break;
                                @case('total_amount')
                                    <td>{{$item['total_amount']}}</td>
                                        @break;
                                @case('tax')
                                    <td>{{$item['tax']}}</td>
                                        @break;
                                @case('discount')
                                    <td>{{$item['discount']}}</td>
                                        @break;
                                @case('last_total_amount')
                                    <td>{{number_format($item['last_total_amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                                        @break;
                                @case('performer_by')
                                    <td>{{$item['staff_performer_name']}}</td>
                                        @break;
                                @case('department')
                                    <td>{{$item['department_name']}}</td>
                                        @break;
{{--                                @case('sign_by'):--}}
{{--                                    <td>{{$item['staff_performer_name']}}</td>--}}
{{--                                        @break;--}}
                                @case('created_by')
                                    <td>{{$item['staff_created_by_name']}}</td>
                                        @break;
                                @case('updated_by')
                                    <td>{{$item['staff_updated_by_name']}}</td>
                                        @break;
{{--                                @case('approve_by'):--}}
{{--                                    <td>{{$item['staff_performer_name']}}</td>--}}
{{--                                        @break;--}}
{{--                                @case('follow_by'):--}}
{{--                                    <td>{{$item['staff_performer_name']}}</td>--}}
{{--                                        @break;--}}
                                @case('warranty_start_date')
                                    <td>{{$item['warranty_start_date'] != "" ? date("d/m/Y",strtotime($item['warranty_start_date'])) : ""}}</td>
                                    @break;
                                @case('warranty_end_date')
                                    <td>{{$item['warranty_end_date'] != "" ? date("d/m/Y",strtotime($item['warranty_end_date'])) : ""}}</td>
                                    @break;
                                @case('reason')
                                    <td>{{$item['reason']}}</td>
                                    @break;
                                @case('contract_file')
                                <td>
                                    <a href="{{$item['list_link']}}"
                                       class="ss--text-black">
                                        {{$item['list_file_name']}}
                                    </a><br>
                                </td>
                                    @break;
                                @case('note')
                                    <td>{{$item['note']}}</td>
                                    @break;
                            @endswitch
                        @endforeach
                    @endif
                </tr>
            @endforeach
        @endif
        </tbody>

    </table>
</div>
{{ $LIST->links('helpers.paging') }}
