@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ NHÂN VIÊN')}}</span>
@stop

@section('content')
    <style>
        .form-control-feedback {
            color: #ff0000;
        }

        input[type=file] {
            padding: 10px;
            background: #fff;
        }

        .m-widget5 .m-widget5__item .m-widget5__pic > img {
            width: 100%
        }

        .m-image {
            /*padding: 5px;*/
            max-width: 155px;
            max-height: 155px;
            background: #ccc;
        }
    </style>
    @include('timeoffdays::timeoffdays.pop.modal-image')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('CẬP NHẬT ĐƠN PHÉP')}}
                    </h2>
                </div>
            </div>
   
        </div>
        <form id="form-edit">
            <input type="hidden" name="time_off_days_id" id="time_off_days_id" value="{{ $data['time_off_days_id'] }}">
            <div class="m-portlet__body">
                {!! csrf_field() !!}
                <div class="row">
                    <div class="col-lg-10">
                        <div class="row clearfix">
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <label class="black-title">
                                        {{__('Loại đơn')}}: <span class="required"><b class="text-danger">*</b></span>
                                    </label>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <select id="time_off_type_id" name="time_off_type_id" class="form-control op_day width-select" title="{{__('Loại đơn')}}"
                                                    name="day">
                                                <option>Chọn loại đơn phép</option>
                                                @if($timeOffTypeList)
                                                    @foreach ($timeOffTypeList as $key => $item)
                                                        <option @if($data['time_off_type_id'] == $item['time_off_type_id']) selected="selected" @endif value="{{$item['time_off_type_id']}}">{{$item['time_off_type_name']}}</option>
                                                    @endforeach
                                                @endif
                                            </select>

                                            @if ($errors->has('time_off_type_id'))
                                                <span class="form-control-feedback">
                                                    {{ $errors->first('time_off_type_id') }}
                                                </span>
                                                <br>
                                            @endif
                                        </div>
                                        <input type="hidden" value="" name="time_off_type_code" id="time_off_type_code">
                                    </div>
                                    <span class="error_time_off_days_type_id" style="color: #ff0000"></span>
                                </div>

                                <div class="form-group m-form__group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button id="motngay" type="button" class="{{ $data['date_type_select'] == 'one-day' ? 'active' : '' }} btn-date btn btn-outline-primary color">Một ngày</button>
                                            <button id="nhieungay" type="button" class="{{ $data['date_type_select'] == 'multi-day' ? 'active' : '' }} btn btn-date  btn-outline-primary color">Nhiều ngày</button>
                                            <button id="buoisang" type="button" class="{{ $data['date_type_select'] == 'morning' ? 'active' : '' }} btn btn-date  btn-outline-primary color">Buổi sáng</button>
                                            <button id="buoichieu" type="button" class="{{ $data['date_type_select'] == 'afternoon' ? 'active' : '' }} btn btn-date  btn-outline-primary color">Buổi chiều</button>
                                        </div>
                                    </div>
                                    <input type="hidden" value="{{ $data['date_type_select']}}" name="select_type_date" id="select_type_date">
                                </div>

                                <div class="form-group m-form__group">
                                   
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label class="black-title">
                                                {{__('Chọn ngày')}}: <span class="required"><b class="text-danger">*</b></span>
                                            </label>
                                            <div class="input-group date" id="date_start">
                                                <input type="text" value="{{$data['time_off_days_start'] ?? ''}}" name="time_off_days_start" class="form-control m-input" readonly="" placeholder="{{ __('Ngày bắt đầu') }}" id="start_date">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="error-start-date"></span>
                                        </div>
                                        <div class="col-lg-6" style="display: {{ $data['date_type_select'] == 'multi-day' ? 'none' : 'block' }}">
                                            <div id="date_end">
                                                <label class="black-title">
                                                    {{__('Chọn ngày')}}: <span class="required"><b class="text-danger">*</b></span>
                                                </label>
                                                <div class="input-group date">
                                                    <input type="text" value="{{$data['time_off_days_end'] ?? ''}}" name="time_off_days_end" class="form-control m-input daterange-picker" readonly="" placeholder="{{ __('Ngày kết thúc') }}"  id="end_date">
                                                    <div class="input-group-append">
                                                        <span class="input-group-text">
                                                            <i class="la la-calendar-check-o"></i>
                                                        </span>
                                                    </div>
                                                </div>
                                                <span class="error-end-date"></span>
                                            </div>
                                            <div id="date_time">
                                                <label class="black-title">
                                                    {{__('Thời gian trễ')}}: <span class="required"><b class="text-danger">*</b></span>
                                                </label>
                                                <div class="input-group">
                                                    <select id="time_off_days_time" name="time_off_days_time" class="form-control op_day width-select" title="{{__('Loại đơn')}}"
                                                        name="day">
                                                        <option value="0">Chọn thời gian</option>
                                                        @foreach ($daysOffTime as $objDaysOffTime)
                                                        <option value="{{$objDaysOffTime['time_off_days_time_value']}}" {{$objDaysOffTime['time_off_days_time_value'] == $data['time_off_days_time'] ? 'selected' : ''}}>
                                                            {{$objDaysOffTime['time_off_days_time_value']}} @lang('phút')
                                                        </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <span class="error-days_timme"></span>
                                            </div>
                                        </div>
                               
                                    </div>
                                </div>

                                <div class="form-group" id="member-level-field">
                               
                                    <select class="form-control" id="time_off_days_shift" name="time_off_days_shift[]" multiple
                                            style="width:100%;">
                                        
                                    </select>

                                    @if ($errors->has('time_off_days_shift[]'))
                                        <span class="form-control-feedback">
                                            {{ $errors->first('time_off_days_shift[]') }}
                                        </span>
                                        <br>
                                    @endif
                                </div>


                                <div class="form-group m-form__group">
                      
                                    <div class="alert alert-secondary" role="alert">
                                        <ul id="total_day" class="" style="list-style-type: none; padding-left: 0px;">
                                            <li>Quý phép năm (2022): <span class="badge badge-pill badge-primary ">Không giới hạn</span></li>
                                            <li>Đã dùng: <span class="badge badge-pill badge-success">2 ngày</span></li>
                                            <li>Quý phép khả dụng: <span class="badge badge-pill badge-primary">Không giới hạn</span></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="form-group m-form__group">
                                    <label>
                                        {{__('Lưu ý')}}:
                                    </label>
                                    <div class="input-group m-input-group ">
                                        <ul id="note_day">
                                            <li>Áp dụng với các đơn vị có ca trực và nghĩ bù tương ứng</li>
                                            <li>Yêu cầu tạo đơn trước 6 tiếng so với giờ làm việc và phải được sự đồng ý của trưởng bộ phận và trưởng đơn vị trước khi thực hiện</li>
                                        </ul>
                                    </div>
                                </div>
                               
                 
                            </div>
                            <div class="col-lg-6">
                       

                                <div class="form-group m-form__group">
                                    <label>
                                        {{__('Lý do')}}:<span class="required"><b class="text-danger">*</b></span>
                                    </label>
                                    <div class="input-group m-input-group ">
                                        <textarea id="note" name="time_off_note" class="form-control autosizeme" rows="8"
                                                    placeholder="@lang("Nhập lý do")"
                                                    data-autosize-on="true"
                                                    style="overflow: hidden; overflow-wrap: break-word; resize: horizontal;">{{$data['time_off_note'] ?? ''}}</textarea>
                                    </div>
                                    @if ($errors->has('time_off_note'))
                                        <span class="form-control-feedback">
                                            {{ $errors->first('time_off_note') }}
                                        </span>
                                        <br>
                                    @endif

                                </div>

                                <div class="form-group m-form__group">
                                    <label>
                                        {{__('Hình ảnh')}}:
                                    </label>
                                    <div class="form-group m-form__group">
                                        <a href="javascript:void(0)" class="btn btn-sm m-btn m-btn--icon color"
                                           onclick="timeoffdays.modalImage()">
                                            <i class="fa fa-plus-circle"></i> @lang('Thêm hình ảnh')
                                        </a>
                                    </div>
                                    <div class="div_image_customer image-show row"></div>
                                </div>
                                
                                <div class="form-group m-form__group" id="lstStaffArpprove">
                                    <label>
                                        {{__('Người duyệt')}}:
                                    </label>
                                    <ul class="d-flex flex-row" style="list-style-type: none; padding-left: 0px;">
                                        @if($staffApprove)
                                        <?php $index = 0; ?>
                                        @foreach ($staffApprove as $key => $item)
                                            <?php $index++; ?>
                                            @if($item['staff_id'] != Auth()->id())
                                                <li class="d-flex flex-column align-items-center">
                                                    <img src="{{$item['staff_avatar']}}" onerror="if (this.src != '/static/backend/images/default-placeholder.png') this.src = '/static/backend/images/default-placeholder.png';" class="m--img-rounded m--marginless" alt="photo" width="50px" height="50px">    
                                                    <p class="font-weight-bold  mt-2">{{$item['full_name']}}</p>
                                                    <p>{{$item['staff_title']}}</p>
                                                </li>
                                                @if($index != count($staffApprove))
                                                    <li class="d-flex flex-column align-self-center color" style="padding-left: 20px; padding-right: 20px;">
                                                        <i class="fa fa-thin fa-arrow-right"></i>
                                                    </li>
                                                @endif
                                               
                                                <input type="hidden" value="{{$item['staff_id'] ?? 0}}" name="staff_id_level{{$index}}" id="staff_id_level{{$index}}"/>
                                            @endif
                                        @endforeach
                                    @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-portlet__foot">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('timeoffdays.index')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        <a style="color: #FFFFFF"
                                class="btn btn-edit btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add_close m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('LƯU THÔNG TIN')}}</span>
							</span>
                        </a>
             

                    </div>
                </div>
            </div>
        </form>
    </div>
