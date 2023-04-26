@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt=""
            style="height: 20px;"> {{ __('QUẢN LÝ NHÂN VIÊN') }}</span>
@stop
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/son.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link href="assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
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

        .m-widget5 .m-widget5__item .m-widget5__pic>img {
            width: 100%
        }

        .m-image {
            /*padding: 5px;*/
            max-width: 155px;
            max-height: 155px;
            background: #ccc;
        }

        .button {
            background-color: #4CAF50;
            /* Green */
            border: none;
            color: white;
            padding: 15px 32px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            margin: 4px 2px;
            cursor: pointer;
        }
    </style>
    <form id="formadd">
        @if ($errors->any())
            <div class="'alert alert-danger text-center">
                @foreach ($errors->all() as $error)
                    <p>{{ $errors }}</p>
                @endforeach
        @endif
        <div class="m-portlet m-portlet--head-sm">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="fa fa-plus-circle"></i>
                        </span>
                        <h2 class="m-portlet__head-text">
                            {{ __('THÊM DỰ ÁN') }}
                        </h2>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                </div>
                <div class="d-flex">
                    <a href="http://piospa.com.dev.com/manager-work/project/list"
                        class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md align-self-center">
                        <span>
                            <i class="la la-arrow-left"></i>
                            <span>{{ __('HỦY') }}</span>
                        </span>
                    </a>
                    <button type="button" onclick="Project.submitAdd()"
                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add_close m--margin-left-10 align-self-center">
                        <span>
                            <i class="la la-check"></i>
                            <span>{{ __('LƯU THÔNG TIN') }}</span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="m-portlet m-portlet--head-sm">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <button type="submit"
                                class="btn btn-1 btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add_close m--margin-left-10 align-self-center"
                                style="margin-top: 8% ">
                                <span>
                                    <span>{{ __('Thông tin dự án') }}</span>
                                </span>
                            </button>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                    </div>
                </div>
            </div>

            <div class="col-lg-10">
                <div class="row clearfix">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black-title">{{ __('Tên dự án') }}:<b class="text-danger">*</b></label>
                            <input type="text" name="full_name" class="form-control m-input" id="full_name"
                                placeholder="{{ __('Placehoder') }}">
                        </div>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group"
                                        {{ $errors->has('staff_title_id') ? ' has-danger' : '' }}>
                                        <label class="black-title">{{ __('Người quản trị') }}:<b
                                                class="text-danger">*</b></label>

                                        <select name="staff_title_id" class="form-control ss--select-2" data-live-search="true"
                                            id="user_name" data-placeholder="{{ __('Placehoder') }}"
                                            style="width: 100% !important;">
                                            @foreach ($listStaff as $item)
                                                <option data-tokens="" value="{{ $item->staff_id }}">
                                                    {{ $item->full_name }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group"
                                        {{ $errors->has('staff_title_id') ? ' has-danger' : '' }}>
                                        <label class="black-title">{{ __('Phòng trực ban') }}:<b
                                                class="text-danger">*</b></label>
                                        <div class="input-group">
                                            <select name="staff_title_id" class="form-control ss--select-2" id='room_name'
                                            data-live-search="true"  data-placeholder="{{ __('Placehoder') }}">
                                                @foreach ($listDepartment as $item)
                                                    <option value="{{ $item->department_id }}">
                                                        {{ $item->department_name }}
                                                    </option>
                                                @endforeach


                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="form-group m-form__group">
                                <div class="d-flex w-100">
                                    <div class="form-group m-form__group w-50"
                                        {{ $errors->has('staff_title_id') ? ' has-danger' : '' }}>
                                        <label class="black-title">{{ __('Ngày bắt đầu hoạt động') }}:</label>
                                        <div class="input-group date date-multiple">

                                            <input name="date_start" id="date_start" class="form-control m-input date-input"
                                                type="text" name="date_start" placeholder="Ngày bắt đầu">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i
                                                        class="la la-calendar-check-o glyphicon-th"></i></span>
                                            </div>
                                        </div>
                                        <div>

                                        </div>
                                    </div>
                                    <div class="form-group m-form__group w-50" style="margin-left: 5%"
                                        {{ $errors->has('staff_title_id') ? ' has-danger' : '' }}>
                                        <label class="black-title">{{ __('Ngày kết thúc hoạt động') }}:</label>
                                        <div class="input-group date date-multiple">
                                            <input name="date_end" id="date_end" class="form-control m-input date-input"
                                                type="text" name="date_end" value=""
                                                placeholder="Ngày kết thúc">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i
                                                        class="la la-calendar-check-o glyphicon-th"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <div class="d-flex w-100">
                                        <div class="form-group m-form__group w-50"
                                            {{ $errors->has('staff_title_id') ? ' has-danger' : '' }}>
                                            <label class="black-title">{{ __('Loại khách hàng') }}:</label>
                                            <div class="input-group">
                                                <select name="staff_title_id" id="customer_name" data-live-search="true"
                                                    class="form-control ss--select-2">
                                                    <option value="staff">{{ __('Cá nhân') }}</option>
                                                    <option value="staff">{{ __('Doanh nghiệp') }}</option>


                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group m-form__group w-50" style="margin-left: 5%"
                                            {{ $errors->has('staff_title_id') ? ' has-danger' : '' }}>
                                            <label class="black-title">{{ __('Khách hàng') }}:</label>
                                            <div class="input-group">
                                                <select name="staff_title_id" id="custom_name" data-live-search="true"
                                                    class="form-control ss--select-2" >
                                                    @foreach ($listCustomer as $item)
                                                        <option value="{{ $item->customer_id }}">
                                                            {{ $item->customer_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group m-form__group">
                                        <label class="black-title">{{ __('Màu dự án') }}:<b
                                                class="text-danger">*</b></label>
                                        <input type="color" name="full_name" class="bfh-colorpicker"
                                            data-name="colorpicker1" id="color_name" value="#ff0000"
                                            placeholder="{{ __('Placehoder') }}">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black-title">{{ __('Quyền truy cập') }}:<b
                                        class="text-danger">*</b></label>
                                <div class="m-radio-inline">
                                    <label class="m-radio cus">
                                        <input type="radio" name="gender" value="male" checked="checked">
                                        {{ __('Nội bộ') }}
                                        <span></span>
                                    </label>
                                    <label class="m-radio cus" style="margin-left: 30%">
                                        <input type="radio" name="gender" value="female"> {{ __('Công khai') }}
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black-title">
                            </label>
                        </div>

                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group" style="margin-left: 10%">

                            <div class="input-group">

                            </div>

                            <div class="form-group m-form__group"
                                {{ $errors->has('staff_title_id') ? ' has-danger' : '' }}>
                                <label class="black-title">{{ __('Trạng thái dữ án') }}:<b
                                        class="text-danger">*</b></label>
                                <div class="input-group">
                                    <select name="staff_title_id" id="status_name" data-live-search="true"
                                                    class="form-control ss--select-2">
                                        @foreach ($listStatus as $item)
                                            <option value="{{ $item->manage_status_id }}">{{ $item->manage_status_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>


                                <div class="form-group m-form__group">

                                    <div class="input-group">

                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{ __('Tiền tố công việc') }}:</label>
                                    <div class="input-group">
                                        <select name="staff_type" id="task_name" class="form-control m-input">
                                            <option value="staff">{{ __('DA12') }}</option>

                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group m-form__group">
                                <label class="black-title">{{ __('Tags') }}:</label>
                                <div class="input-group">
                                    <select name="staff_title_id" id="tags_id" class="form-control m-input">
                                        @foreach ($listTags as $item)
                                            <option value="{{ $item->manage_tag_id }}">{{ $item->manage_tag_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group m-form__group">
                                <label class="black-title">{{ __('Mô tả dự án') }}:</label>
                                <textarea name="desc_nasme" id="desc_name" class="form-control m-input" style="display: none;"></textarea>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @csrf
    </form>
    </div>
@stop

@section('after_script')
    <script>
        $(document).ready(function() {
            $('#desc_name').summernote();
        });
    </script>

    <script src="{{ asset('static/backend/js/manager-project/project/main.js?v=' . time()) }}" type="text/javascript">
    <script src="{{ asset('static/backend/js/manager-project/project/list.js?v=' . time()) }}" type="text/javascript">
    </script>
    <script src="assets/plugins/global/plugins.bundle.js"></script>
@endsection
