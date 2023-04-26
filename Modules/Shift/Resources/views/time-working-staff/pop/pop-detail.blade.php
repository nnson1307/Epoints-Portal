<div class="modal fade" id="modal-detail" role="dialog" style="z-index: 100;">
    <div class="modal-dialog modal-dialog-centered modal-lg-email-campaign">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title color_title" id="exampleModalLabel">
                    <i class="la la-eye"></i> {{__('CHI TIẾT NGÀY LÀM VIỆC')}}
                </h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>@lang('Tên nhân viên'):</label>
                            <input class="form-control" type="text" value="{{$item['full_name']}}" disabled>
                        </div>
                        <div class="form-group">
                            <label>@lang('Tên ca làm'):</label>
                            <input class="form-control" type="text" value="{{$item['shift_name']}}" disabled>
                        </div>
                        <div class="form-group">
                            <label>@lang('Giờ làm'):</label>
                            <input class="form-control" type="text" value="{{$item['time_work']}}" disabled>
                        </div>
                        <div class="form-group">
                            <label>@lang('Hệ số công'):</label>
                            <input class="form-control" type="text"
                                   value="{{floatval($item['timekeeping_coefficient'])}}" disabled>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>@lang('Ngày làm'):</label>
                            <input class="form-control" type="text"
                                   value="{{\Carbon\Carbon::parse($item['working_day'])->format('d/m/Y')}}" disabled>
                        </div>
                        <div class="form-group">
                            <label>@lang('Vị trí làm'):</label>
                            <input class="form-control" type="text" value="{{$item['branch_name']}}" disabled>
                        </div>
                        <div class="form-group">
                            <label>@lang('Số giờ làm tối thiểu tính đủ công'):</label>
                            <input class="form-control" type="text" value="{{$item['min_time_work']}}" disabled>
                        </div>
                        <div class="form-group">
                            <label>@lang('Tăng ca'):</label>
                            <label class="m-checkbox m-checkbox--bold m-checkbox--state-success m--padding-top-5">
                                <input type="checkbox" {{$item['is_ot'] == 1 ? 'checked': ''}} disabled>
                                <span></span>
                            </label>
                        </div>
                        <div class="form-group">
                            <label>@lang('Tính tăng ca theo'):</label>
                            @switch($item['overtime_type'])
                                @case('S')
                                <input class="form-control" type="text" value="@lang('Ca')" disabled>
                                @break
                                @case('H')
                                <input class="form-control" type="text" value="@lang('Giờ')" disabled>
                                @break
                                @default
                                <input class="form-control" type="text" disabled>
                                @break
                            @endswitch
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <div class="m-portlet__body">
                        <ul class="nav nav-pills" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active show" data-toggle="tab" href="#m_tabs_3_1">
                                    @lang('Danh sách công việc')
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#m_tabs_3_2">
                                    @lang('Thưởng')
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#m_tabs_3_3">
                                    @lang('Phạt vi phạm')
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link" data-toggle="tab" href="#m_tabs_3_4">
                                    @lang('Lịch sử chấm công')
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="m_tabs_3_1" role="tabpanel">
                                <table class="table table-striped m-table m-table--head-bg-default">
                                    <thead class="bg">
                                    <tr>
                                        <th class="tr_thead_list">#</th>
                                        <th class="tr_thead_list">@lang('Tiêu đề')</th>
                                        <th class="tr_thead_list">@lang('Loại công việc')</th>
                                        <th class="tr_thead_list">@lang('Trạng thái')</th>
                                        <th class="tr_thead_list">@lang('Người thực hiện')</th>
                                        <th class="tr_thead_list">@lang('Ngày hết hạn')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($lst_work as $value => $itemWork)
                                        <tr>
                                            <td></td>
                                            <td>
                                                <a href="/manager-work/detail/{{ $itemWork['manage_work_id'] }}"
                                                   target="_blank">
                                                    {{ $itemWork['manage_work_title'] }}
                                                </a>
                                            </td>
                                            <td>
                                                {{ $itemWork['manage_type_work_name'] }}
                                            </td>
                                            <td>
                                                <p class="mb-0 ml-0 status_work_priority "
                                                   style="background-color:{{ $itemWork['manage_color_code'] }}">{{ $itemWork['manage_status_name'] }}</p>
                                            </td>
                                            <td>
                                                {{ $itemWork['processor_full_name'] }}
                                            </td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($itemWork['date_end'])->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                            <div class="tab-pane autotable_reward" id="m_tabs_3_2" role="tabpanel">
                                <div style="text-align: right;">
                                    <a href="javascript:void(0)"
                                       onclick="index.showPopRecompense('{{$item['time_working_staff_id']}}', 'R')"
                                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill"
                                       style="text-align: right;">
                                            <span>
                                                <i class="fa fa-plus-circle"></i>
                                                <span> @lang('Thêm hình thức thưởng')</span>
                                            </span>
                                    </a>
                                </div>

                                <form class="frmFilter">
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <input type="hidden" name="time_working_staff_id"
                                                   value="{{$item['time_working_staff_id']}}">
                                            <input type="hidden" name="type" value="R">
                                        </div>
                                        <div class="col-lg-2 form-group">
                                            <button class="btn btn-primary color_button btn-search"
                                                    style="display: none">
                                                @lang('TÌM KIẾM') <i
                                                        class="fa fa-search ic-search m--margin-left-5"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-content m--margin-top-30">

                                </div>
                            </div>
                            <div class="tab-pane autotable_punishment" id="m_tabs_3_3" role="tabpanel">
                                <div style="text-align: right;">
                                    <a href="javascript:void(0)"
                                       onclick="index.showPopRecompense('{{$item['time_working_staff_id']}}', 'P')"
                                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('Thêm hình thức phạt')</span>
                                    </span>
                                    </a>
                                </div>

                                <form class="frmFilter">
                                    <div class="row">
                                        <div class="form-group col-lg-6">
                                            <input type="hidden" name="time_working_staff_id"
                                                   value="{{$item['time_working_staff_id']}}">
                                            <input type="hidden" name="type" value="P">
                                        </div>
                                        <div class="col-lg-2 form-group">
                                            <button class="btn btn-primary color_button btn-search"
                                                    style="display: none">
                                                @lang('TÌM KIẾM') <i
                                                        class="fa fa-search ic-search m--margin-left-5"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <div class="table-content m--margin-top-30">

                                </div>
                            </div>
                            <div class="tab-pane" id="m_tabs_3_4" role="tabpanel">
                                <table class="table table-striped m-table m-table--head-bg-default">
                                    <thead class="bg">
                                    <tr>
                                        <th class="tr_thead_list">#</th>
                                        <th class="tr_thead_list">@lang('GIỜ VÀO')</th>
                                        <th class="tr_thead_list">{{__('GIỜ RA')}}</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            1
                                        </td>
                                        <td>
                                            @if ($item['is_check_in'] == 1)
                                                @if ($item['is_check_in'] == 1 && $item['created_type_ci'] == "staff")
                                                    <i class="la la-mobile"></i>
                                                @elseif($item['is_check_in'] == 1 && $item['created_type_ci'] == "admin")
                                                    <i class="la la-user-plus"></i>
                                                @endif

                                                {{\Carbon\Carbon::parse($item['check_in_day']. ' '. $item['check_in_time'])->format('d/m/Y H:i')}}
                                            @else
                                                --:--
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item['is_check_out'] == 1)
                                                @if ($item['is_check_out'] == 1 && $item['created_type_co'] == "staff")
                                                    <i class="la la-mobile"></i>
                                                @elseif($item['is_check_out'] == 1 && $item['created_type_co'] == "admin")
                                                    <i class="la la-user-plus"></i>
                                                @endif

                                                {{\Carbon\Carbon::parse($item['check_out_day']. ' '. $item['check_out_time'])->format('d/m/Y H:i')}}
                                            @else
                                                --:--
                                            @endif
                                        </td>
                                    </tr>

                                    @if ($time_working_change_log != null || $check_in_change_log != null || $check_out_change_log != null)
                                        <tr>
                                            <td>
                                                2
                                            </td>
                                            <td style="text-decoration: line-through;">
                                                @if ($check_in_change_log != null)
                                                    @if ($check_in_change_log['created_type_old'] == "staff")
                                                        <i class="la la-mobile"></i>
                                                    @elseif($check_in_change_log['created_type_old'] == "admin")
                                                        <i class="la la-user-plus"></i>
                                                    @endif

                                                    {{\Carbon\Carbon::parse($check_in_change_log['check_in_day_old']. ' '. $check_in_change_log['check_in_time_old'])->format('d/m/Y H:i')}}
                                                @endif
                                            </td>
                                            <td style="text-decoration: line-through;">
                                                @if ($check_out_change_log != null)
                                                    @if ($check_out_change_log['created_type_old'] == "staff")
                                                        <i class="la la-mobile"></i>
                                                    @elseif($check_out_change_log['created_type_old'] == "admin")
                                                        <i class="la la-user-plus"></i>
                                                    @endif

                                                    {{\Carbon\Carbon::parse($check_out_change_log['check_out_day_old']. ' '. $check_out_change_log['check_out_time_old'])->format('d/m/Y H:i')}}
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="m-form__actions m--align-right w-100">
                    <button data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                    </button>
                    {{--                    <button type="button"--}}
                    {{--                            onclick="index.saveTimeWorking('{{$item['time_working_staff_id']}}', '{{$view}}')"--}}
                    {{--                            class="btn btn-info  color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">--}}
                    {{--							<span>--}}
                    {{--							<i class="la la-check"></i>--}}
                    {{--							<span>{{__('LƯU')}}</span>--}}
                    {{--							</span>--}}
                    {{--                    </button>--}}

                </div>
            </div>
        </div>
    </div>
</div>


