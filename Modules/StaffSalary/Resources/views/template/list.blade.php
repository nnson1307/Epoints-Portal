<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list"></th>
            <th class="tr_thead_list">@lang('TÊN MẪU ÁP DỤNG')</th>
            <th class="tr_thead_list">@lang('LOẠI LƯƠNG')</th>
            <th class="tr_thead_list">@lang('KỲ HẠN TRẢ LƯƠNG')</th>
            <th class="tr_thead_list">@lang('MỨC LƯƠNG')</th>
            <th class="tr_thead_list">@lang('HÌNH THỨC TRẢ LƯƠNG')</th>
            <th class="tr_thead_list">@lang('NGÀY TẠO')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $k => $item)
                <tr>
                    <td style="vertical-align: middle;">
                        @if(in_array('staff-salary.template.edit', session('routeList')))
                            <a href="{{route('staff-salary.template.edit', $item['staff_salary_template_id'])}}"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chỉnh sửa')">
                                <i class="la la-edit"></i>
                            </a>
                        @endif

                        @if(in_array('staff-salary.template.destroy', session('routeList')))
                            <a href="javascript:void(0)"
                               onclick="listTemplate.remove('{{$item['staff_salary_template_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Xóa')">
                                <i class="la la-trash"></i>
                            </a>
                        @endif
                    </td>
                    <td style="vertical-align: middle;">{{$item['staff_salary_template_name']}}</td>
                    <td style="vertical-align: middle;">{{__($item['staff_salary_type_name'])}}</td>
                    <td style="vertical-align: middle;">{{__($item['staff_salary_pay_period_name'])}}</td>
                    <td style="vertical-align: middle;">
                        {{number_format($item['salary_default'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                        {{$item['staff_salary_unit_name']}} / {{__($item['staff_salary_type_name'])}}
                    </td>
                    <td style="vertical-align: middle;">
                        @switch($item['payment_type'])
                            @case('cash')
                            @lang('Tiền mặt')
                            @break
                            @case('transfer')
                            @lang('Chuyển khoản')
                            @break
                        @endswitch
                    </td>
                    <td style="vertical-align: middle;">{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td style="vertical-align: middle;">
                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                    <label style="margin: 0 0 0 10px; padding-top: 4px">
                                        <input type="checkbox"
                                               onclick="listTemplate.changeStatus(this, '{{$item['staff_salary_template_id']}}')"
                                               {{$item['is_actived'] == 1 ? 'checked': ''}} class="manager-btn" name="">
                                        <span></span>
                                    </label>
                        </span>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
