<div class="table-responsive">
    <table class="table table-striped m-table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            {{--            <th class="tr_thead_list">@lang('HÌNH ẢNH')</th>--}}
            <th class="tr_thead_list">@lang('TÊN DEAL')</th>
            <th class="tr_thead_list">@lang('GIÁ TRỊ DEAL')</th>
            <th class="tr_thead_list">@lang('HÀNH TRÌNH')</th>
            <th class="tr_thead_list">@lang('MÃ DEAL')</th>
            <th class="tr_thead_list">@lang('NGÀY DỰ KIẾN')</th>
            <th class="tr_thead_list">@lang('NGƯỜI SỞ HỮU')</th>
            <th class="tr_thead_list">@lang('NGƯỜI ĐƯỢC PHÂN BỔ')</th>
            <th class="tr_thead_list">@lang('PIPELINE')</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        <a href="javascript:void(0)" onclick="listDeal.detail({{$item['deal_id']}})">
                            {{$item['deal_name']}}
                        </a>
                    </td>
                    <td>
                        {{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                    </td>
                    <td>{{$item['journey_name']}}</td>
                    <td>{{$item['deal_code']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['closing_date'])->format('d/m/Y')}}</td>
                    <td>{{$item['owner_name']}}</td>
                    <td>{{$item['sale_name']}}</td>
                    <td>{{$item['pipeline_name']}}</td>
                    <td>
                        @if(in_array('customer-lead.customer-deal.popup-list-staff', session('routeList')))
                            {{--                        @if(in_array('customer-lead.assign', session('routeList')))--}}
                            @if ($item['sale_id'] == null)
                                <a href="javascript:void(0)"
                                   onclick="listDeal.popupListStaff('{{$item['deal_id']}}')"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="@lang('Phân công')">
                                    <i class="la la-angle-up"></i>
                                </a>
                            @else
                                <a href="javascript:void(0)"
                                   onclick="listDeal.revokeOne('{{$item['deal_id']}}')"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="@lang('Thu hồi')">
                                    <i class="fas fa-redo"></i>
                                </a>
                            @endif
                        @else
                            @if ($item['created_by'] == \Illuminate\Support\Facades\Auth::id())
                                @if ($item['sale_id'] == null)
                                    <a href="javascript:void(0)"
                                       onclick="listDeal.popupListStaff('{{$item['deal_id']}}')"
                                       class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                       title="@lang('Phân công')">
                                        <i class="la la-angle-up"></i>
                                    </a>
                                @endif
                            @endif
                        @endif
                        <a href="javascript:void(0)"
                           onclick="listDeal.popupDealCare('{{$item['deal_id']}}')"
                           class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                           title="@lang('Chăm sóc khách hàng')">
                            @if($item['total_work'] != 0)
                                <span class="badge badge-fix badge-light float-right color-red-fix">{{$item['total_work']}}</span>
                            @endif
                            <i class="la  la-gratipay"></i>
                        </a>
                        @if(in_array('customer-lead.customer-deal.edit', session('routeList')))
                            <a href="javascript:void(0)" onclick="edit.popupEdit('{{$item['deal_id']}}', false)"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Chỉnh sửa')">
                                <i class="la la-edit"></i>
                            </a>
                        @endif
                        @if(in_array('customer-lead.customer-deal.destroy', session('routeList')))
                            <a href="javascript:void(0)"
                               onclick="listDeal.remove('{{$item['deal_id']}}', false)"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Xóa')">
                                <i class="la la-trash"></i>
                            </a>
                        @endif

                        @if(in_array('customer-deal.modal-call', session('routeList')))
                            <a href="javascript:void(0)"
                               onclick="listDeal.modalCall('{{$item['deal_id']}}')"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Gọi')">
                                <i class="la la-phone"></i>
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
