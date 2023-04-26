<div class="m-portlet__body" style="padding: 0px;padding-top:12px">
    <div class="table-responsive">
        <table class="table table-striped m-table ss--header-table">
            <thead>
            <tr class="ss--nowrap">
                <th class="ss--font-size-th ss--text-center">#</th>
                <th class="ss--font-size-th ss--text-center">{{__('Hành động')}}</th>
                <th class="ss--font-size-th  ss--text-center">{{__('Tên chính sách')}}</th>
                <th class="ss--font-size-th ss--text-center">{{__('Loại')}}</th>
                <th class="ss--font-size-th ss--text-center">{{__('Người cập nhật')}}</th>
                <th class="ss--font-size-th ss--text-center">{{__('Ngày cập nhật')}}</th>
                <th class="ss--font-size-th ss--text-center">{{__('Trạng Thái Chính Sách')}}</th>

            </tr>
            </thead>
            <tbody>
            @if(count($data) != 0)
                @foreach($data as $k => $v)
                    <tr class="ss--font-size-13 ss--nowrap">
                        <td class="ss--text-center">{{isset($param['page']) ? ($param['page']-1)*10 + $k+1 :$k+1}}</td>
                        @if( ($v['status'] == "new" ||  $v['status'] == "pending") && $v['money_commission'] != 1 )
                            <td class="ss--text-center">
                                <button
                                        type="button" onclick="commission.editCommission({{$v['referral_program_id']}})"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="Chỉnh sửa">
                                    <i class="la la-edit"></i>
                                </button>
                                <a href="javascript:void(0)"
                                   onclick="commission.delete('{{$v['referral_program_id']}}', '{{$v['referral_program_name']}}')"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="Xóa">
                                    <i class="la la-trash"></i>
                                </a>
                            </td>
                        @elseif( ($v['status'] == "new" ||  $v['status'] == "pending") && $v['money_commission'] == 1 )
                            <td class="ss--text-center">
                                <button
                                        type="button" onclick="commission.editCommission({{$v['referral_program_id']}})"
                                        class="m-portlet__nav-link btn m-btn m-btn--hover-accent m-btn--icon m-btn--icon-only m-btn--pill"
                                        title="Chỉnh sửa">
                                    <i class="la la-edit"></i>
                                </button>
                            </td>
                        @elseif($v['status'] != "new" && $v['status'] != "pending" && $v['status'] != "actived" && $v['money_commission'] != 1)
                            <td class="ss--text-center">
                                <a href="javascript:void(0)"
                                   onclick="commission.delete('{{$v['referral_program_id']}}', '{{$v['referral_program_name']}}')"
                                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                   title="Xóa">
                                    <i class="la la-trash"></i>
                                </a>
                            </td>
                        @elseif($v['status'] == "actived")
                            <td class="ss--text-center"></td>
                        @else
                            <td class="ss--text-center"></td>
                        @endif
                        <td class="ss--text-center">
                            <a href="{{route('referral.detailCommission',['id'=>$v['referral_program_id']])}}"
                               title="Xem chi tiết">
                                {{$v['referral_program_name']}}
                            </a>
                        </td>
                        <td class="ss--text-center">{{$v['type']}}</td>
                        <td class="ss--text-center">{{$v['staff_name']}}</td>
                        <td class="ss--text-center">
                            @if($v['updated_at']!=null)
                                <p>{{\Carbon\Carbon::parse($v['updated_at'])->format('d/m/Y')}}</p>
                                <p>{{\Carbon\Carbon::parse($v['updated_at'])->format('H:i')}}</p>
                            @else
                                <p></p>
                            @endif
                        </td>
                        <td class="ss--text-center">
                            @if($v['status'] == "actived")
                                <div class="status-active">
                                    <span>Đang hoạt động</span>
                                </div>
                            @elseif($v['status'] == "new")
                                <div class="status-new">
                                    <span>Nháp</span>
                                </div>
                            @elseif($v['status'] == "waiting")
                                <div class="status-waiting">
                                    <span>Đang chờ duyệt</span>
                                </div>
                            @elseif($v['status'] == "pending")
                                <div class="status-pending">
                                    <span>Tạm dừng</span>

                                </div>
                            @elseif($v['status'] == "approved")
                                <div class="status-approved">
                                    <span>Đã duyệt</span>
                                </div>
                            @elseif($v['status'] == "reject")
                                <div class="status-reject">
                                    <span>Đã từ chối</span>
                                </div>
                            @elseif($v['status'] == "finish")
                                <div class="status-reject">
                                    <span>Đã kết thúc</span>
                                </div>
                            @else
                                <div class="status-cancel">
                                    <span>Đã hủy</span>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @else
                <tr class="ss--font-size-13 ss--nowrap">
                    <td colspan="7">
                        <div class="not_find"
                             style="text-align: center;padding-top: 40px;padding-bottom: 40px;font-weight: bold;">
                            <i class="la la-search-plus"> </i>
                            <span>@lang('Chưa có dữ liệu')</span>
                        </div>
                    </td>
                </tr>
            @endif

            </tbody>
        </table>
    </div>
</div>

{{ $data->appends($param)->links('helpers.referral-paging-load') }}

