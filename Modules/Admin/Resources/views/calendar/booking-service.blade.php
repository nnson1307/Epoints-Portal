@extends('layout')

@section('content')
    @php($width = 80 / count($HEADER))
    <style>
        .ddt .ddt-sp {
            max-width: 95%;
            max-height: 35px;
        }

        .ddt .ddt-sp img {
            height: 30px;
        }

        .ddt-th {
            font-weight: bold;
            display: block;
        }

        .ddt-ng {
            display: block;
        }

        .ddt th, .ddt td {
            text-align: center;
            width: {{$width != 80 ? $width : 0}}%;
        }

        .ddt .table-bordered th, .ddt .table-bordered td {
            border-color: #19afb7;
            vertical-align: middle;
        }

        .ddt table {
            border-collapse: separate;
            border-spacing: 0 10px;
        }

        .ddt-ite {
            position: relative;
        }

        .ddt-ite span {
            position: relative;
            z-index: 3;
        }

        .ddt-prg {
            position: absolute;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #4fc4ca;
            z-index: 2;
        }

        .ddt-cn {
            color: #f00;
        }

        .calendar_note {
            display: block;
            width: 17px;
            height: 17px;
            float: left;
            margin-right: 8px;
            border: 1px solid #4fc4ca;
        }

        .calendar_st_busy {
            background-color: #4fc4ca
        }

        .past {
            background-color: #dadbde
        }

        .a_click {
            display: block;
            width: 96%;
            height: 100%;
            z-index: 1;
            position: absolute;
            margin-left: 2%;
            top: 0;
            left: 0;
        }
    </style>
    <div class="text-center">
        <h3> @lang('Quản lý lịch cho thuê') </h3>
    </div>
{{--    <div class="row">--}}
{{--        <div class="col-3 col-md-2"><span class="calendar_note"></span> Ngày trống</div>--}}
{{--        <div class="col-3 col-md-2"><span class="calendar_note calendar_st_busy"></span> Ngày đã đặt</div>--}}
{{--    </div>--}}
    <p>&nbsp;</p>
    <div class="row checkScrollFix">
        <div class="col-9">
            <div class="row">
                <div class="btn-fillter col-12"><i class="fa fa-filter" aria-hidden="true"></i></div>
                <form class="form-inline">
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="inputPassword2" class="sr-only">Tên dịch vụ</label>
                        <input type="text" class="form-control" name="s" placeholder="Tên dịch vụ"
                               value="{{ $SEARCH }}">
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <label for="" class="sr-only">Tên dịch vụ</label>
                        <div class="input-group" style="width: 200px">
                            <select class="form-control" id="status" name="status"
                                    style="width:100%;">
                                <option value=""></option>
                                <option value="all" {{$STATUS_BOOKING != null && $STATUS_BOOKING == 'all' ? 'selected' : ''}}>@lang('Tất cả')</option>
                                <option value="empty" {{$STATUS_BOOKING != null && $STATUS_BOOKING == 'empty' ? 'selected' : ''}}>@lang('Còn trống')</option>
                                <option value="booked" {{$STATUS_BOOKING != null && $STATUS_BOOKING == 'booked' ? 'selected' : ''}}>@lang('Đã đặt')</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mx-sm-3 mb-2">
                        <div class="m-input-icon m-input-icon--right">
                            <input readonly class="form-control m-input date-picker"
                                   style="background-color: #fff" id="date_filter" name="date_filter" value="{{$DATE_BOOKING != null ? $DATE_BOOKING : Carbon\Carbon::now()->format('d/m/Y')}}"
                                   autocomplete="off" placeholder="@lang('Chọn ngày')">
                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary color_button mb-2">Tìm kiếm</button>
                    <a href="{{route('booking-calendar')}}" class="btn btn-primary color_button ml-3 mb-2">
                        <i class="la la-refresh"></i>
                    </a>
                </form>
            </div>
        </div>

        <div class="col-3">
            <div class="btn-group btn-group-sm float-right" role="group" aria-label="Basic example">
                <a href="?{{$PREV_LINK}}" class="btn btn-info"><i class="fas fa-chevron-circle-left"></i></a>
                <a href="?{{$NEXT_LINK}}" class="btn btn-info"><i class="fas fa-chevron-circle-right"></i></a>
            </div>
        </div>
    </div>

    <div class="row ddt">
        <div class="table-responsive-lg col-12">
            <table width="100%" class="table table-bordered">
                <thead>
                <tr>
                    <th style="width: 18%">@lang('Dịch vụ')</th>
                    @foreach($HEADER as $i => $date)
                        @php($now = Carbon\Carbon::createFromFormat('Y-m-d', $date))
                        @php($thu = getThu($now))
                        <th class="{{ $thu == 'CN' ? 'ddt-cn' : '' }}">
                            <span class="ddt-th">{{ $thu }}</span>
                            <span class="ddt-ng">{{ $now->format('d/m') }}</span>
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach($SERVICES as $service)
                    <tr>
                        <td>
                            <div class="row">
                                <div class="col-lg-9 text-left">
                                    <a href="javascript:void(0)"
                                       onclick="customer_appointment.clickDetailObject('{{$service->service_id}}', '{{$HEADER[0]}}', '{{$HEADER[count($HEADER) - 1]}}')">
                                        <span>{{ $service->service_name }}</span>
                                    </a>
                                </div>
                                <div class="col-lg-3">
                                    @if($service->service_avatar != null)
                                        <img class="ddt-sp" src="{{ $service->service_avatar }}"
                                             alt="{{ $service->service_name }}"
                                             title="{{ $service->service_name }}">
                                    @else
                                        <img class="ddt-sp" src="{{asset('static/backend/images/logo-epoints.png')}}"
                                             alt="{{asset('static/backend/images/logo-epoints.png')}}">
                                    @endif
                                </div>
                            </div>
                        </td>
                        @foreach($service['price_list'] as $date => $price)
                            @php($now = Carbon\Carbon::now()->format('Y-m-d'))
                            @php($dateCheck = Carbon\Carbon::createFromFormat('Y-m-d', $date))
                            <td class="ddt-ite {{ $dateCheck < $now ? 'past' : '' }}">
                                <span>{{ $price['price'] / 1000 }}k</span>

                                @if(isset($service['booking_date'][$date]))
                                    @foreach($service['booking_date'][$date]['parts'] as $book)
                                        @php($calb = calcBusyCalendar($book['start_time'], $book['end_time']))
                                        <div class="ddt-prg booked_st_{{$book['status']}}"
                                             data-busy="{{ $calb['process'] }}"
                                             data-left="{{ $calb['left'] }}">
                                        </div>
                                    @endforeach
                                @endif

                                @if ($dateCheck > $now)
                                    <a href="javascript:void(0)" class="a_click"
                                       onclick="customer_appointment.clickDay('{{$dateCheck->format('Y-m-d')}}', '{{$service->service_id}}')">
                                    </a>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div id="show-modal"></div>
