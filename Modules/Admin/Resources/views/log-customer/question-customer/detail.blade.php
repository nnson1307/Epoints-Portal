@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-member.png')}}" alt=""
                style="height: 20px;"> @lang("LOG CÂU HỎI KHÁCH HÀNG")</span>
@stop
@section('content')
    <style>
        .m-image {
            padding: 5px;
            max-width: 155px;
            max-height: 155px;
            background: #ccc;
        }
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h2 class="m-portlet__head-text title_index">
                        <span><i class="la la-server"
                                 style="font-size: 13px"></i> @lang("CHI TIẾT LOG CÂU HỎI KHÁCH HÀNG")</span>
                    </h2>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group m-form__group">
                        <span class="sz_dt">{{__("Loại câu hỏi")}}: {{$item['feedback_question_type'] == 'rating' ? __('Câu hỏi đánh giá') : __('Câu hỏi dạng phát biểu cảm nghĩ')}} </span>
                    </div>
                    <div class="form-group m-form__group">
                        <span class="sz_dt">{{__("Câu hỏi")}}: {{$item['feedback_question_title']}}</span>
                    </div>
                    <div class="form-group m-form__group">
                        <span class="sz_dt">{{__("Trạng thái")}}:
                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm ml-3">
                                <label class="m-0">
                                    <input type="checkbox" disabled {{$item['feedback_question_active'] == 1 ? 'checked' : ''}} class="manager-btn" name="">
                                    <span class="m-0"></span>
                                </label>
                            </span>
                        </span>
                    </div>
                    <div class="form-group m-form__group">
                        <span class="sz_dt">{{__("Ngày tạo")}}: {{date("d/m/Y",strtotime($item['created_at']))}}</span>
                    </div>
                </div>
            </div>

        </div>
        <div class="m-portlet__foot">
            <div class="m-form__actions m--align-right">
                <a href="{{route('admin.log.question-customer')}}"
                   class="btn btn-metal bold-huy  m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>@lang("QUAY LẠI")</span>
						</span>
                </a>
            </div>
        </div>

    </div>

@stop
@section("after_style")
    {{--    <link rel="stylesheet" href="{{asset('static/backend/css/process.css')}}">--}}
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">

@stop