<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('#')</th>
            <th class="tr_thead_list">@lang('TÊN KHÁCH HÀNG')</th>
            <th class="tr_thead_list">@lang('THÔNG TIN HIỆN TẠI')</th>
            <th class="tr_thead_list">@lang('THÔNG TIN CẦN THAY ĐỔI')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST))
            @foreach ($LIST as $key => $item)
                <tr>
                    @if(isset($page))
                        <td>{{ ($page-1)*10 + $key+1}}</td>
                    @else
                        <td>{{$key+1}}</td>
                    @endif
                    <td>{{$item['full_name']}}</td>
                    <td>
                        @lang('Email'): <strong>{{$item['email']}}</strong> <br>
                        @lang('Sđt'): <strong>{{$item['phone']}}</strong> <br>
                        @lang('Địa chỉ'): <strong>{{$item['address']. ', '. $item['district_name']. ', '. $item['province_name']}}</strong>
                    </td>
                    <td>
                        @lang('Email'): <strong>{{$item['email_temp']}}</strong> <br>
                        @lang('Sđt'): <strong>{{$item['phone_temp']}}</strong> <br>
                        @lang('Địa chỉ'): <strong>{{$item['address_temp']. ', '. $item['district_name_temp']. ', '. $item['province_name_temp']}}</strong>
                    </td>
                    <td>
                        @switch($item['status'])
                            @case('new')
                                @lang('Mới')
                            @break
                            @case('confirm')
                                @lang('Đã xác nhận')
                            @break
                            @case('cancel')
                                @lang('Huỷ')
                            @break
                        @endswitch
                    </td>
                    <td>
                        @if($item['status'] == 'new' && in_array('customer-info.confirm',session('routeList')))
                            <a href="{{route('customer-info.confirm', $item['customer_info_temp_id'])}}"
                                    class="test m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="{{__('Xác nhận')}}">
                                <i class="la la-check-square"></i>
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
