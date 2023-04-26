@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-member.png')}}" alt=""
                style="height: 20px;"> {{__('LOG CÂU HỎI KHÁCH HÀNG')}}</span>
@stop
@section('content')

    <style>
        /*.modal-backdrop {*/
        /*position: relative !important;*/
        /*}*/

        .form-control-feedback {
            color: red;
        }
    </style>
    @include('admin::customer.active-sv-card')
    <div class="m-portlet m-portlet--head-sm" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                         <i class="la la-th-list"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        {{__('DANH SÁCH LOG CÂU HỎI KHÁCH HÀNG')}}
                    </h2>

                </div>
            </div>

            <div class="m-portlet__head-tools">

            </div>

        </div>
        <div class="m-portlet__body">
            <form class="frmFilter bg">
                <div class="row padding_row">
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <select name="feedback_question_type" class="form-control select-fix">
                                    <option value="">{{__('Chọn loại câu hỏi')}}</option>
                                    <option value="rating">{{__('Câu hỏi đánh giá')}}</option>
                                    <option value="comment">{{__('Câu hỏi dạng phát biểu cảm nghĩ')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="text" class="form-control" name="search"
                                       placeholder="{{__('Nhập câu hỏi')}}">
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <select name="feedback_question_active" class="form-control select-fix">
                                    <option value="">{{__('Chọn trạng thái')}}</option>
                                    <option value="0">{{__('Ẩn')}}</option>
                                    <option value="1">{{__('Hiển thị')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3">
                        <div class="input-group" style="background-color: white">
                            <div class="m-input-icon m-input-icon--right">
                                <input readonly="" class="form-control m-input daterange-picker"
                                       id="created_at" name="created_at" autocomplete="off"
                                       placeholder="{{__('Ngày tạo')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                                    <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group m-form__group">
                            <button class="btn btn-primary color_button btn-search" onclick="log.search()">
                                {{__('TÌM KIẾM')}} <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </form>
            <div class="table-content m--padding-top-30">
                @include('admin::log-customer.question-customer.list')

            </div><!-- end table-content -->

        </div>
    </div>
    <div id="my-modal"></div>

@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/log-customer/script.js?v='.time())}}" type="text/javascript"></script>
    <script>
        $('.select-fix').select2();
        $(".m_selectpicker").selectpicker();
    </script>
@stop