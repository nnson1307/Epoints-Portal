<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('TÊN GÓI BẢO HÀNH')</th>
            <th class="tr_thead_list">@lang('THỜI HẠN BẢO HÀNH')</th>
            <th class="tr_thead_list">@lang('GIÁ TRỊ BẢO HÀNH')</th>
            <th class="tr_thead_list">@lang('SỐ LẦN ĐƯỢC BẢO HÀNH')</th>
            <th class="tr_thead_list">@lang('SỐ TIỀN TỐI ĐA ĐƯỢC ĐƯỢC BẢO HÀNH')</th>
            <th class="tr_thead_list">@lang('TRẠNG THÁI')</th>
            <th class="tr_thead_list">@lang('NGÀY TẠO')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        <a href="{{route('warranty-package.show', $item['warranty_packed_id'])}}">
                            {{$item['packed_name']}}
                        </a>
                    </td>
                    <td>
                        @switch($item['time_type'])
                            @case('day') {{$item['time']}} {{__('Ngày')}} @break
                            @case('week') {{$item['time'] / 7}} {{__('Tuần')}} @break
                            @case('month') {{$item['time'] / 30}} {{__('Tháng')}} @break
                            @case('year') {{$item['time'] / 365}} {{__('Năm')}} @break
                            @case('infinitive') {{__('Vô hạn')}} @break
                        @endswitch
                    </td>
                    <td>{{number_format($item['percent'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} %</td>
                    <td>{{$item['quota'] == 0 ? __('Vô hạn') : $item['quota']}}</td>
                    <td>{{number_format($item['required_price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                    <td class="text_middle">
{{--                        @if(in_array('warranty-package.update-status',session('routeList')))--}}
                        @if ($item['is_actived'])
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" class="manager-btn" checked
                                           onclick="listPackage.updateStatus('{{$item['warranty_packed_id']}}', 0)">
                                    <span></span>
                                </label>
                            </span>
                        @else
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                <label style="margin: 0 0 0 10px; padding-top: 4px">
                                    <input type="checkbox" class="manager-btn"
                                           onclick="listPackage.updateStatus('{{$item['warranty_packed_id']}}', 1)">
                                    <span></span>
                                </label>
                            </span>
                        @endif
{{--                        @endif--}}
                    </td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y H:i')}}</td>
                    <td>
                        <a href="{{route('warranty-package.edit', $item['warranty_packed_id'])}}"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Chỉnh sửa')">
                            <i class="la la-edit"></i>
                        </a>
{{--                        @if(in_array('warranty-package.delete', session('routeList')))--}}
                            <a href="javascript:void(0)"
                               onclick="listPackage.delete('{{$item['warranty_packed_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Xóa')">
                                <i class="la la-trash"></i>
                            </a>
{{--                        @endif--}}
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
