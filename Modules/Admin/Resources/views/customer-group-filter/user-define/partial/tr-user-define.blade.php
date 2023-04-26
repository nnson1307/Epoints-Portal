{{--<table class="table table-striped">--}}
    {{--<thead>--}}
    {{--<tr>--}}
        {{--<th style="width: 20px">--}}
            {{--<label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">--}}
                {{--<input type="checkbox" onclick="userGroupDefine.selectAll2(this)">--}}
                {{--<span></span>--}}
            {{--</label>--}}
        {{--</th>--}}
        {{--<th>@lang('admin::user-group-notification.create.ACCOUNT')</th>--}}
        {{--<th>@lang('admin::user-group-notification.create.FULL_NAME')</th>--}}
        {{--<th>@lang('admin::user-group-notification.create.STATUS')</th>--}}
        {{--<th></th>--}}
    {{--</tr>--}}
    {{--</thead>--}}
    {{--<tbody id="tbody-add-user">--}}
    {{--@if(isset($list))--}}
        {{--@foreach($list as $item)--}}
            {{--<tr>--}}
                {{--<td>--}}
                    {{--<label class="kt-checkbox kt-checkbox--solid kt-checkbox--brand">--}}
                        {{--<input class="check-box-choose-user" type="checkbox"--}}
                               {{--onclick="userGroupDefine.chooseUser2(this)">--}}
                        {{--<input type="hidden" value="{{$item['phone']}}" class="phone-2">--}}
                        {{--<span></span>--}}
                    {{--</label>--}}
                {{--</td>--}}
                {{--<td>{{$item['phone']}}</td>--}}
                {{--<td>{{$item['fullname']}}</td>--}}
                {{--<td>{{$item['is_activated']==1?'Hoạt động':'Không hoạt động'}}</td>--}}
                {{--<td>--}}
                    {{--<button type="button" onclick="" class="btn btn-secondary btn-icon ss-float-right ss-width-5rem">--}}
                        {{--@lang('admin::user-group-notification.create.REMOVE')--}}
                    {{--</button>--}}
                {{--</td>--}}
            {{--</tr>--}}
        {{--@endforeach--}}
    {{--@else--}}
    {{--@endif--}}

    {{--</tbody>--}}
{{--</table>--}}


<table class="table table-striped">
    <thead>
    <tr>
        <th>{{__('HỌ VÀ TÊN')}}</th>
        <th>{{__('SỐ ĐIỆN THOẠI')}}</th>
        <th>{{__('EMAIL')}}</th>
        <th>{{__('MÃ KHÁCH HÀNG')}}</th>
        <th>{{__('TRẠNG THÁI')}}</th>
        <th></th>
    </tr>
    </thead>
    <tbody id="tbody-add-user">
    @if(isset($list))
        @foreach($list as $item)
            <tr>
                <td>
                    <p title="{{ $item['full_name'] }}">
                        {{ ($item['full_name']) }}
                    </p>
                </td>
                <td>
                    <p title="{{ $item['phone1'] }}">
                        {{ ($item['phone1']) }}
                    </p>
                </td>
                <td>
                    <p title="{{ $item['email'] }}">
                        {{ ($item['email']) }}
                    </p>
                </td>
                <td>
                    <p title="{{ $item['customer_code'] }}">
                        {{ ($item['customer_code']) }}
                    </p>
                </td>
                <td>
                    {{$item['is_actived'] == 1 ? 'Hoạt động' : 'Không hoạt động'}}
                </td>
                <td>
                    <button type="button" onclick="userGroupDefine.removeRowTr(this,'{{$item["customer_id"]}}','{{$page}}')"
                            class="btn btn-secondary btn-icon ss-float-right ss-width-5rem btn-remove-phone-tr">
                        {{__('XÓA')}}
                    </button>
                </td>
            </tr>
        @endforeach
    @else
    @endif
    <tr>
        <td>
            <a class="a-add-user" href="javascript:void(0)" onclick="userGroupDefine.showModalAddUser()">
                {{__('THÊM KHÁCH HÀNG')}}
            </a>
        </td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>
    </tbody>
</table>
@if(isset($list))
    {{$list->links('admin::customer-group-filter.user-define.helper.paging-ajax-3')}}
@endif
