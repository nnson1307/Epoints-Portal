@extends('layout')
@section('title_header')
    <span class="title_header">{{__('QUẢN LÝ CHI NHÁNH')}}</span>
@stop
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                         {{__('THÊM CHI NHÁNH')}}
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        @include('admin::branch.modal-add-image')
        <form id="form">
            {!! csrf_field() !!}
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Tên chi nhánh')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="branch_name" class="form-control m-input"
                                   id="branch_name"
                                   placeholder="{{__('Nhập tên chi nhánh')}}...">
                            <span class="error-name"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Mã đại diện')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="representative_code" class="form-control m-input"
                                   id="representative_code"
                                   placeholder="{{__('Nhập mã đại diện')}}...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Số điện thoại')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="phone" class="form-control m-input" id="phone"
                                   placeholder="{{__('Hãy nhập số điện thoại')}}...">
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Email')}}:
                            </label>
                            <input type="text" name="email" class="form-control m-input" id="email"
                                   placeholder="{{__('Hãy nhập email')}}...">
                            <span class="error_email" style="color: red"></span>
                        </div>
                        <div class="form-group m-form__group">
                            <label>
                                {{__('Hot line')}}:
                            </label>
                            <input type="text" name="hot_line" class="form-control m-input" id="hot_line"
                                   placeholder="{{__('Hãy nhập hot line')}}...">
                        </div>
                        <label class="m-checkbox m-checkbox--air">
                            <input id="is_representative" name="example_3" type="checkbox">
                            {{__('Là trụ sở chính')}}
                            <span></span>
                        </label>
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-3 w-col-mb-100">
                                    <a href="javascript:void(0)" onclick="branch.modal_image()"
                                       class="btn btn-sm m-btn--icon color">
                                            <span class="">
                                                <i class="la la-plus"></i>

                                                    {{__('Thêm ảnh chi nhánh')}}

                                            </span>
                                    </a>
                                </div>
                                <div class="col-lg-9 w-col-mb-100 div_avatar">
                                    <div class="image-show ">

                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Tỉnh/ Thành phố')}}:<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control" style="width: 100%" id="provinceid" name="provinceid">
                                    <option></option>
                                    @foreach($optionProvince as $key=>$value)
                                        <option value="{{$key}}">{{$value}}</option>
                                    @endforeach
                                </select>
                            </div>


                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Quận/ Huyện')}}:<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <select class="form-control" style="width: 100%" id="districtid" name="districtid">
                                    <option></option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                {{__('Địa chỉ')}}:<b class="text-danger">*</b>
                            </label>
                            <input type="text" name="address" class="form-control m-input" id="address"
                                   placeholder="{{__('Hãy nhập địa chỉ')}}...">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                Latitude:
                            </label>
                            <input type="text" name="latitude" class="form-control m-input" id="latitude"
                                   placeholder="{{__('Hãy nhập latitude...')}}">
                        </div>
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                Longitude:
                            </label>
                            <input type="text" name="longitude" class="form-control m-input" id="longitude"
                                   placeholder="{{__('Hãy nhập longitude...')}}">
                        </div>
                        <div class="form-group">
                            <label>
                                {{__('Giới thiệu')}}:
                            </label>
                            <textarea placeholder="{{__('Nhập mô tả')}}" rows="5" cols="40"
                                      name="description" id="description" class="form-control"></textarea>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="type_add" id="type_add" value="0">
            </div>
            <div class="modal-footer">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.branch')}}"
                           class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>{{__('HỦY')}}</span>
                            </span>
                        </a>

                        <button type="button" onclick="branch.add()"
                                class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>{{__('LƯU THÔNG TIN')}}</span>
                        </span>
                        </button>

                        {{--<button type="submit" onclick="branch.add(0)"--}}
                        {{--class="btn btn-success color_button  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">--}}
                        {{--<span>--}}
                        {{--<i class="fa fa-plus-circle"></i>--}}
                        {{--<span>{{__('LƯU & TẠO MỚI')}}</span>--}}
                        {{--</span>--}}
                        {{--</button>--}}


                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script type="text/template" id="imgeShow">
        <div class="wrap-img image-show-child">
            <input type="hidden" name="img" value="{link_hidden}">
            <img class='m--bg-metal m-image img-sd '
                 src='{{'{link}'}}' alt='{{__('Hình ảnh')}}' width="100px" height="100px">
            <span class="delete-img" style="display: block;">
                                                    <a href="javascript:void(0)" onclick="branch.remove_img(this)">
                                                        <i class="la la-close"></i>
                                                    </a>
                                                </span>
        </div>

    </script>
    <script src="{{asset('static/backend/js/admin/branch/script.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/branch/dropzone.js?v='.time())}}" type="text/javascript"></script>
@stop


