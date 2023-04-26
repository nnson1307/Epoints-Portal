<style>
    .service-option {
        font-weight: bold !important;
        color: #fff !important;
        background: #dff7f8 !important;
        text-transform: uppercase;
    }

    #popup-work .note-toolbar-wrapper {
        height: 100% !important;
    }

    .modal-lg-work {
        max-width: 80% !important
    }

    .span_parent_close {
        background-color: #159cd5;
        position: relative;
        padding: 8px 16px;
        color: #fff;
        border-radius: 5px;
        width: fit-content;
        display: inline-block;
    }

    .close {
        background-color: #159cd5;
        position: absolute;
        color: #fff;
        font-size: 11px;
        top: 7px;
        height: auto;
        line-height: 0;
        right: 3px;
    }

    .btn-metal{
        color: #212529;
        background-color: #adafc6;
        border-color: #a6a7c1;
    }
    .select2{
        width : 100% !important;
    }
    .weekly-select, .monthly-select{
        padding-top: 12px;
        width: 45px;
        height: 45px;
    }
</style>

<div class="modal fade" id="popup-work" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg-work" role="document">
        <div class="modal-content clear-form">
            <div class="modal-header">
                <h4 class="modal-title ss--title m--font-bold">
                    @if($detail != null && $detail['is_edit'] == 0)
                        <input type="hidden" name="is_edit_work" value="0">
                    @endif
                    @if($detail != null)
                        <input type="hidden" name="manage_work_child_id" value="{{$detail['manage_work_id']}}">
                        <i class="far fa-edit ss--icon-title m--margin-right-5"></i>
                        {{ __('CHỈNH SỬA CÔNG VIỆC') }}
                    @else
                        <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
                        {{ __('THÊM CÔNG VIỆC') }}
                    @endif
                </h4>
                <button type="button" class="close" data-dismiss="modal" onclick="WorkChild.closePopup()">&times;
                </button>
            </div>
            <div class="modal-body">

                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{ __('Tiêu đề') }} <b class="text-danger">*</b>
                    </label>
                    <input type="text" name="manage_work_title" class="form-control m-input"
                           {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}
                           value="{{$dataInfo['title']}}"
                           placeholder="{{ __('Nhập tiêu đề') }}...">
                </div>
                <div class="form-group m-form__group">
                    <label class="black_title">
                        {{ __('Loại công việc') }} <b class="text-danger">*</b>
                    </label>
                    <div class="input-group">
                        @if(count($listTypeWork) <= 5)
                            <div class="kt-radio-inline">
                                @foreach ($listTypeWork as $item)
                                    <label class="kt-radio mr-4">
                                        <input type="radio" name="manage_type_work_id" id="popup_manage_type_work_id"
                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} class="mr-2"
                                               value="{{$item['manage_type_work_id']}}"
                                                {{($detail != null && $item['manage_type_work_id'] == $detail['manage_type_work_id']) || (isset($parentWork) && $parentWork['manage_type_work_id'] == $item['manage_type_work_id']) ? 'checked' : ''}}
                                                {{isset($dataShift['manage_type_work_id']) && $dataShift['manage_type_work_id'] == $item['manage_type_work_id'] ? 'checked': ''}}
                                        > {{ $item['manage_type_work_name'] }}
                                        <span></span>
                                    </label>
                                @endforeach
                            </div>
                        @else
                            <select name="manage_type_work_id" id="popup_manage_type_work_id"
                                    class="form-control select2 select2-active" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}>
                                <option value="">@lang('Chọn loại công việc')</option>
                                @foreach ($listTypeWork as $item)
                                    <option value="{{ $item['manage_type_work_id'] }}"
                                            {{($detail != null && $item['manage_type_work_id'] == $detail['manage_type_work_id'])  || (isset($parentWork) && $parentWork['manage_type_work_id'] == $item['manage_type_work_id']) ? 'selected' : ''}}
                                            {{isset($dataShift['manage_type_work_id']) && $dataShift['manage_type_work_id'] == $item['manage_type_work_id'] ? 'selected': ''}}>
                                        {{ $item['manage_type_work_name'] }}
                                    </option>
                                @endforeach
                            </select>
                        @endif
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Ngày bắt đầu')
                            </label>
                            <div class="row">
                                {{--                                <div class="col-8">--}}
                                <div class="col-12">
                                    <div class="input-group date date-multiple">
                                        <input type="text" class="form-control m-input date-timepicker"
                                               @if (isset($dataShift['start_date']))
                                               readonly
                                               value="{{isset($dataShift['start_date']) && $dataShift['start_date'] != null ? \Carbon\Carbon::parse($dataShift['start_date'])->format('d/m/Y H:i') : ''}}"
                                               @else
                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} value="{{$detail != null && isset($detail['date_start']) && $detail['date_start'] != '' && $detail['date_start'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detail['date_start'])->format('d/m/Y H:i') : \Carbon\Carbon::now()->format('d/m/Y H:i')}}"
                                               @endif
                                               placeholder="@lang('Ngày bắt đầu')" name="date_start">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i
                                                        class="la la-calendar-check-o glyphicon-th"></i></span>
                                        </div>
                                    </div>
                                </div>
                                {{--                                <div class="col-4">--}}
                                {{--                                    <input type="text" class="form-control m-input time-input"--}}
                                {{--                                           @if (isset($dataShift['start_time']))--}}
                                {{--                                           readonly--}}
                                {{--                                           value="{{isset($dataShift['start_time']) && $dataShift['start_time'] != null ? \Carbon\Carbon::parse($dataShift['start_time'])->format('H:i') : ''}}"--}}
                                {{--                                           @else--}}
                                {{--                                           {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}--}}
                                {{--                                           value="{{$detail != null && isset($detail['date_start']) && $detail['date_start'] != '' && $detail['date_start'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detail['date_start'])->format('H:i') : ''}}"--}}
                                {{--                                           @endif--}}
                                {{--                                           placeholder="@lang('Giờ')" name="time_start">--}}
                                {{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Ngày kết thúc') <b class="text-danger">*</b>
                            </label>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-group date date-multiple">
                                        <input type="text" class="form-control m-input date-timepicker"
                                               @if (isset($dataShift['start_date']))
                                               readonly
                                               value="{{isset($dataShift['start_date']) && $dataShift['start_date'] != null ? \Carbon\Carbon::parse($dataShift['start_date'])->format('d/m/Y H:i') : ''}}"
                                               @else
                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} value="{{$detail != null && isset($detail['date_end']) && $detail['date_end'] != '' && $detail['date_end'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detail['date_end'])->format('d/m/Y H:i') : ''}}"
                                               @endif
                                               placeholder="@lang('Ngày kết thúc')" name="date_end">
                                        <div class="input-group-append">
                                            <span class="input-group-text"><i
                                                        class="la la-calendar-check-o glyphicon-th"></i></span>
                                        </div>
                                    </div>
                                </div>
                                {{--                                <div class="col-4">--}}
                                {{--                                    <input type="text" class="form-control m-input time-input"--}}
                                {{--                                           @if (isset($dataShift['end_time']))--}}
                                {{--                                           readonly--}}
                                {{--                                           value="{{isset($dataShift['end_time']) && $dataShift['end_time'] != null ? \Carbon\Carbon::parse($dataShift['end_time'])->format('H:i') : ''}}"--}}
                                {{--                                           @else--}}
                                {{--                                           {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} value="{{$detail != null && isset($detail['date_end']) && $detail['date_end'] != '' && $detail['date_end'] != '0000-00-00 00:00:00' ? \Carbon\Carbon::parse($detail['date_end'])->format('H:i') : ''}}"--}}
                                {{--                                           @endif--}}
                                {{--                                           placeholder="@lang('Giờ')" name="time_end">--}}
                                {{--                                </div>--}}
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Trạng thái') <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select name="manage_status_id" class="form-control select2 select2-active">
                                    @foreach ($listStatus as $item)
                                        @if($detail != null && $detail['is_approve_id'] == 1 && $item['manage_status_id'] == 6 && $detail['approve_id'] != \Illuminate\Support\Facades\Auth::id())
                                        @else
                                            <option value="{{ $item['manage_status_id'] }}" {{$detail != null && $item['manage_status_id'] == $detail['manage_status_id'] ? 'selected' : ''}}>{{ $item['manage_status_name'] }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Thời gian ước lượng')
                            </label>
                            <div>
                                <div class="input-group mb-3">
                                    <div style="width:70%">
                                        <input type="text" class="form-control input-mask" name="time"
                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} placeholder="{{__('Nhập thời lượng')}}"
                                               value="{{$detail != null ? $detail['time'] : ''}}">
                                    </div>
                                    <div class="input-group-append" style="width:30%">
                                        <select class="input-group-text"
                                                name="time_type" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}>
                                            <option value="h" {{$detail != null && $detail['time_type'] == 'h' ? 'selected' : ''}}>{{ __('Giờ') }}</option>
                                            {{--                                            <option value="d" {{$detail != null && $detail['time_type'] == 'd' ? 'selected' : ''}}>{{ __('Ngày') }}</option>--}}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Chọn nhân viên thực hiện') <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                @if(isset($data['staff_id']))
                                    <input type="hidden" name="processor_id" value="{{$data['staff_id']}}">
                                    <select disabled
                                            class="form-control select2 select2-active" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}>
                                        <option value="">@lang('Chọn nhân viên thực hiện')</option>
                                        @foreach ($listStaff as $item)
                                            <option value="{{ $item['staff_id'] }}"
                                                    {{$data['staff_id'] == $item['staff_id'] ? 'selected' : ''}}
                                                    {{isset($dataShift['processor_id']) && $dataShift['processor_id'] == $item['staff_id'] ? 'selected' : ''}}
                                            >{{ $item['full_name'] }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select name="processor_id" class="form-control select2 select2-active"
                                            @if (isset($dataShift['processor_id']))
                                            readonly
                                    @else
                                        {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}
                                            @endif
                                    >
                                        <option value="">@lang('Chọn nhân viên thực hiện')</option>
                                        @foreach ($listStaff as $item)
                                            <option value="{{ $item['staff_id'] }}"
                                            @if (isset($dataShift['processor_id']))
                                                {{isset($dataShift['processor_id']) && $dataShift['processor_id'] == $item['staff_id'] ? 'selected' : ''}}
                                                    @else
                                                {{($detail != null && $detail['processor_id'] == $item['staff_id']) || ($detail == null && $item['staff_id'] == \Illuminate\Support\Facades\Auth::id()) ? 'selected' : ''}}
                                                    @endif
                                            >{{ $item['full_name'] }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Dự án')
                            </label>

                            <div class="input-group">
                                @if($detail == null)
                                    <select id="popup_manage_project_id" name="manage_project_id"
                                            onchange="changeListStaff(this)"
                                            class="form-control select2 select2-active" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}>
                                        <option value="">@lang('Chọn dự án')</option>
                                        @foreach ($listProject as $item)
                                            <option value="{{ $item['manage_project_id'] }}" {{($detail != null && $detail['manage_project_id'] == $item['manage_project_id']) || (isset($manage_project_id) && $manage_project_id == $item['manage_project_id']) || (isset($parentWork) && $parentWork['manage_project_id'] == $item['manage_project_id']) ? "selected" : ''}} >{{ $item['manage_project_name'] }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    @if(\Helper::checkIsAdmin())
                                        <select id="popup_manage_project_id" name="manage_project_id"
                                                onchange="changeListStaff(this)"
                                                class="form-control select2 select2-active" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}>
                                            <option value="">@lang('Chọn dự án')</option>
                                            @foreach ($listProject as $item)
                                                <option value="{{ $item['manage_project_id'] }}" {{($detail != null && $detail['manage_project_id'] == $item['manage_project_id']) || (isset($manage_project_id) && $manage_project_id == $item['manage_project_id']) || (isset($parentWork) && $parentWork['manage_project_id'] == $item['manage_project_id']) ? "selected" : ''}} >{{ $item['manage_project_name'] }}</option>
                                            @endforeach
                                        </select>
                                    @else
                                        <?php
                                        $manage_project_name = '';
                                        foreach ($listProject as $item) {
                                            if ($detail['manage_project_id'] == $item['manage_project_id']) {
                                                $manage_project_name = $item['manage_project_name'];
                                            }
                                        }
                                        ?>
                                        <input type="text" disabled class="form-control" value="{{$manage_project_name}}">
                                        <input type="hidden" id="popup_manage_project_id" name="manage_project_id"
                                               value="{{$detail['manage_project_id']}}">
                                        {{--                                                <input type="hidden" name="manage_project_id" value="{{$detail['manage_project_id']}}">--}}
                                    @endif

                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <label class="black_title">
                            @lang('Giai đoạn'):
                        </label>
                        <div>
                            <div class="input-group mb-3">
                                <select class="form-control" id="popup_manage_project_phase_id" name="manage_project_phase_id">
                                    <option value="">{{__('Chọn giai đoạn')}}</option>
                                    @foreach($listPhase as $item)
                                        <option value="{{$item['manage_project_phase_id']}}" {{$detail != null && $detail['manage_project_phase_id'] == $item['manage_project_phase_id'] ? 'selected' : ''}}>{{$item['name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="m-form__group">
                            <div class="form-check pl-0">
                                @if(isset($data['work_detail']))
                                    <label class="form-check-label m-checkbox m-checkbox--air">
                                        <input type="checkbox" name="is_approve_id" value="1" id="is_approve_id"
                                               onclick="WorkDetail.approveStaff()" {{$detail != null && ($detail['is_edit'] == 0 || $detail['type_card_work'] == 'kpi' ) ? "disabled" : '' }} {{$detail != null && $detail['is_approve_id'] == 1 ? 'checked' : ''}}>
                                        <span></span>
                                        <div class="pt-1">{{ __('Công việc cần phê duyệt') }}</div>
                                    </label>
                                @else
                                    <label class="form-check-label m-checkbox m-checkbox--air">
                                        <input type="checkbox" name="is_approve_id" value="1" id="is_approve_id"
                                               onclick="WorkChild.approveStaff()" {{$detail != null && ($detail['is_edit'] == 0 || $detail['type_card_work'] == 'kpi' ) ? "disabled" : '' }} {{$detail != null && $detail['is_approve_id'] == 1 ? 'checked' : ''}}>
                                        <span></span>
                                        <div class="pt-1">{{ __('Công việc cần phê duyệt') }}</div>
                                    </label>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title black_title_not_approve"
                                   style="{{$detail != null && $detail['is_approve_id'] == 1 ? 'display:none' : ''}}">
                                @lang('Chọn người duyệt')
                            </label>
                            <label class="black_title black_title_approve"
                                   style="{{$detail != null && $detail['is_approve_id'] == 1 ? '' : 'display:none'}}">
                                @lang('Chọn người duyệt') <b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                @if(\Helper::checkIsAdmin())
                                    <select name="approve_id" id="approve_id_select"
                                            class="form-control select2 select2-active">
                                        <option value="">@lang('Chọn người duyệt')</option>
                                        @foreach ($listStaff as $item)
                                            <option value="{{ $item['staff_id'] }}" {{$detail != null && $detail['approve_id'] == $item['staff_id'] ? 'selected' : ''}}>{{ $item['full_name'] }}</option>
                                        @endforeach
                                    </select>
                                @else
                                    <select name="approve_id" id="approve_id_select"
                                            class="form-control select2 select2-active" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : ($detail != null && $detail['is_approve_id'] == 0 ? 'disabled' : ($detail == null ? 'disabled' : '')) }}>
                                        <option value="">@lang('Chọn người duyệt')</option>
                                        @foreach ($listStaff as $item)
                                            <option value="{{ $item['staff_id'] }}" {{$detail != null && $detail['approve_id'] == $item['staff_id'] ? 'selected' : ''}}>{{ $item['full_name'] }}</option>
                                        @endforeach
                                    </select>
                                @endif

                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Công việc cha')
                            </label>
                            <div class="input-group">
                                {{--                                @if(isset($data['parent_id']))--}}
                                {{--                                    <input type="hidden" name="parent_id" value="{{$data['parent_id']}}">--}}
                                {{--                                @endif--}}
                                <select onchange="changeParentTask()" id="parent_id" name="parent_id"
                                        {{$detail != null && $detail['is_parent'] != 0 ? 'disabled' : ''}}
                                        class="form-control select2 select2-active" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}>
                                    <option value="">@lang('Chọn công việc cha')</option>
                                    {{--                                    @foreach ($listWork as $item)--}}
                                    {{--                                        <option value="{{ $item['manage_work_id'] }}" {{isset($detail['parent_id']) && $detail['parent_id'] == $item['manage_work_id'] ? 'selected' : (isset($data['parent_id']) && $data['parent_id'] == $item['manage_work_id'] ? 'selected' : '')}}>{{ $item['manage_work_title'] }}</option>--}}
                                    {{--                                    @endforeach--}}
                                    @if($parentWork != null)
                                        <option value="{{$parentWork['manage_work_id']}}"
                                                selected>{{$parentWork['manage_work_title']}}</option>
                                    @endif

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('Nhân viên hỗ trợ'):
                            </label>
                            <div class="form-group div_staff_support">
                                @if ($detail != null && $detail['is_edit'] == 1 && count($detail['list_support']) > 0)
                                    @php $staffName = ''; @endphp

                                    @foreach($detail['list_support'] as $k => $v)
                                        <?php $comma = ''; ?>
                                        @if ($k + 1 < count($detail['list_support']))
                                            <?php $comma = ', '; ?>
                                        @endif

                                        <?php $staffName .= $v['staff_name'] . $comma?>

                                        <input type="hidden" name="support[]" value="{{$v['staff_id']}}">
                                    @endforeach

                                    <textarea class="form-control" rows="5" disabled>{{$staffName}}</textarea>
                                @endif
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-sm m-btn--icon bg-light color"
                                        onclick="WorkChild.showPopStaff()">
                                <span>
                                    <span class="la la-plus" aria-hidden="true"></span>
                                    @lang('Chọn nhân viên hỗ trợ')
                                </span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{ __('Tiến độ') }}
                            </label>
                            {{--                            <select name="progress" class="form-control select2 select2-active" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}--}}
                            {{--                                    data-placeholder="Chọn nhân viên hỗ trợ">--}}
                            {{--                                @for($i = 0 ; $i <= 100 ; $i = $i+10)--}}
                            {{--                                    <option value="{{ $i }}" {{ $detail != null && $detail['progress'] == $i ? 'selected' : '' }}>{{ $i .' %' }}</option>--}}
                            {{--                                @endfor--}}
                            {{--                            </select>--}}
                            <div class="input-group">
                                <input type="text" class="progress_input form-control" name="progress"
                                       {{$detail != null && ($detail['is_parent'] != 0 || $detail['is_edit'] == 0 ) ? 'disabled' : ''}}
                                       value="{{ $detail != null ? $detail['progress'] : '' }}">
                                <div class="input-group-append">
                                    <span class="input-group-text">%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-2">
                        <div class="m-form__group">
                            <label> {{ __('Mô tả công việc') }}</label>
                            <textarea name="description"
                                      class="form-control m-input summernote" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}>{!! $dataInfo['description'] !!}</textarea>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <button type="button" class="btn btn-sm m-btn--icon bg-light color" data-toggle="collapse"
                                data-target="#moreInfo" aria-expanded="false" aria-controls="moreInfo">
                                <span>
                                    <span class="fa fa-calendar pr-1" aria-hidden="true"></span>
                                    {{ __('Thông tin thêm') }}
                                </span>
                        </button>
                        @if($detail == null)
                            <button type="button" class="btn btn-sm m-btn--icon bg-light color"
                                    data-toggle="collapse" data-target="#multiCollapseExample3"
                                    aria-expanded="false" aria-controls="multiCollapseExample3">
                                        <span>
                                            <i class="fas fa-plus-circle"></i>
                                            {{ __('managerwork::managerwork.add_document') }}
                                        </span>
                            </button>
                        @endif
                        @if($detail == null)
                            <button type="button" class="btn btn-sm m-btn--icon bg-light color"
                                    {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} data-toggle="collapse"
                                    data-target="#multiCollapseExample1" aria-expanded="false"
                                    aria-controls="multiCollapseExample1">
                                                <span>
                                                    <span class="fa fa-calendar pr-1" aria-hidden="true"></span>
                                                    {{ __('Thêm nhắc nhở') }}
                                                </span>
                            </button>
                        @endif
{{--                        @if($detail == null || $detail['is_parent'] != 0)--}}
                            <button type="button" class="btn btn-sm m-btn--icon bg-light color"
                                    data-toggle="collapse" data-target="#multiCollapseExample2"
                                    aria-expanded="false" aria-controls="multiCollapseExample2">
                                            <span>
                                                <span class="fa fa-bell pr-1" aria-hidden="true"></span>
                                                {{ __('Tần suất lặp lại') }}
                                            </span>
                            </button>
{{--                        @endif--}}
                    </div>
                    <div class="col-12">
                        <div class="collapse show multi-collapse mt-3" id="moreInfo">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Loại khách hàng')
                                        </label>
                                        <div class="input-group">
                                            <select name="manage_work_customer_type" onchange="WorkAll.changeCustomer()"
                                                    id="manage_work_customer_type"
                                                    class="form-control select2 select2-active" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}>
                                                <option value="customer" {{$detail != null && $detail['manage_work_customer_type'] == 'customer' ? 'selected' : '' }}>@lang('Khách hàng')</option>
                                                <option value="lead" {{$detail != null && $detail['manage_work_customer_type'] == 'lead' ? 'selected' : '' }}>@lang('Khách hàng tiềm năng')</option>
                                                <option value="deal" {{$detail != null && $detail['manage_work_customer_type'] == 'deal' ? 'selected' : '' }}>@lang('Danh sách Deal')</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Mức độ ưu tiên') <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select name="priority"
                                                    class="form-control select2 select2-active" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}>
                                                <option value="">@lang('Mức độ ưu tiên')</option>
                                                <option value="1" {{$detail != null && $detail['priority'] == 1 ? 'selected' : ''}}>{{__('Cao')}}</option>
                                                <option value="2" {{$detail != null && $detail['priority'] == 2 || $detail == null ? 'selected' : ''}}>{{__('Bình thường')}}</option>
                                                <option value="3" {{$detail != null && $detail['priority'] == 3 ? 'selected' : ''}}>{{__('Thấp')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title text-customer-select"
                                               style="{{$detail != null && $detail['manage_work_customer_type'] == 'deal' ? 'display:none' : ''}}">
                                            @lang('Khách hàng liên quan')
                                        </label>
                                        <label class="black_title text-deal-select"
                                               style="{{$detail != null && $detail['manage_work_customer_type'] == 'deal' ? '' : 'display:none'}}">
                                            @lang('Deal liên quan')
                                        </label>
                                        <div class="input-group">
                                            <select name="customer_id" id="customer_id"
                                                    onchange="WorkAll.changeObjectCustomer(this)"
                                                    class="form-control select2 select2-active" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}>
                                                @if ($detail != null && $detail['manage_work_customer_type'] == 'deal')
                                                    <option value="">@lang('Chọn deal liên quan')</option>
                                                @else
                                                    <option value="">@lang('Chọn khách hàng liên quan')</option>
                                                @endif
                                                @foreach ($listCustomer as $item)
                                                    <option value="{{ $item['customer_id'] }}" {{$detail != null && $detail['customer_id'] == $item['customer_id'] ? 'selected' : ''}}>{{ $item['customer_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <br/>
                                        <div class="div_detail_customer">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Loại thẻ công việc')
                                        </label>
                                        <div class="input-group">
                                            <select name="type_card_work"
                                                    class="form-control select2 select2-active" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}>
                                                <option value="">@lang('Chọn loại thẻ công việc')</option>
                                                <option value="kpi" {{$detail != null && $detail['type_card_work'] == 'kpi' ? 'selected' : ''}}>
                                                    Kpi
                                                </option>
                                                <option value="bonus" {{$detail != null && $detail['type_card_work'] == 'bonus' || $detail == null ? 'selected' : ''}}>{{__('Thường')}}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            @lang('Tags')
                                        </label>
                                        <div class="input-group">
                                            <select name="manage_tag[]" class="form-control select2 select2-active"
                                                    {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}
                                                    multiple data-placeholder="Chọn tag">
                                                <option value="">@lang('Chọn tags')</option>
                                                @foreach ($listTag as $item)
                                                    @if($detail != null && count($detail['list_tag']) != 0 )
                                                        <option value="{{ $item['manage_tag_name'] }}" {{in_array($item['manage_tag_name'],collect($detail['list_tag'])->pluck('manage_tag_name')->toArray()) ? 'selected' : ''}}>{{ $item['manage_tag_name'] }}</option>
                                                    @else
                                                        <option value="{{ $item['manage_tag_name'] }}">{{ $item['manage_tag_name'] }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                {{--                                <div class="col-lg-12 mt-3 mb-3">--}}
                                {{--                                    @if($detail == null)--}}
                                {{--                                        <button type="button" class="btn btn-sm m-btn--icon bg-light color"--}}
                                {{--                                                {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} data-toggle="collapse"--}}
                                {{--                                                data-target="#multiCollapseExample1" aria-expanded="false"--}}
                                {{--                                                aria-controls="multiCollapseExample1">--}}
                                {{--                                                <span>--}}
                                {{--                                                    <span class="fa fa-calendar pr-1" aria-hidden="true"></span>--}}
                                {{--                                                    {{ __('Thêm nhắc nhở') }}--}}
                                {{--                                                </span>--}}
                                {{--                                        </button>--}}
                                {{--                                    @endif--}}
                                {{--                                    @if($detail == null || $detail['is_parent'] != 0)--}}
                                {{--                                        <button type="button" class="btn btn-sm m-btn--icon bg-light color"--}}
                                {{--                                                data-toggle="collapse" data-target="#multiCollapseExample2"--}}
                                {{--                                                aria-expanded="false" aria-controls="multiCollapseExample2">--}}
                                {{--                                            <span>--}}
                                {{--                                                <span class="fa fa-bell pr-1" aria-hidden="true"></span>--}}
                                {{--                                                {{ __('Tần suất lặp lại') }}--}}
                                {{--                                            </span>--}}
                                {{--                                        </button>--}}
                                {{--                                    @endif--}}
                                {{--                                </div>--}}
                                {{--                                <div class="col-12">--}}
                                {{--                                    <div class="row">--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}
                            </div>
                        </div>
                        @if($detail == null)
                            <div class="col-12 mb-3 mt-3">
                                <div class="collapse multi-collapse" id="multiCollapseExample1">
                                    <div class="card card-body">
                                        <div class="form-group m-form__group">
                                            <h5>{{__('Nhắc nhở')}}</h5>
                                        </div>
                                        <div class="form-group m-form__group">
                                            <label class="black_title">
                                                @lang('Nhắc ai'):<b class="text-danger">*</b>
                                            </label>
                                            <div class="input-group">
                                                <select multiple name="staff[]"
                                                        class="form-control select2 select2-active" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}>
                                                    @foreach ($listStaff as $value)
                                                        <option value="{{ $value['staff_id'] }}">{{ $value['full_name'] }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="form-group m-form__group">
                                                    <label class="black_title">
                                                        @lang('Thời gian nhắc'):<b
                                                                class="text-danger">*</b>
                                                    </label>
                                                    <div class="input-group date">
                                                        <input type="text"
                                                               class="form-control m-input date-timepicker"
                                                               readonly
                                                               placeholder="@lang('Thời gian nhắc')"
                                                               name="date_remind"
                                                               value="" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}>
                                                        <div class="input-group-append">
                                                                            <span class="input-group-text"><i
                                                                                        class="la la-calendar-check-o glyphicon-th"></i></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group m-form__group">
                                                    <label class="black_title">
                                                        @lang('Thời gian trước nhắc nhở')
                                                    </label>
                                                    <div>
                                                        <div class="input-group mb-3">
                                                            <input type="text"
                                                                   class="form-control input-mask-remind"
                                                                   id="time_remind" name="time_remind"
                                                                   value=""
                                                                   {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}
                                                                   placeholder="{{__('Nhập thời gian trước nhắc nhở')}}">
                                                            <div class="input-group-append">
                                                                <select class="input-group-text"
                                                                        name="time_type_remind" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}>
                                                                    <option value="m"
                                                                            selected>{{ __('Phút') }}</option>
                                                                    <option value="h">{{ __('Giờ') }}</option>
                                                                    <option value="d">{{ __('Ngày') }}</option>
                                                                    <option value="w">{{ __('Tuần') }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="form-group m-form__group">
                                                    <label> {{ __('Nội dung') }}</label>:<b
                                                            class="text-danger">*</b>
                                                    <textarea name="description_remind"
                                                              class="form-control m-input"
                                                              rows="3" {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }}></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="col-12">
                            <div class="collapse multi-collapse border-0 @if($detail != null && $detail['repeat_type'] != null) show @endif " id="multiCollapseExample2">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group m-form__group">
                                                <h5>{{__('Tần suất lặp lại')}}</h5>
                                            </div>
                                            <div class="row">
                                                <div class="col-2">{{__('Lặp lại')}}</div>
                                                <div class="col-10">
                                                    <div class="kt-radio-list">
                                                        <div class="row">
                                                            <div class="col-12 mb-3">
                                                                <label class="kt-radio">
                                                                    @if(isset($data['work_detail']))
                                                                        <input type="radio"
                                                                               class="repeat_type select-parent"
                                                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} name="repeat_type"
                                                                               value="none"
                                                                               {{$detail == null ? 'checked' : ($detail != null && (!isset($detail['repeat_type'])) ? 'checked' : '' )}} onchange="WorkDetail.changeRepeat()"> {{__('Không có')}}
                                                                        <span></span>
                                                                    @else
                                                                        <input type="radio"
                                                                               class="repeat_type select-parent"
                                                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} name="repeat_type"
                                                                               value="none"
                                                                               {{$detail == null ? 'checked' : ($detail != null && (!isset($detail['repeat_type'])) ? 'checked' : '' )}} onchange="WorkChild.changeRepeat()"> {{__('Không có')}}
                                                                        <span></span>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <label class="kt-radio">
                                                                    @if(isset($data['work_detail']))
                                                                        <input type="radio"
                                                                               class="repeat_type disabled-parent"
                                                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} name="repeat_type"
                                                                               value="daily"
                                                                               {{$detail != null && in_array($detail['repeat_type'],['daily']) ? 'checked' : '' }} onchange="WorkDetail.changeRepeat()"> {{__('Hàng ngày')}}
                                                                        <span></span>
                                                                    @else
                                                                        <input type="radio"
                                                                               class="repeat_type disabled-parent"
                                                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} name="repeat_type"
                                                                               value="daily"
                                                                               {{$detail != null && in_array($detail['repeat_type'],['daily']) ? 'checked' : '' }} onchange="WorkChild.changeRepeat()"> {{__('Hàng ngày')}}
                                                                        <span></span>
                                                                    @endif
                                                                </label>
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <label class="kt-radio">
                                                                    @if(isset($data['work_detail']))
                                                                        <input type="radio"
                                                                               class="repeat_type disabled-parent"
                                                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} name="repeat_type"
                                                                               value="weekly"
                                                                               {{$detail != null && in_array($detail['repeat_type'],['weekly']) ? 'checked' : '' }} onchange="WorkDetail.changeRepeat()"> {{__('Hàng tuần một lần mỗi')}}
                                                                        <span></span>
                                                                    @else
                                                                        <input type="radio"
                                                                               class="repeat_type disabled-parent"
                                                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} name="repeat_type"
                                                                               value="weekly"
                                                                               {{$detail != null && in_array($detail['repeat_type'],['weekly']) ? 'checked' : '' }} onchange="WorkChild.changeRepeat()"> {{__('Hàng tuần một lần mỗi')}}
                                                                        <span></span>
                                                                    @endif

                                                                </label>
                                                                @if(isset($data['work_detail']))
                                                                    <div class="pl-3 block_weekly"
                                                                         style="{{$detail == null ? 'display:none' : ($detail != null && $detail['repeat_type'] == 'weekly' ? '' : 'display:none') }}">
                                                                        @for($i = 0; $i <= 6 ; $i++)
                                                                            <label class="weekly-select weekly-select-{{$i}} {{$detail != null && $detail['repeat_type'] == 'weekly' && in_array($i,$listTime) == true ? 'weekly-select-active' : '' }}"
                                                                                   data-week="{{$i}}"
                                                                                   onclick="WorkDetail.selectWeekly({{$i}})">{{$i+2 == 8 ? __('CN') : __('T'.($i+2))}}</label>
                                                                            <input type="hidden"
                                                                                   class="weekly-select-{{$i}}"
                                                                                   name="manage_repeat_time_weekly[]"
                                                                                   value="{{$detail != null && $detail['repeat_type'] == 'weekly' && in_array($i,$listTime) == true  ? $i : '' }}">
                                                                        @endfor
                                                                    </div>
                                                                @else
                                                                    <div class="pl-3 block_weekly"
                                                                         style="{{$detail == null ? 'display:none' : ($detail != null && $detail['repeat_type'] == 'weekly' ? '' : 'display:none') }}">
                                                                        @for($i = 0; $i <= 6 ; $i++)
                                                                            <label class="weekly-select weekly-select-{{$i}} {{$detail != null && $detail['repeat_type'] == 'weekly' && in_array($i,$listTime) == true ? 'weekly-select-active' : '' }}"
                                                                                   data-week="{{$i}}"
                                                                                   onclick="WorkChild.selectWeekly({{$i}})">{{$i+2 == 8 ? __('CN') : __('T'.($i+2))}}</label>
                                                                            <input type="hidden"
                                                                                   class="weekly-select-{{$i}}"
                                                                                   name="manage_repeat_time_weekly[]"
                                                                                   value="{{$detail != null && $detail['repeat_type'] == 'weekly' && in_array($i,$listTime) == true  ? $i : '' }}">
                                                                        @endfor
                                                                    </div>
                                                                @endif

                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <label class="kt-radio">
                                                                    @if(isset($data['work_detail']))
                                                                        <input type="radio"
                                                                               class="repeat_type  disabled-parent"
                                                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} name="repeat_type"
                                                                               value="monthly"
                                                                               {{$detail != null && in_array($detail['repeat_type'],['monthly']) == true ? 'checked' : '' }}  onchange="WorkDetail.changeRepeat()"> {{__('Hàng tháng')}}
                                                                        <span></span>
                                                                    @else
                                                                        <input type="radio"
                                                                               class="repeat_type disabled-parent"
                                                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} name="repeat_type"
                                                                               value="monthly"
                                                                               {{$detail != null && in_array($detail['repeat_type'],['monthly']) == true ? 'checked' : '' }}  onchange="WorkChild.changeRepeat()"> {{__('Hàng tháng')}}
                                                                        <span></span>
                                                                    @endif

                                                                </label>
                                                                <div class="pl-3 block_monthly"
                                                                     style="{{$detail != null && in_array($detail['repeat_type'],['monthly']) ? '' : 'display:none' }}">
                                                                    @if(isset($data['work_detail']))
                                                                        @for($i = 1; $i <= 31 ; $i++)
                                                                            <label class="monthly-select monthly-select-{{$i}} {{$detail != null && $detail['repeat_type'] == 'monthly' && in_array($i,$listTime) == true ? 'weekly-select-active' : '' }}"
                                                                                   data-month="{{$i}}"
                                                                                   onclick="WorkDetail.selectMonthly({{$i}})">{{$i}}</label>
                                                                            <input type="hidden"
                                                                                   class="monthly-select monthly-select-{{$i}}"
                                                                                   name="manage_repeat_time_monthly[]"
                                                                                   value="{{$detail != null && $detail['repeat_type'] == 'monthly' && in_array($i,$listTime) == true  ? $i : '' }}">
                                                                        @endfor
                                                                    @else
                                                                        @for($i = 1; $i <= 31 ; $i++)
                                                                            <label class="monthly-select monthly-select-{{$i}} {{$detail != null && $detail['repeat_type'] == 'monthly' && in_array($i,$listTime) == true ? 'weekly-select-active' : '' }}"
                                                                                   data-month="{{$i}}"
                                                                                   onclick="WorkChild.selectMonthly({{$i}})">{{$i}}</label>
                                                                            <input type="hidden"
                                                                                   class="monthly-select monthly-select-{{$i}}"
                                                                                   name="manage_repeat_time_monthly[]"
                                                                                   value="{{$detail != null && $detail['repeat_type'] == 'monthly' && in_array($i,$listTime) == true  ? $i : '' }}">
                                                                        @endfor
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-2">{{__('Kết thúc')}}</div>
                                                <div class="col-10">
                                                    <div class="kt-radio-list">
                                                        <div class="row">
                                                            <div class="col-12 mb-3">
                                                                <label class="kt-radio">
                                                                    @if(isset($data['work_detail']))
                                                                        <input type="radio"
                                                                               name="repeat_end"
                                                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} class="repeat_end select-parent"
                                                                               value="none"
                                                                               {{$detail == null ? 'checked' : ($detail != null && ($detail['repeat_end'] == 'none' || !isset($detail['repeat_end'])) ? 'checked' : '' )}} onchange="WorkDetail.changeRepeatEnd()"> {{__('Không bao giờ')}}
                                                                        <span></span>
                                                                    @else
                                                                        <input type="radio"
                                                                               name="repeat_end"
                                                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} class="repeat_end select-parent"
                                                                               value="none"
                                                                               {{$detail == null ? 'checked' : ($detail != null && ($detail['repeat_end'] == 'none' || !isset($detail['repeat_end'])) ? 'checked' : '' )}} onchange="WorkChild.changeRepeatEnd()"> {{__('Không bao giờ')}}
                                                                        <span></span>
                                                                    @endif

                                                                </label>
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <div class="row">
                                                                    <div class="col-2">
                                                                        <label class="kt-radio">
                                                                            @if(isset($data['work_detail']))
                                                                                <input type="radio"
                                                                                       name="repeat_end"
                                                                                       {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} class="repeat_end disabled-parent"
                                                                                       value="after"
                                                                                       {{$detail != null && in_array($detail['repeat_end'],['after']) ? 'checked' : '' }} onchange="WorkDetail.changeRepeatEnd()"> {{__('Sau')}}
                                                                                <span></span>
                                                                            @else
                                                                                <input type="radio"
                                                                                       name="repeat_end"
                                                                                       {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} class="repeat_end disabled-parent"
                                                                                       value="after"
                                                                                       {{$detail != null && in_array($detail['repeat_end'],['after']) ? 'checked' : '' }} onchange="WorkChild.changeRepeatEnd()"> {{__('Sau')}}
                                                                                <span></span>
                                                                            @endif

                                                                        </label>
                                                                    </div>
                                                                    <div class="col-10">
                                                                        <div class="row">
                                                                            <div class="col-3">
                                                                                <input type="text"
                                                                                       class="form-control d-inline-block disabled_block repeat_end_time disabled-parent-text"
                                                                                       {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} name="repeat_end_time"
                                                                                       id="repeat_end_time"
                                                                                       value="{{$detail != null && $detail['repeat_end'] == 'after' ? $detail['repeat_end_time'] : '' }}" {{$detail == null ? 'disabled' : ($detail != null && $detail['repeat_end'] == 'after' ? '' : 'disabled' )}}>
                                                                            </div>
                                                                            <div class="col-9">
                                                                                <select {{$detail == null ? 'disabled' : ($detail != null && $detail['repeat_end'] != 'after' ? 'disabled' : '' )}} {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} class="form-control select2-active w-25 d-inline-block disabled_block repeat_end_type disabled-parent-select"
                                                                                        name="repeat_end_type">
                                                                                    <option value="d" {{$detail != null && in_array($detail['repeat_end'],['after']) && $detail['repeat_end_type'] == 'd' ? 'selected' : '' }}>{{__('Ngày')}}</option>
                                                                                    <option value="w" {{$detail != null && in_array($detail['repeat_end'],['after']) && $detail['repeat_end_type'] == 'w' ? 'selected' : '' }}>{{__('Tuần')}}</option>
                                                                                    <option value="m" {{$detail != null && in_array($detail['repeat_end'],['after']) && $detail['repeat_end_type'] == 'm' ? 'selected' : '' }}>{{__('Tháng')}}</option>
                                                                                </select>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 mb-3">
                                                                <div class="row">
                                                                    <div class="col-2">
                                                                        <label class="kt-radio">
                                                                            @if(isset($data['work_detail']))
                                                                                <input type="radio"
                                                                                       name="repeat_end"
                                                                                       {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} class="repeat_end disabled-parent"
                                                                                       value="date"
                                                                                       {{$detail == null ? '' : ($detail != null && $detail['repeat_end'] == 'date' ? 'checked' : '')}} onchange="WorkDetail.changeRepeatEnd()"> {{__('Vào ngày')}}
                                                                                <span></span>
                                                                            @else
                                                                                <input type="radio"
                                                                                       name="repeat_end"
                                                                                       {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} class="repeat_end disabled-parent"
                                                                                       value="date"
                                                                                       {{$detail == null ? '' : ($detail != null && $detail['repeat_end'] == 'date' ? 'checked' : '')}} onchange="WorkChild.changeRepeatEnd()"> {{__('Vào ngày')}}
                                                                                <span></span>
                                                                            @endif

                                                                        </label>
                                                                    </div>
                                                                    <div class="col-10">
                                                                        <input type="text"
                                                                               class="form-control date-timepicker-repeat disabled_block repeat_end_full_time disabled-parent-text"
                                                                               {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} name="repeat_end_full_time"
                                                                               {{$detail != null && $detail['repeat_end'] == 'date' ? '' : 'disabled'}} value="{{$detail != null && $detail['repeat_end'] == 'date' ? \Carbon\Carbon::parse($detail['repeat_end_full_time'])->format('d/m/Y') : ''}}">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="row">
                                                <div class="col-2">{{__('Giờ lặp')}}</div>
                                                <div class="col-10">
                                                    <input type="text"
                                                           class="form-control disabled-parent-text"
                                                           name="repeat_time" id="repeat_time"
                                                           {{$detail != null && $detail['is_edit'] == 0 ? 'disabled' : '' }} value="{{$detail != null && $detail['repeat_time'] != '' ? \Carbon\Carbon::createFromFormat('H:i:s',$detail['repeat_time'])->format('H:i') : ''}}">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($detail == null)
                        <div class="col-lg-12 mt-3">
                            <div class="collapse multi-collapse border-0 mt-3" id="multiCollapseExample3">
                                <div class="card card-body">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="form-group m-form__group">
                                                <label class="black_title">
                                                    @lang('Hồ sơ đính kèm'):
                                                </label>
                                                <div class="input-group">
                                                    <div class="input-group">
                                                        <div class="col-lg-12 col-md-12 col-sm-12 ss--padding-left-0">
                                                            <div class="m-dropzone dropzone m-dropzone--primary dz-clickable"
                                                                 action="{{route('manager-project.work.detail.upload-file')}}"
                                                                 id="dropzoneImageWork">
                                                                <div class="m-dropzone__msg dz-message needsclick">
                                                                    <h3 class="m-dropzone__msg-title"><i
                                                                                class="fas fa-plus"></i></h3>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group m-form__group row mt-3 upload-image-document"
                                                     id="upload-image-work">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
            <input type="hidden" id="id_staff" value="{{\Illuminate\Support\Facades\Auth::id()}}">
            <input type="hidden" id="is_parent_number"
                   value="{{$detail != null && $detail['is_parent'] != 0 ? $detail['is_parent'] : 0}}">
            <input type="hidden" id="total_child_work"
                   value="{{$detail != null && $detail['total_child_job'] ? $detail['total_child_job'] : 0}}">
            <input type="hidden" id="create_object_type" name="create_object_type"
                   value="{{$dataInfo['create_object_type']}}">
            <input type="hidden" id="create_object_id" name="create_object_id"
                   value="{{$dataInfo['create_object_id']}}">

            <input type="hidden" id="manage_project_issue_id" name="manage_project_issue_id"
                   value="{{isset($data['project_issue_id']) ? $data['project_issue_id'] : null}}">

            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        @if(isset($data['work_detail']))
                            <button type="button" onclick="WorkDetail.cancelWork()"
                                    class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{ __('HỦY') }}</span>
                            </span>
                            </button>
                        @else
                            <button type="button" onclick="WorkChild.cancelWork()"
                                    class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{ __('HỦY') }}</span>
                            </span>
                            </button>
                        @endif

                        @if(isset($data['work_detail']))
                            <button type="button" onclick="WorkDetail.saveWork()"
                                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('LƯU THÔNG TIN') }}</span>
                            </span>
                            </button>
                        @else
                            <button type="button" onclick="WorkChild.saveWork()"
                                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('LƯU THÔNG TIN') }}</span>
                            </span>
                            </button>
                        @endif

                        @if($detail == null)
                            @if(isset($data['work_detail']))
                                <button type="button" onclick="WorkDetail.saveWork(1)"
                                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{ __('LƯU THÔNG TIN') }}</span>
                            </span>
                                </button>
{{--                            @else--}}
{{--                                <button type="button" onclick="WorkChild.saveWork(1)"--}}
{{--                                        class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 m--margin-bottom-5">--}}
{{--                                    <span class="ss--text-btn-mobi">--}}
{{--                                        <i class="fa fa-plus-circle"></i>--}}
{{--                                        <span>{{ __('LƯU & TẠO MỚI') }}</span>--}}
{{--                                    </span>--}}
{{--                                </button>--}}
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="my-modal-staff"></div>

<style>
    .modal {
        overflow-y: auto !important;
    }
</style>
<script>
    function changeParentTask() {
        var parentId = $('#parent_id').val();
        var is_parent_number = $('#form-work-detail #is_parent_number').val();
        if (is_parent_number == 0) {
            if (parentId != '') {
                $('.disabled-parent-text').val(' ');
                $('.disabled-parent-select').val($(".disabled-parent-select option:first").val());
                $('.select-parent').prop('checked', true);
                $('.disabled-parent').prop('disabled', true);
                $('.disabled-parent-text').prop('disabled', true);
                $('.disabled-parent-select').prop('disabled', true);
            } else {
                $('.disabled-parent').prop('disabled', false);
                $('.disabled-parent-select').prop('disabled', false);
                $('.disabled-parent-text').prop('disabled', false);
            }
        }

        // if($('#popup-work #parent_id').val() != '')
        // {
        //     $.ajax({
        //         url: laroute.route('manager-work.change-parent-task'),
        //         data: {
        //             parent_id : $('#popup-work #parent_id').val()
        //         },
        //         method: "POST",
        //         dataType: "JSON",
        //         success: function(res) {
        //             if (res.error == false) {
        //                 $("#popup-work select[name='manage_type_work_id']").val(res.manage_type_work_id).change();
        //                 $('#popup-work select[name="manage_project_id"]').val(res.manage_project_id).change();
        //             }
        //         },
        //     });
        // }
    }

    function changeBranch() {
        var branch_id = $('#branch_id_search').val();
        console.log(laroute.route('manager-work.detail.submit-change-folder'));
        $.ajax({
            url: laroute.route('manager-work.detail.change-branch-staff'),
            method: 'POST',
            data: {
                branch_id: branch_id
            },
            success: function (res) {
                if (res.error == false) {
                    $('#support').empty();
                    $('#support').append(res.view);
                }
            },

        });
    }

    function changeListStaff(obj) {
        $.ajax({
            url: laroute.route('manager-work.change-list-staff'),
            method: 'POST',
            data: {
                manage_project_id: $(obj).val()
            },
            success: function (res) {
                if (res.error == false) {
                    $('#popup-work select[name="processor_id"]').empty();
                    $('#popup-work select[name="processor_id"]').append(res.view);

                    $('#popup-work select[name="approve_id"]').empty();
                    $('#popup-work select[name="approve_id"]').append(res.view);

                    // $('#popup-work #parent_id').empty();
                    // $('#popup-work #parent_id').append(res.viewWork);
                    // $('#popup-work #parent_id').selectpicker('refresh');

                    $('#parent_id').val('').trigger('change');

                    $('#support').empty();
                    $('#popup_manage_project_phase_id').empty();
                    $('#support').append(res.viewGroup);
                    $('#popup_manage_project_phase_id').append(res.viewPhase);
                    $('#support').selectpicker('refresh');
                }
            },

        });
    }

    $(document).ready(function () {
        changeParentTask();
    })
</script>

<script type="text/template" id="imageShowWork">
    <div class="image-show-work col-12">
        <span class="delete-img-document">
            <a href="javascript:void(0)" onclick="DocumentWork.removeImage(this)">
                <i class="fas fa-trash-alt"></i>
            </a>
        </span>
        <p class="img-fluid d-inline-block">{link_work}</p>
        <input type="hidden" class="path_work" name="file[{n}][path_work]" value="{link_work}">
        <input type="hidden" class="file_name_work" name="file[{n}][file_name_work]" value="{file_name_work}">
        <input type="hidden" class="file_type_work" name="file[{n}][file_type_work]" value="image">

    </div>
</script>
<script type="text/template" id="imageShowFileWork">
    <div class="image-show-work col-12">
        <span class="delete-img-document d-inline-block">
            <a href="javascript:void(0)" onclick="DocumentWork.removeImage(this)">
                <i class="fas fa-trash-alt"></i>
            </a>
        </span>
        <p class="d-inline-block">{{asset('static/backend/images/document.png')}}</p>
        <input type="hidden" class="file_name_work" name="file[{n}][file_name_work]" value="{file_name_work}">
        <input type="hidden" class="file_type_work" name="file[{n}][file_type_work]" value="file_work">
        <input type="hidden" class="path_work" name="file[{n}][path_work]" value="{link_work}">

    </div>
</script>

<script type="text/template" id="detail-customer-tpl">
    <a class="btn btn-sm m-btn--icon bg-light color" href="{url}" target="_blank">
            <span>
                <span class="la la-eye" aria-hidden="true"></span>
                @lang('Xem chi tiết')
            </span>
    </a>
</script>

<script>
    $('select[name="type_card_work"]').change(function () {
        if ($('select[name="type_card_work"]').val() == 'kpi') {
            $('#is_approve_id').prop('checked', true);
            $('#is_approve_id').prop('disabled', true);
            WorkChild.approveStaff();
        } else {
            $('#is_approve_id').prop('disabled', false);
        }
    });
</script>


<script type="text/template" id="staff-support-tpl">
    <span class="mr-1 mb-1 span_parent_close">
        {staff_name}
        <a class="close" href="javascript:void(0)" onclick="WorkChild.removeStaffSupport(this, '{staff_id}')">x</a>
        <input type="hidden" name="support[]" value="{staff_id}">
    </span>
</script>
