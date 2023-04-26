<div class="table-responsive">
    <table class="table m-table--head-bg-default">
        <thead class="bg">
        <tr>
            <th class="tr_thead_list">@lang('HÌNH ẢNH')</th>
            <th class="tr_thead_list" style="width: 150px;">@lang('TÊN KHÁCH HÀNG')</th>
            <th class="tr_thead_list">@lang('ĐỊA CHỈ')</th>
            {{-- <th class="tr_thead_list">@lang('LOẠI KHÁCH HÀNG')</th> --}}
            <th class="tr_thead_list">@lang('HÀNH TRÌNH HIỆN TẠI')</th>
            {{-- <th class="tr_thead_list">@lang('NGUỒN KHÁCH HÀNG')</th> --}}
            <th class="tr_thead_list">@lang('NGƯỜI SỞ HỮU')</th>
            <th class="tr_thead_list">@lang('NGƯỜI ĐƯỢC PHÂN BỔ')</th>
            <th class="tr_thead_list">@lang('NGÀY TẠO')</th>
            <th class="tr_thead_list">@lang('NGÀY HẾT HẠN')</th>
            <th class="tr_thead_list">@lang('TÌNH TRẠNG')</th>
            <th class="tr_thead_list">@lang('LOẠI CHUYẾN ĐỔI')</th>
            {{-- <th class="tr_thead_list">@lang('NỘI DUNG CHĂM SÓC')</th> --}}
            <th style="width: 100px;"></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($LIST) && count($LIST) > 0)
            @foreach ($LIST as $key => $item)
                <tr>
                    <td>
                        <img src="{{$item['avatar']}}"
                             onerror="this.onerror=null;this.src='{{asset('static/backend/images/default-placeholder.png')}}';"
                             class="m--img-rounded m--marginless" alt="photo" width="50px" height="50px">
                    </td>
                    <td style="width: 150px;">
                        {{-- <a href="javascript:void(0)" onclick="listLead.detail({{$item['customer_lead_id']}})"> --}}
                        <a href="{{route('customer-lead.detail', $item['customer_lead_id'])}}" target="_blank">
                            {{$item['full_name']}}
                        </a><br>
                        @if($item['customer_type'] == 'personal')
                            @lang('Cá nhân')
                        @elseif($item['customer_type'] == 'business')
                            @lang('Doanh nghiệp')
                        @endif
                        <br>
                        {{$item['customer_source_name']}}
                        <br>
                        {{$item['phone']}}
                    </td>
                    <td>{{$item['address']}}</td>

                    <td>{{$item['pipeline_name']}} - {{$item['journey_name']}}</td>

                    <td>{{$item['owner_name']}}</td>
                    <td>{{$item['sale_name']}}</td>
                    <td>{{\Carbon\Carbon::parse($item['created_at'])->format('d/m/Y')}}</td>
                    <td>
                        @if($item['date_revoke'] != null)
                            {{\Carbon\Carbon::parse($item['date_revoke'])->format('d/m/Y')}}
                        @endif
                    </td>
                    <td>{{$item['is_convert'] == 1 ? __('Chuyển đổi thành công') : __('Chưa chuyển đổi')}}</td>
                    <td>
                        @if($item['convert_object_type'] == 'deal')
                            {{__('Cơ hội bán bàng')}}
                        @elseif($item['convert_object_type'] == 'customer')
                            {{__('Khách hàng thực thụ')}}
                        @endif
                    </td>
                    {{-- <td>{!! str_limit($item['content_care'],100,'...') !!}</td> --}}
                    <td nowrap="" style="vertical-align: middle; text-align:right;">
                        <span class="dropdown">
                            <a href="javascript:void(0)" class="btn m-btn btn-success m-btn--hover-success m-btn--icon m-btn--icon-only m-btn--pill" data-toggle="dropdown" aria-expanded="false">
                              <i class="la la-ellipsis-h"></i>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-32px, 27px, 0px);">
                                @if($item['is_convert'] == 0)
                            @if(in_array('customer-lead.popup-list-staff', session('routeList')))
                                @if ($item['sale_id'] == null)
                                    <a href="javascript:void(0)"
                                    onclick="listLead.popupListStaff('{{$item['customer_lead_id']}}')"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="@lang('Phân công')">
                                        <i class="la la-angle-up"></i>
                                    </a>
                                @else
                                    <a href="javascript:void(0)"
                                    onclick="listLead.revokeOne('{{$item['customer_lead_id']}}')"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="@lang('Thu hồi')">
                                        <i class="fas fa-redo"></i>
                                    </a>
                                @endif
                            @else
                                @if ($item['created_by'] == \Illuminate\Support\Facades\Auth::id())
                                    @if ($item['sale_id'] == null)
                                        <a href="javascript:void(0)"
                                        onclick="listLead.popupListStaff('{{$item['customer_lead_id']}}')"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="@lang('Phân công')">
                                            <i class="la la-angle-up"></i>
                                        </a>
                                    @endif
                                @endif
                            @endif
                            {{-- END PHÂN BỔ + THU HỒI --}}
                            @if(in_array('customer-lead.customer-care', session('routeList')))
                                <a href="javascript:void(0)"
                                onclick="listLead.popupCustomerCare('{{$item['customer_lead_id']}}')"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                title="@lang('Chăm sóc khách hàng')">
                                    {{-- @if($item['total_work'] != 0)
                                        <span class="badge badge-fix badge-light float-right color-red-fix">{{$item['total_work']}}</span>
                                    @endif --}}
                                    <i class="la  la-gratipay"></i>
                                </a>
                            @endif
                            
                            @if(in_array('customer-lead.edit', session('routeList')))
                                {{-- <a href="javascript:void(0)" onclick="edit.popupEdit('{{$item['customer_lead_id']}}', false)" --}}
                                <a href="{{route('customer-lead.edit-lead', $item['customer_lead_id'])}}" target="_blank"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                title="@lang('Chỉnh sửa')">
                                    <i class="la la-edit"></i>
                                </a>
                            @endif
                            @if(in_array('customer-lead.destroy', session('routeList')))
                                <a href="javascript:void(0)"
                                onclick="listLead.remove('{{$item['customer_lead_id']}}', false)"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                title="@lang('Xóa')">
                                    <i class="la la-trash"></i>
                                </a>
                            @endif

                                @if(in_array('customer-lead.modal-call', session('routeList')))
                                    <a href="javascript:void(0)"
                                    onclick="listLead.modalCall('{{$item['customer_lead_id']}}')"
                                    class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                    title="@lang('Gọi')">
                                        <i class="la la-phone"></i>
                                    </a>
                                @endif

                            @endif
                            @if(in_array('customer-lead.customer-log', session('routeList')))
                                <a href="{{route('customer-lead.customer-log') . '?id=' .$item['customer_lead_id']}}"
                                target="_blank"
                                class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                title="@lang('Lịch sử thay đổi')">
                                    <i class="fa fa-history"></i>
                                </a>
                            @endif
                            </div>
                        </span>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>
{{ $LIST->links('helpers.paging') }}
