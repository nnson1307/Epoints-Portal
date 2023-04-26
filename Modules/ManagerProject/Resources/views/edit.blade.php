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
    <form id="formEdit">
        <div class="m-portlet m-portlet--head-sm">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <span class="m-portlet__head-icon">
                            <i class="fa fa-plus-circle"></i>
                        </span>
                        <h2 class="m-portlet__head-text">
                            {{ __('CHỈNH SỬA DỰ ÁN') }}
                        </h2>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    {{-- <div onmouseover="onmouseoverAddNew()" onmouseout="onmouseoutAddNew()" --}}
                    {{-- class="m-dropdown m-dropdown--inline m-dropdown--arrow m-dropdown--align-right m-dropdown--align-push m-dropdown--open btn-hover-add-new" --}}
                    {{-- m-dropdown-toggle="hover" aria-expanded="true"> --}}
                    {{-- <a href="#" --}}
                    {{-- class="m-portlet__nav-link btn btn-lg btn-secondary  m-btn m-btn--outline-2x m-btn--air m-btn--icon m-btn--icon-only m-btn--pill  m-dropdown__toggle"> --}}
                    {{-- <i class="la la-plus m--hide"></i> --}}
                    {{-- <i class="la la-ellipsis-h"></i> --}}
                    {{-- </a> --}}
                    {{-- <div class="m-dropdown__wrapper dropdow-add-new" style="z-index: 101;display: none"> --}}
                    {{-- <span class="m-dropdown__arrow m-dropdown__arrow--right m-dropdown__arrow--adjust" --}}
                    {{-- style="left: auto; right: 21.5px;"></span> --}}
                    {{-- <div class="m-dropdown__inner"> --}}
                    {{-- <div class="m-dropdown__body"> --}}
                    {{-- <div class="m-dropdown__content"> --}}
                    {{-- <ul class="m-nav"> --}}
                    {{-- <li class="m-nav__item"> --}}
                    {{-- <a data-toggle="modal" --}}
                    {{-- data-target="#modalAdd" href="" class="m-nav__link"> --}}
                    {{-- <i class="m-nav__link-icon la la-users"></i> --}}
                    {{-- <span class="m-nav__link-text">{{__('Thêm chức vụ')}} </span> --}}
                    {{-- </a> --}}
                    {{-- </li> --}}
                    {{-- <li class="m-nav__item"> --}}
                    {{-- <a data-toggle="modal" --}}
                    {{-- data-target="#modalAddPartment" href="" class="m-nav__link"> --}}
                    {{-- <i class="m-nav__link-icon la la-users"></i> --}}
                    {{-- <span class="m-nav__link-text">{{__('Thêm phòng ban')}} </span> --}}
                    {{-- </a> --}}
                    {{-- </li> --}}
                    {{-- </ul> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}
                    {{-- </div> --}}
                </div>
                <div class="d-flex">
                    <a href="{{ route('admin.staff') }}"
                        class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md align-self-center">
                        <span>
                            <i class="la la-arrow-left"></i>
                            <span>{{ __('HỦY') }}</span>
                        </span>
                    </a>
                    <button type="button" onclick="Project.buttonEdit()"
                        class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md btn_add_close m--margin-left-10 align-self-center">
                        <span>
                            <i class="la la-check"></i>
                            <span>{{ __('LƯU THÔNG TIN') }}</span>
                        </span>
                    </button>
                </div>
            </div>
            <div class="m-portlet m-portlet--head-sm">

            </div>


            <form id="form-add">
                {{-- <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-2">
                        <div class="form-group m-form__group">
                            <input type="hidden" id="staff_avatar" name="staff_avatar" value="">
                            <div class="form-group m-widget19">
                                <div class="m-widget19__pic">

                                </div>



                                <div class="m-widget19__action" style="max-width: 155px">

                                </div>
                            </div>
                        </div>
                    </div> --}}
                <div class="col-lg-10">
                    <div class="row clearfix">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <label class="black-title">{{ __('Tên dự án') }}:<b class="text-danger">*</b></label>
                                <input type="text" name="edit_name" class="form-control m-input" id="edit_name"
                                    placeholder="{{ __('Placehoder') }}">
                            </div>
                            <div class="form-group m-form__group">
                                <div class="row">
                                    <div class="col-lg-6">
                                    <div class="form-group m-form__group "
                                        {{ $errors->has('staff_title_id') ? ' has-danger' : '' }}>
                                        <label class="black-title">{{ __('Người quản trị') }}:<b
                                                class="text-danger">*</b></label>
                                      
                                            <select name="staff_name" data-live-search="true"
                                                class="form-control ss--select-2" placeholder="{{ __('Placehoder') }}"
                                                style="width: 100% !important;">
                                                @foreach ($listStaff as $item)
                                                    <option value="{{ $item->staff_id }}">{{ $item->full_name }}</option>
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                    <div class="form-group m-form__group " 
                                        {{ $errors->has('staff_title_id') ? ' has-danger' : '' }}>
                                        <label class="black-title">{{ __('Phòng trực ban') }}:<b
                                                class="text-danger">*</b></label>
                                        <div class="input-group">
                                            <select name="staff_title_id" data-live-search="true"
                                                class="form-control ss--select-2" placeholder="{{ __('Placehoder') }}">
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

                                                <input name="date_start" id="date_start"
                                                    class="form-control m-input date-input" type="text" name="date_start"
                                                     placeholder="Ngày bắt thúc">
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
                                                <input name="date_end" id="date_end"
                                                    class="form-control m-input date-input" type="text" name="date_end"
                                                    value="" placeholder="Ngày kết thúc">
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
                                                    <select name="staff_title_id" id="customer_id"
                                                        data-live-search="true" class="form-control ss--select-2">
                                                        <option value="staff">{{ __('Cá nhân') }}</option>
                                                        <option value="staff">{{ __('Doanh nghiệp') }}</option>
                                                    
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group m-form__group w-50" style="margin-left: 5%"
                                                {{ $errors->has('staff_title_id') ? ' has-danger' : '' }}>
                                                <label class="black-title">{{ __('Khách hàng') }}:</label>
                                                <div class="input-group">
                                                    <select name="staff_title_id" id="custom_id"
                                                        data-live-search="true" class="form-control ss--select-2">
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
                                            <input type="color" name="full_name" class="form-control m-input"
                                                id="full_name" value="#ff0000" placeholder="{{ __('Placehoder') }}">
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

                            </div>

                        </div>
                        <div class="col-lg-6">
                            <div class="form-group m-form__group" style="margin-left: 10%">


                                <label class="black_title">
                                    {{ __('Tiến độ dự án') }}:<b class="text-danger">*</b>
                                </label>
                                <div class="input-group">
                                    <input type="text" class="progress_input form-control" name="progress_name"
                                        value="" aria-invalid="false">
                                    <div class="input-group-append">
                                        <span class="input-group-text">%</span>
                                    </div>
                                </div>


                                <div class="form-group m-form__group"
                                    {{ $errors->has('staff_title_id') ? ' has-danger' : '' }}>
                                    <label class="black-title">{{ __('Trạng thái dữ án') }}:<b
                                            class="text-danger">*</b></label>
                                    <div class="input-group">
                                        <select name="staff_title_id" id="status_id" 
                                            data-live-search="true" class="form-control ss--select-2">
                                            @foreach ($listStatus as $item)
                                                <option value="{{ $item->manage_status_id }}">
                                                    {{ $item->manage_status_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>


                                    <div class="form-group m-form__group">

                                        <div class="input-group">

                                        </div>
                                    </div>

                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{ __('Tag') }}:</b></label>
                                    <div class="input-group">
                                        <select name="staff_title_id" id="staff_title_id" data-live-search="true"
                                            class="form-control ss--select-2">
                                            @foreach ($listTags as $item)
                                                <option value="{{ $item->manage_tag_id }}">{{ $item->manage_tag_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group m-form__group">
                                    <label class="black-title">{{ __('Mô tả dự án') }}:</b></label>
                                    <textarea name="description" id="desc_id" class="form-control m-input summernote" style="display: none;"></textarea>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    @stop
    @section('after_script')
        <script>
            $(document).ready(function() {
                $('#desc_id').summernote();
            });
        </script>
        <script src="{{ asset('static/backend/js/manager-work/project/list.js?v=' . time()) }}" type="text/javascript">
        </script>

    @endsection