@stop
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop

@section('after_script')
    <script>
        $('.ddt-prg').each(function () {
            var progress = $(this).data('busy');
            var left = $(this).data('left');

            if (typeof progress != 'undefined') {
                $(this).css({'width': progress + '%', left: left + '%'})
            }
        });
    </script>
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>
    <script src="{{asset('static/backend/js/admin/calendar/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        customer_appointment._init()
    </script>
    <script type="text/template" id="append-status-other-tpl">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-info  color_button active" id="new"
                   onclick="customer_appointment.new_click()">
                <input type="radio" name="status" id="option1" value="new"
                       autocomplete="off" checked=""> {{__('MỚI')}}
            </label>
            <label class="btn btn-default" id="confirm"
                   onclick="customer_appointment.confirm_click()">
                <input type="radio" name="status" id="option2" value="confirm"
                       autocomplete="off"> {{__('XÁC NHẬN')}}
            </label>
            <label class="btn btn-default" id="processing"
                   onclick="customer_appointment.processing_click()">
                <input type="radio" name="status" id="option2" value="processing"
                       autocomplete="off"> {{__('ĐANG THỰC HIỆN')}}
            </label>
        </div>
    </script>
    <script type="text/template" id="append-status-live-tpl">
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-info color_button active" id="wait">
                <input type="radio" name="status" id="option1" value="wait"
                       autocomplete="off" checked=""> {{__('CHỜ PHỤC VỤ')}}
            </label>
        </div>
    </script>
    <script type="text/template" id="table-card-tpl">
        <tr class="tr_quantity tr_card">
            <td>{name}
                <input type="hidden" name="customer_order" id="customer_order_{stt}" value="{stt}">
                <input type="hidden" name="object_type" id="object_type" value="{type}">
            </td>
            <td>
                <select class="form-control service_id" name="service_id" id="service_id_{stt}"
                        style="width: 100%" multiple="multiple">
                    <option></option>
                </select>
            </td>
            <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                <select class="form-control staff_id" name="staff_id" id="staff_id_{stt}"
                        title="{{__('Chọn nhân viên phục vụ')}}" style="width: 100%" disabled>
                    <option></option>
                </select>
            </td>
            <td style="{{session()->get('brand_code') != 'giakhang' ? '': 'display:none;'}}">
                <select class="form-control room_id" name="room_id" id="room_id_{stt}"
                        title="{{__('Chọn phòng')}}" style="width: 100%" disabled>
                    <option></option>
                </select>
            </td>
        </tr>
    </script>
    <script type="text/template" id="to-date-tpl">
        @if($configToDate == 1)
        <div class="form-group m-form__group row">
            <div class="form-group col-lg-6">
                <label class="black-title">{{__('Ngày kết thúc')}}:<b
                            class="text-danger">*</b></label>
                <div class="input-group">
                    <div class="m-input-icon m-input-icon--right">
                        <input class="form-control m-input" name="end_date"
                               id="end_date"
                               readonly
                               placeholder="{{__('Chọn ngày hẹn')}}" type="text"
                               value="">
                        <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                        class="la la-calendar"></i></span></span>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group col-lg-6">
                <label class="black-title">{{__('Giờ kết thúc')}}:<b
                            class="text-danger">*</b></label>
                <div class="input-group m-input-group">
                    <input id="end_time" name="end_time" class="form-control"
                           placeholder="{{__('Chọn giờ hẹn')}}" readonly>
                </div>
            </div>
        </div>
        @endif
    </script>
    <script type="text/template" id="w-m-y-tpl">
        <div class="form-group m-form__group">
            <label class="black-title">{{__('Số tuần/tháng/năm')}}:<b class="text-danger">*</b></label>
            <input class="form-control" id="type_number" name="type_number" value="1"
                   onchange="customer_appointment.changeNumberTime()">
        </div>
        @if($configToDate == 1)
        <div class="form-group m-form__group row">
            <div class="form-group col-lg-6">
                <label class="black-title">{{__('Ngày kết thúc')}}:<b
                            class="text-danger">*</b></label>
                <div class="input-group">
                    <div class="m-input-icon m-input-icon--right">
                        <input class="form-control m-input" name="end_date"
                               id="end_date"
                               readonly
                               placeholder="{{__('Chọn ngày hẹn')}}" type="text"
                               value="">
                        <span class="m-input-icon__icon m-input-icon__icon--right"><span><i
                                        class="la la-calendar"></i></span></span>
                    </div>
                </div>
            </div>
            <div class="form-group m-form__group col-lg-6">
                <label class="black-title">{{__('Giờ kết thúc')}}:<b
                            class="text-danger">*</b></label>
                <div class="input-group m-input-group">
                    <input id="end_time" name="end_time" class="form-control"
                           placeholder="{{__('Chọn giờ hẹn')}}" readonly>
                </div>
            </div>
        </div>
        @endif
    </script>
@stop