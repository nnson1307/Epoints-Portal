{{--{{dd(old("product_id[]"))}}--}}
@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ NHÂN VIÊN')}}</span>
@endsection
@section('content')
    <style>
        .form-control-feedback {
            color: #ff0000;
        }
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-edit"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHỈNH SỬA LỊCH LÀM VIỆC')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>

        <div class="m-portlet__body">
            <form id="form-edit">
                <div class="form-group row">
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>@lang('Tên lịch làm việc'):<span class="required"><b class="text-danger">*</b></span></label>
                            <input class="form-control" type="text" id="work_schedule_name" name="work_schedule_name"
                                   placeholder="@lang("Nhập tên lịch làm việc")"
                                   value="{{$info['work_schedule_name']}}">
                        </div>
                        <div class="form-group">
                            <label>@lang('Hình thức lặp lại'):<span class="required"><b class="text-danger">*</b></span></label>

                            <div class="m-radio-inline">
                                <label class="m-radio m-radio--bold m-radio--state-success">
                                    <input type="radio" name="repeat"
                                           value="hard" {{$info['repeat'] == 'hard' ? 'checked': ''}}> @lang('Cố định')
                                    <span></span>
                                </label>
                                {{--<label class="m-radio m-radio--bold m-radio--state-success">--}}
                                {{--<input type="radio" name="repeat" value="monthly" {{$info['repeat'] == 'monthly' ? 'checked': ''}}> @lang('Hàng tháng')--}}
                                {{--<span></span>--}}
                                {{--</label>--}}
                            </div>
                        </div>
                        <div class="form-group">
                            <label>@lang('Ngày bắt đầu phân ca'):<span class="required"><b
                                            class="text-danger">*</b></span></label>

                            <div class="input-group date">
                                <input type="text" class="form-control m-input" readonly=""
                                       placeholder="@lang('Chọn ngày')"
                                       id="start_day_shift" name="start_day_shift"
                                       {{\Carbon\Carbon::parse($info['start_day_shift'])->format('Y-m-d') <= \Carbon\Carbon::now()->format('Y-m-d') ? 'disabled': ''}}
                                       value="{{\Carbon\Carbon::createFromFormat('Y-m-d', $info['start_day_shift'])->format('d/m/Y')}}">
                                <div class="input-group-append">
                                <span class="input-group-text"><i
                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label>@lang('Ghi chú'):</label>
                            <textarea class="form-control" id="note" name="note" rows="5"
                                      placeholder="@lang('Nhập ghi chú')">{{$info['note']}}</textarea>
                        </div>
                        <div class="form-group"></div>
                        <div class="form-group">
                            <label>@lang('Ngày kết thúc phân ca'):<span class="required"><b
                                            class="text-danger">*</b></span></label>

                            <div class="input-group date">
                                <input type="text" class="form-control m-input" readonly=""
                                       placeholder="@lang('Chọn ngày')"
                                       id="end_day_shift" name="end_day_shift"
                                       value="{{\Carbon\Carbon::createFromFormat('Y-m-d', $info['end_day_shift'])->format('d/m/Y')}}">
                                <div class="input-group-append">
                                <span class="input-group-text"><i
                                            class="la la-calendar-check-o glyphicon-th"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>

            <div class="list_shift">
                @if (count($mapShift) > 0)
                    @foreach($mapShift as $keyShift => $shift)
                        <div class="object_shift">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-6 form-group">
                                            <label>@lang('Chọn ca làm việc'):<span class="required"><b
                                                            class="text-danger">*</b></span></label>

                                            <div class="input-group">
                                                <select class="form-control shift_id"
                                                        style="width:100%;">
                                                    <option></option>
                                                    @foreach($optionShift as $v)
                                                        <option value="{{$v['shift_id']}}" {{$shift['shift_id'] == $v['shift_id'] ? 'selected': ''}}>{{$v['shift_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="error_shift_id color_red"></span>
                                        </div>
                                        <div class="col-lg-6 form-group">
                                            <label>@lang('Vị trí làm việc'):<span class="required"><b
                                                            class="text-danger">*</b></span></label>

                                            <div class="input-group">
                                                <select class="form-control branch_id"
                                                        style="width:100%;">
                                                    <option></option>
                                                    @foreach($shift['branch'] as $v)
                                                        <option value="{{$v['branch_id']}}" {{$shift['branch_id'] == $v['branch_id'] ? 'selected': ''}}>{{$v['branch_name']}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <span class="error_branch_id color_red"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="row">
                                        <div class="col-lg-10 form-group">
                                            <div class="m-form__group form-group">
                                                <label for=""></label>
                                                <div class="m-checkbox-inline">
                                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                                        <input type="checkbox" class="is_monday"
                                                               {{$shift['is_monday'] == 1 ? 'checked': ''}} disabled> @lang('Thứ 2')
                                                        <span></span>
                                                    </label>
                                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                                        <input type="checkbox" class="is_tuesday"
                                                               {{$shift['is_tuesday'] == 1 ? 'checked': ''}} disabled> @lang('Thứ 3')
                                                        <span></span>
                                                    </label>
                                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                                        <input type="checkbox" class="is_wednesday"
                                                               {{$shift['is_wednesday'] == 1 ? 'checked': ''}} disabled> @lang('Thứ 4')
                                                        <span></span>
                                                    </label>
                                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                                        <input type="checkbox" class="is_thursday"
                                                               {{$shift['is_thursday'] == 1 ? 'checked': ''}} disabled> @lang('Thứ 5')
                                                        <span></span>
                                                    </label>
                                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                                        <input type="checkbox" class="is_friday"
                                                               {{$shift['is_friday'] == 1 ? 'checked': ''}} disabled> @lang('Thứ 6')
                                                        <span></span>
                                                    </label>
                                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                                        <input type="checkbox" class="is_saturday"
                                                               {{$shift['is_saturday'] == 1 ? 'checked': ''}} disabled> @lang('Thứ 7')
                                                        <span></span>
                                                    </label>
                                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                                        <input type="checkbox" class="is_sunday"
                                                               {{$shift['is_sunday'] == 1 ? 'checked': ''}} disabled> @lang('Chủ nhật')
                                                        <span></span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 form-group">
                                            @if ($keyShift > 0)
                                                <a href="javascript:void(0)" onclick="view.removeObjectShift(this)"
                                                   class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                                                   title="@lang('Xoá')">
                                                    <i class="la la-trash"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                <input type="checkbox"
                                       class="is_ot" {{$shift['is_ot'] == 1 ? 'checked': ''}}> @lang('Tăng ca')
                                <span></span>
                            </label>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="form-group">
                <a href="javascript:void(0)" onclick="view.addObjectShift()"
                   class="btn  btn-sm m-btn--icon color">
                        <span>
                            <i class="la la-plus"></i>
                            <span> @lang('Thêm lịch làm')</span>
                        </span>
                </a>
            </div>
            <div class="m-separator m-separator--dashed m-separator--lg"></div>

            <div id="autotable-staff">
                <div class="form-group padding_row bg">
                    <form class="frmFilter">
                        <div class="row">
                            @php $i = 0; @endphp
                            @foreach ($FILTER as $name => $item)
                                @if ($i > 0 && ($i % 3 == 0))
                        </div>
                        <div class="form-group m-form__group row align-items-center">
                            @endif
                            @php $i++; @endphp
                            <div class="col-lg-3 form-group input-group">
                                @if(isset($item['text']))
                                    <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $item['text'] }}
                                        </span>
                                    </div>
                                @endif
                                {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
                            </div>
                            @endforeach

                            <div class="col-lg-2 form-group">
                                <button class="btn btn-primary color_button btn-search" style="display: block">
                                    @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="form-group">
                    <a href="javascript:void(0)" onclick="view.showModalStaff()"
                       class="btn  btn-sm m-btn--icon color">
                        <span>
                            <i class="la la-plus"></i>
                            <span> @lang('Thêm nhân viên')</span>
                        </span>
                    </a>
                </div>

                <div class="table-content m--margin-top-30">

                </div>
            </div>


        </div>
        <div class="modal-footer">
            <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('shift.work-schedule')}}"
                       class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                    </a>
                    <button type="button" onclick="edit.save('{{$info['work_schedule_id']}}')"
                            class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('LƯU THÔNG TIN')</span>
                        </span>
                    </button>
                </div>
            </div>
        </div>
        <input type="hidden" value="0" id="type_add" name="type_add">
    </div>
    <div id="my-modal"></div>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/shift/work-schedule/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        view._init('edit');

        @if (count($mapStaff) > 0)
        $('#autotable-staff').PioTable({
            baseUrl: laroute.route('shift.work-schedule.list-staff')
        });

        $('.btn-search').trigger('click');
        @endif
    </script>

    <script type="text/template" id="shift-tpl">
        <div class="object_shift">
            <div class="row">
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-6 form-group">
                            <label>@lang('Chọn ca làm việc'):<span class="required"><b
                                            class="text-danger">*</b></span></label>

                            <div class="input-group">
                                <select class="form-control shift_id"
                                        style="width:100%;" onchange="view.changeShift(this)">
                                    <option></option>
                                    @foreach($optionShift as $v)
                                        <option value="{{$v['shift_id']}}">{{$v['shift_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <span class="error_shift_id color_red"></span>
                        </div>
                        <div class="col-lg-6 form-group">
                            <label>@lang('Vị trí làm việc'):<span class="required"><b
                                            class="text-danger">*</b></span></label>

                            <div class="input-group">
                                <select class="form-control branch_id" style="width:100%;">

                                </select>
                            </div>
                            <span class="error_branch_id color_red"></span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="row">
                        <div class="col-lg-10 form-group">
                            <div class="m-form__group form-group">
                                <label for=""></label>
                                <div class="m-checkbox-inline">
                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                        <input type="checkbox" class="is_monday" disabled> @lang('Thứ 2')
                                        <span></span>
                                    </label>
                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                        <input type="checkbox" class="is_tuesday" disabled> @lang('Thứ 3')
                                        <span></span>
                                    </label>
                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                        <input type="checkbox" class="is_wednesday" disabled> @lang('Thứ 4')
                                        <span></span>
                                    </label>
                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                        <input type="checkbox" class="is_thursday" disabled> @lang('Thứ 5')
                                        <span></span>
                                    </label>
                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                        <input type="checkbox" class="is_friday" disabled> @lang('Thứ 6')
                                        <span></span>
                                    </label>
                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                        <input type="checkbox" class="is_saturday" disabled> @lang('Thứ 7')
                                        <span></span>
                                    </label>
                                    <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                                        <input type="checkbox" class="is_sunday" disabled> @lang('Chủ nhật')
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-2 form-group">
                            <a href="javascript:void(0)" onclick="view.removeObjectShift(this)"
                               class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill"
                               title="@lang('Xoá')">
                                <i class="la la-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <label class="m-checkbox m-checkbox--solid m-checkbox--state-success">
                <input type="checkbox" class="is_ot"> @lang('Tăng ca')
                <span></span>
            </label>
        </div>
    </script>
@stop