@stop
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <style>
        .alert{
            background-color: #f9fafb;
        }
    </style>
@stop
@section('after_script')

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/timeoffdays/script.js?v='.time())}}" type="text/javascript"></script>    
    <script src="{{asset('static/backend/js/admin/customer/dropzone.js?v='.time())}}" type="text/javascript"></script> 
    <script>
        timeoffdays.dropzoneCustomer();
    </script>
    <script>
        timeoffdays._init();
        timeoffdays.total($('#time_off_type_id').val());
        $('#time_off_days_time').select2();
        $("#date_end").hide();
        $('#start_date, #end_date').datepicker({
            rtl: mUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
            autoclose: true,
            format: 'dd/mm/yyyy',
            startDate: new Date(),
            minDate: 0,
            
        }).datepicker("setDate", new Date()).on("change", function() {
            timeoffdays.listShift($('#start_date').val(), $('#end_date').val());
        });;

        $('#motngay').click(function () {
            $('.btn-date').removeClass('active');
            $(this).addClass('active');
            $("#date_end").hide();
            $('#end_date').datepicker('setDate', $('#start_date').val());
            $('#select_type_date').val('one-day');
        });
        $('#nhieungay').click(function () {
            $('.btn-date').removeClass('active');
            $(this).addClass('active');
            $("#date_end").show();
            $('#select_type_date').val('multi-day');
        });
        $('#buoisang').click(function () {
            $('.btn-date').removeClass('active');
            $(this).addClass('active');
            $("#date_end").hide();
            $('#select_type_date').val('morning');
        });
        $('#buoichieu').click(function () {
            $('.btn-date').removeClass('active');
            $(this).addClass('active');
            $("#date_end").hide();
            $('#select_type_date').val('afternoon');
        });

        $('#time_off_type_id').select2().on('change', function(){
            timeoffdays.total($(this).val());
            // timeoffdays.getListStaffApprove($(this).val());
        });

        timeoffdays.listShift($('#start_date').val(), $('#end_date').val());

    </script>
@stop
