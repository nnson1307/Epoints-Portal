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
                        {{__('THÊM ĐƠN PHÉP')}}
                    </h2>
                </div>
            </div>
   
        </div>
        <form id="form-create">
            <div class="m-portlet__body">
                {!! csrf_field() !!}
                <div class="row">
   
                    <div class="col-lg-10">
                        <div class="row clearfix">
                            <div class="col-lg-6">
                     
                                <div class="form-group m-form__group">
                                    <label for="example-password-input" class="col-3 col-form-label">
                                        {{__('Loại đơn')}}: <span class="required"><b class="text-danger">*</b></span>
                                    </label>
                                    <div class="col-9">
                                        <div class="input-group">
                                            <select id="time_off_type_id" name="time_off_type_id" class="form-control op_day width-select" title="{{__('Loại đơn')}}"
                                                    name="day">
                                                <option value="">Chọn loại đơn phép</option>
                                                @if($timeOffTypeList)
                                                    @foreach ($timeOffTypeList as $key => $item)
                                                        <option value="{{$item['time_off_type_id']}}">{{$item['time_off_type_name']}}</option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <input type="hidden" value="" name="time_off_type_code" id="time_off_type_code">
                                    </div>
                                   
                                </div>

                                <div class="form-group m-form__group">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <button id="motngay" type="button" class="active btn-date btn btn-outline-primary color">Một ngày</button>
                                            <button id="nhieungay" type="button" class="btn btn-date off-day btn-outline-primary color">Nhiều ngày</button>
                                            <button id="buoisang" type="button" class="btn btn-date  off-day  btn-outline-primary color">Buổi sáng</button>
                                            <button id="buoichieu" type="button" class="btn btn-date  off-day  btn-outline-primary color">Buổi chiều</button>
                                        </div>
                                    </div>
                                    <input type="hidden" value="one-day" name="select_type_date" id="select_type_date">
                                </div>

                                <div class="form-group m-form__group">
                                   
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <label class="black-title">
                                                {{__('Chọn ngày')}}: <span class="required"><b class="text-danger">*</b></span>
                                            </label>
                                            <div class="input-group date" id="date_start">
                                                <input type="text" name="time_off_days_start" class="form-control m-input" readonly="" placeholder="{{ __('Ngày bắt đầu') }}" id="start_date">
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        <i class="la la-calendar-check-o"></i>
                                                    </span>
                                                </div>
                                            </div>
                                            <span class="error-start-date"></span>
                                        </div>
                                        <div class="col-lg-6">
                                            <div id="date_end">
                                                <label class="black-title">
                                                    {{__('Chọn ngày')}}: <span class="required"><b class="text-danger">*</b></span>
                                                </label>
                                                <div class="input-group date">
                                                    <input type="text" name="time_off_days_end" class="form-control m-input daterange-picker" readonly="" placeholder="{{ __('Ngày kết thúc') }}"  id="end_date">
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
                                                <div class="input-group date">
                                                    <select id="time_off_days_time" name="time_off_days_time" class="form-control op_day width-select" title="{{__('Thời gian')}}"
                                                        name="day">
                                                        <option value="">Chọn thời gian</option>
                                                        @foreach ($daysOffTime as $objDaysOffTime)
                                                        <option value="{{$objDaysOffTime['time_off_days_time_value']}}">{{$objDaysOffTime['time_off_days_time_value']}} @lang('phút')</option>
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

                                <div class="form-group m-form__group day-off-info" style="display: none;">
                      
                                    <div class="alert alert-secondary" role="alert">
                                        <ul id="total_day" class="" style="list-style-type: none; padding-left: 0px;">
                                            <li>Quý phép năm (2022): <span class="badge badge-pill badge-primary ">Không giới hạn</span></li>
                                            <li>Đã dùng: <span class="badge badge-pill badge-success">2 ngày</span></li>
                                            <li>Quý phép khả dụng: <span class="badge badge-pill badge-primary">Không giới hạn</span></li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="form-group m-form__group day-off-info" style="display: none;">
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
                                                    style="overflow: hidden; overflow-wrap: break-word; resize: horizontal;"></textarea>
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
                        <a style="color: #FFFFFF" class="btn btn-add btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add_close m--margin-left-10">
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
        $('#time_off_days_time').select2();
        $("#date_end").hide();
        $("#date_time").hide();
        $('#start_date').datepicker({
            rtl: mUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
            autoclose: true,
            format: 'dd/mm/yyyy',
            startDate: new Date(),
            minDate: 0,
            
        }).datepicker("setDate", new Date()).on("change", function() {
            if($('#select_type_date').val() == 'one-day'){
                $('#end_date').datepicker('setDate', $('#start_date').val());
            }else {
                timeoffdays.listShift($('#start_date').val(), $('#end_date').val());
            }
           
        });
        var startDate = $('#start_date').val();
        var newdate = startDate.replaceAll("/", "-");
      
        $('#end_date').datepicker({
            rtl: mUtil.isRTL(),
            todayHighlight: true,
            orientation: "bottom left",
            autoclose: true,
            format: 'dd/mm/yyyy',
            startDate: newdate,
            minDate: 0,
             
        }).datepicker("setDate", new Date()).on("change", function() {
            
            timeoffdays.listShift($('#start_date').val(), $('#end_date').val());
        });;
        

        $('#motngay').click(function () {
            $('.btn-date').removeClass('active');
            $(this).addClass('active');
            $("#date_end").hide();
            $('#select_type_date').val('one-day');
        });
        $('#nhieungay').click(function () {
            $('.btn-date').removeClass('active');
            $(this).addClass('active');
            $("#date_end").show();
            $('#end_date').datepicker('setDate', $('#start_date').val());
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
    <script type="text/template" id="tpl-image">
        <div class="wrap-img image-show-child">
            <input type="hidden" name="img-link-customer" value="{imageLink}">
            <input type="hidden" name="img-name-customer" value="{imageName}">
            <input type="hidden" name="img-type-customer" value="{imageType}">

            <img class="m--bg-metal m-image img-sd " src="{imageLink}" alt="Hình ảnh" width="100px" height="100px">
            <span class="delete-img-sv" style="display: none;">
                <a href="javascript:void(0)" onclick="timeoffdays.removeImage(this)">
                    <i class="la la-close"></i>
                </a>
            </span>
        </div>
    </script>
@stop
