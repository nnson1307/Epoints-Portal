@extends('layout')

@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-member.png') }}" alt=""
            style="height: 20px;"> @lang('survey::survey.create.survey_manager')</span>
@stop
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('static/backend/css/son.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css?v='.time())}}">
    <style>
        .kt-radio.kt-radio--brand.kt-radio--bold>input:checked~span {
            border: 2px solid #000000 !important;
        }

        .kt-avatar.kt-avatar--circle .kt-avatar__holder {
            border-radius: 0% !important;
        }

        .ss--kt-avatar__upload {
            width: 20px !important;
            height: 20px !important;
        }

        .kt-checkbox.kt-checkbox--bold>input:checked~span {
            background: #4FC4CA;
            border: 2px solid #4FC4CA !important;
            border-radius: 3px !important;
        }

        .kt-checkbox>span:after {
            border: solid #fff;
        }

        .kt-radio.kt-radio--bold>input:checked~span {
            border: 2px solid #4FC4CA;
        }

        .kt-radio>span:after {
            border: solid #027177;
            background: #027177;
            margin-left: -4px;
            margin-top: -4px;
            width: 8px;
            height: 8px;
        }

        .kt-checkbox-fix {
            padding: 15px 15px;
        }

        .kt-checkbox-fix span {
            position: absolute;
            top: unset !important;
            bottom: -10px !important;
            left: 30px !important;
        }

        .m-portlet__body {
            color
        }

        .primary-color {
            color: #027177 !important;
            font-weight: 500;
        }

        .form-control-feedback {
            color: red;
        }

        .m-radio>span:after {
            background: #4FC4CA !important;
            border: 1px solid #4FC4CA !important;
        }

        .m-radio>span {
            border: 1px solid #4FC4CA !important;
        }


        .kt-checkbox.kt-checkbox--bold span {
            border: 1px solid #4FC4CA;
        }

        .fw_title {
            font-weight: bold !important;
            color: #000000;
            font-size: 18px;
        }

        .m-portlet__head-text {
            font-weight: bold !important;
        }

        .form-control-feedback {
            font-weight: 400 !important;
            padding: 5px 0px !important;
        }

        .list-document-append img {
            width: 100px;
            margin: auto;
        }

        .list-document-append .delete-img-document {
            width: 100px;
            margin: auto;
        }
    </style>
