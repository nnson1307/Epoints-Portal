@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt=""
                                    style="height: 20px;">
        {{ __('Quản lý đánh giá') }}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/todh.css')}}">
    <style>
        .modal .select2.select2-container,
        .select2-search__field {
            width: 100% !important;
        }

        .timepicker {
            border: 1px solid rgb(163, 175, 251);
            text-align: center;
            /* display: inline; */
            border-radius: 4px;
            padding: 2px;
            height: 38px;
            line-height: 30px;
            width: 130px;
        }

        .timepicker .hh, .timepicker .mm {
            width: 50px;
            outline: none;
            border: none;
            text-align: center;
        }

        .timepicker.valid {
            border: solid 1px springgreen;
        }

        .timepicker.invalid {
            border: solid 1px red;
        }

        .bg-white {
            background-color: #fff !important;
        }

        .custom-remind-item {
            color: #575962 !important;
            border: 1px solid #4bb072 !important;
            position: relative;
        }

        .custom-remind-item strong {
            height: 100%;
            display: flex;
            align-items: center;
        }

        .custom-remind-item button {
            color: #575962 !important;
        }

        .custom-remind-item::before {
            content: '';
            position: absolute;
            left: -1px;
            background: #79cca8;
            width: 9px;
            height: calc(100% + 2px);
            top: -1px;
            /* border-radius: 0px 5px 5px 0px; */
            border-radius: 5px;
            border-top-right-radius: 0px;
            border-bottom-right-radius: 0px;
        }

        .modal .modal-content .modal-body-config {
            padding: 25px;
            max-height: 400px;
            overflow-y: scroll;
        }

        .weekDays-selector input {
            display: none !important;
        }

        .weekDays-selector input[type=checkbox] + label {
            display: inline-block;
            border-radius: 6px;
            background: #dddddd;
            height: 40px;
            width: 30px;
            margin-right: 3px;
            line-height: 40px;
            text-align: center;
            cursor: pointer;
        }

        .weekDays-selector input[type=checkbox]:checked + label {
            background: #2AD705;
            color: #ffffff;
        }

        .table-content-font-a a {
            font-size: 1rem;
        }
        .areaa{
            background-color: #0067AC;
            font-weight: bold;
            font-size:20px;
            padding: 4px 20px;
            margin-left: 20px;
        }
    </style>
@endsection
@section('content')
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                    </span>
                    <h3 class="m-portlet__head-text">
                        {{ __('Quản lý đánh giá') }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            @include('fnb::append.tab-header-review')
            <form class="frmFilter ss--background search-policy">
                <div class="row ss--bao-filter">
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <select class="form-control select2" name="table_id">
                                    <option value="">{{__('Chọn bàn')}}</option>
                                    @foreach($listTable as $key => $val)
                                        <option value="{{$val['table_id']}}" {{isset($input['table_id']) && $input['table_id'] == $val['table_id'] ? 'selected' : ''}}>{{$val['table_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <select class="form-control" id="status" name="status">
                                    <option value="">{{__('Chọn trạng thái')}}</option>
                                    <option value="new" {{isset($input['status']) && $input['status'] == 'new' ? 'selected' : ''}}>{{__('Mới')}}</option>
                                    <option value="processing" {{isset($input['status']) && $input['status'] == 'processing' ? 'selected' : ''}}>{{__('Đang thực hiện')}}</option>
                                    <option value="done" {{isset($input['status']) && $input['status'] == 'new' ? 'done' : ''}}>{{__('Hoàn thành')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <select class="form-control" id="payment" name="payment">
                                    <option value="">{{__('Chọn phương thức thanh toán')}}</option>
                                    @foreach($listPaymentMethod as $key => $val)
                                        <option value="{{$val['payment_method_code']}}" {{isset($input['payment']) && $input['payment'] == $val['payment_method_code'] ? 'selected' : ''}}>{{$val['payment_method_name_vi']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="text" class="form-control daterange_picker" id="created_at" name="created_at" placeholder="{{__('Chọn ngày tạo')}}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body" style="padding-right: 15px;padding-top: 15px;padding-bottom: 12px">
                    <div class="text-right">
                        <a href="{{route('fnb.request')}}"
                           class="btn btn-refresh btn-primary color_button m-btn--icon" style="color: #fff">
                            {{ __('XÓA BỘ LỌC') }}
                            <i class="fa fa-eraser" aria-hidden="true"></i>
                        </a>
                        <button type="submit"
                                class="btn ss--btn-search1 color_button">
                            {{__('TÌM KIẾM')}}
                            <i class="fa fa-search ss--icon-search"></i>
                        </button>
                    </div>
                </div>
            </form>
            <div class="table-content table-content-font-a mt-3">
                @include('fnb::request.list')
            </div>
            <!-- end table-content -->
        </div>
    </div>
    <div class="append-popup"></div>

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/fnb/request/script.js?v='.time())}}"></script>
    <script>
        request._init();
    </script>
@stop
