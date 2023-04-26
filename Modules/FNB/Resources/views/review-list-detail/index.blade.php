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
    <div class="m-portlet" >
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
            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="m-form m-form--label-align-right">
                        <div class="row ss--bao-filter">
                            <div class="col-lg-3 form-group">
                                <div class=" m-form__group">
                                    <div class="input-group">
                                        <input type="text" class="form-control m-input" id="name" name="name" placeholder="{{__('Nhập tên đánh giá')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-3 form-group">
                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <select class="form-control select2 m-input" name="review_list_id">
                                            <option value="">{{__('Chọn cấp độ đánh giá')}}</option>
                                            @foreach($listReview as $key => $val)
                                                <option value="{{$val['review_list_id']}}" {{isset($input['review_list_id']) && $input['review_list_id'] == $val['review_list_id'] ? 'selected' : ''}}>{{$val['name']}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 form-group">
                                <div class="">
                                    <a href="{{route('fnb.review-list-detail')}}"
                                       class="btn btn-refresh btn-primary color_button m-btn--icon" style="color: #fff">
                                        {{ __('XÓA BỘ LỌC') }}
                                        <i class="fa fa-eraser" aria-hidden="true"></i>
                                    </a>
                                    <button
                                            class="btn ss--btn-search">
                                        {{__('TÌM KIẾM')}}
                                        <i class="fa fa-search ss--icon-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-content m--padding-top-30">
                    @include('fnb::review-list-detail.list')
                </div>
            </div>
            <!-- end table-content -->
        </div>
    </div>
    <div class="append-popup"></div>

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/fnb/review-list-detail/script.js?v='.time())}}"></script>
    <script>
        requestListDetail._init();
    </script>
@stop