@endsection
{{--@section('after_css')--}}
{{--    <link href="{{ asset('static/backend/css/survey/style.bundle.css') }}" rel="stylesheet" type="text/css" />--}}
{{--@endsection--}}
@section('content')
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
                <a href="{{ route('manager-work.project') }}" class="btn btn-secondary btn-search ml-2"
                    style="color:black; border:1px solid">
                    {{ __('HUỶ') }}
                </a>
                <button onclick="ProjectAdd.save()" type="button"
                    class="btn btn-primary color_button color_button_destroy  btn-search ml-2">
                    <i class="la la-check"></i>
                    {{ __('LƯU THÔNG TIN') }}
                </button>
            </div>
        </div>
        <div class="kt-portlet kt-portlet--mobile">
            <form id="form-data" class="mt-5">
                <div class="m-portlet__body">
                    <div class="row">
                        <!-- Tên dự án -->
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    {{ __('Tên dự án') }} <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" onchange="ProjectAdd.namePrefixProject(this)"
                                        class="form-control m-input date-input" placeholder="{{ __('Nhập tên dự án') }}"
                                        id="project_name" name="project_name">
                                </div>
                            </div>
                        </div>
                        <!-- Tên trạng thái -->
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    {{ __('Trạng thái dự án') }} <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <select name="project_status" id="project_status"
                                        class="form-control select2 select2-active">
                                        @foreach ($listStatus as $item)
                                            <option value="{{ $item->manage_project_status_id }}" {{$item->manage_project_status_id == 1 ? 'selected' : ''}}>
                                                {{ $item->manage_project_status_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <!-- Người quản trị, Phòng ban trực thuộc -->
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Người quản trị') }} <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select name="project_manager" id="project_manager"
                                                class="form-control select2 select2-active">
                                                @foreach ($listStaffs as $item)
                                                    <option value="{{ $item->staff_id }}"
                                                        {{ Auth::user()->staff_id == $item->staff_id ? 'selected' : '' }}>{{ $item->full_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Phòng ban trực thuộc') }} <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group">
                                            <select name="project_department" id="project_department"
                                                class="form-control select2 select2-active">
                                                <option value="">{{ __('Chọn phòng ban trực thuộc') }}</option>
                                                @foreach ($listDepartment as $item)
                                                    <option value="{{ $item->department_id }}" {{\Illuminate\Support\Facades\Auth::user()['department_id'] == $item->department_id ? 'selected' : ''}}>
                                                        {{ $item->department_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tiền tố tác vụ -->
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    {{ __('Tiền tố công việc') }} <b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control m-input date-input"
                                        placeholder="{{ __('Nhập tiền tố công việc') }}" id="project_prefix"
                                        name="project_prefix">
                                </div>
                            </div>
                        </div>
                        <!-- Ngày bắt đầu, Ngày kết thúc -->
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Ngày bắt đầu hoạt động') }} <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group date date-multiple">
                                            <input type="text" class="form-control m-input date-input"
                                                placeholder="{{ __('Chọn ngày') }}"
                                                value="{{ \Carbon\Carbon::now()->format('d/m/Y') }}" id="date_start"
                                                name="date_start">
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
                                            {{ __('Ngày kết thúc hoạt động') }} <b class="text-danger">*</b>
                                        </label>
                                        <div class="input-group date date-multiple">
                                            <input type="text" class="form-control m-input date-input"
                                                placeholder="{{ __('Chọn ngày') }}" id="date_end" name="date_end">
                                            <div class="input-group-append">
                                                <span class="input-group-text"><i
                                                        class="la la-calendar-check-o glyphicon-th"></i></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Tags -->
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <div class="form-group m-form__group">
                                    <label class="black_title">
                                        {{ __('Tags') }} :
                                    </label>
                                    <div class="input-group">
                                        <select name="manage_tags" multiple id="manage_tags"
                                            class="form-control select2 select2-active">
                                            <option value="">{{ __('Chọn tags') }}</option>
                                            @foreach ($listTag as $item)
                                                <option value="{{ $item->manage_tag_id }}">{{ $item->manage_tag_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Loại khác hàng, Khách hàng, Màu -->
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Loại khách hàng') }}
                                        </label>
                                        <div class="input-group">
                                            <select onchange="ProjectAdd.getCustomerDynamic(this)"
                                                name="project_type_customer" id="project_type_customer"
                                                class="form-control select2 select2-active">
                                                <option value="">{{ __('Chọn loại khách hàng') }}</option>
                                                <option value="personal">{{ __('Cá nhân') }}</option>
                                                <option value="business">{{ __('Doanh nghiệp') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Khách hàng') }}
                                        </label>
                                        <div class="input-group">
                                            <select name="project_customer" id="project_customer"
                                                class="form-control select2 select2-active">
                                                <option value="">{{ __('Chọn khách hàng') }}</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group m-form__group">
                                        <label class="black_title">
                                            {{ __('Màu dự án') }} :
                                        </label>
                                        <div class="input-group">
                                            <input type="color" id="color_code" name="color_code"
                                                class="bfh-colorpicker" data-name="colorpicker1" id="color_code"
                                                value="#ff0000" placeholder="{{ __('Placehoder') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Mô tả dự án -->
                        <div class="col-lg-6">
                            <div class="m-form__group">
                                <label class="black_title">
                                    {{ __('Mô tả dự án') }} :
                                </label>
                                <textarea name="description" id="description" class="form-control m-input summernote"></textarea>
                            </div>
                        </div>
                        <!-- Quyền truy cập -->
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black_title">
                                    {{ __('Quyền truy cập') }} <b class="text-danger">*</b>
                                </label>
                                <div class="row mt-2">
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                            <label class="m-radio cus">
                                                <input type="radio" name="permission" checked
                                                    class="period_in_date_type period_in_date_type_unlimited"
                                                    value="private">
                                                {{ __('Nội bộ') }}
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-group">
                                            <label class="m-radio cus">
                                                <input type="radio" name="permission"
                                                    class="period_in_date_type period_in_date_type_unlimited"
                                                    value="public">
                                                {{ __('Công khai') }}
                                                <span></span>
                                            </label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        <!-- Thêm tài liệu -->
                        <div class="col-lg-7 mt-5 ">
                            <div class="row list-document-append text-center"></div>
                        </div>
{{--                        <div class="col-lg-12 mt-5">--}}
{{--                            <div class="form-group m-form__group">--}}
{{--                                <a href="javascript:void(0)" onclick="Document.showPopup()"--}}
{{--                                    class="btn btn-primary color_button btn-search kt-padding-l-40 kt-padding-r-40">--}}
{{--                                    <span>--}}
{{--                                        <i class="fa fa-plus-circle"></i>--}}
{{--                                        <span> {{ __('Thêm tài liệu') }}</span>--}}
{{--                                    </span>--}}
{{--                                </a>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    </div>
                </div>
            </form>
        </div>
    </div>
    <form id="form-file" autocomplete="off">
        <div id="block_append"></div>
{{--        <input type="hidden" id="manage_work_id" name="manage_work_id" value="{{$detail['manage_work_id']}}">--}}
    </form>
@endsection

@section('after_script')
    <script>
        var n = 0;
        var nDocument = 0;
    </script>
    <script type="text/template" id="imageShow">
        <div class="image-show col-3">
            <img class="img-fluid" src="{link}">
            <p class="name_file">{file_name}</p>
            <input type="hidden" name="img[{n}][path]" class="path" value="{link}">
            <input type="hidden" name="img[{n}][file_name]" class="file_name" value="{file_name}">
            <input type="hidden" name="img[{n}][file_type]" class="file_type" value="image">
            <span class="delete-img-document" style="display: block;">
                <a href="javascript:void(0)" onclick="Document.removeImage(this)">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </span>
        </div>
    </script>
    <script type="text/template" id="imageShowFile">
        <div class="image-show col-3">
            <img src="{{asset('static/backend/images/document.png')}}">
            <p class="name_file">{file_name}</p>
            <input type="hidden" name="img[{n}][file_name]" class="file_name" value="{file_name}">
            <input type="hidden" name="img[{n}][file_type]" class="file_type" value="file">
            <input type="hidden" name="img[{n}][path]" class="path" value="{path}">
            <span class="delete-img-document" style="display: block;">
                <a href="javascript:void(0)" onclick="Document.removeImage(this)">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </span>
        </div>
    </script>

    <script src="{{ asset('static/backend/js/manager-work/project/main.js?v=' . time()) }}"></script>
    <script src="{{ asset('static/backend/js/manager-work/project/add.js?v=' . time()) }}"></script>

@endsection
