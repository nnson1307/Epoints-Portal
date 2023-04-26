@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-sms.png')}}" alt="" style="height: 20px;">
        SMS
    </span>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('content')
    <!--begin::Portlet-->
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-institution"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('DANH SÁCH CHIẾN DỊCH')}}</span>
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.sms.sms-campaign-add',session('routeList')))
                    <a href="{{route('admin.sms.sms-campaign-add')}}"
                       class="btn ss--button-cms-piospa color_button m-btn btn-sm m-btn--icon m-btn--pill btn_add_pc">
                        <span>
						   <i class="fa fa-plus-circle"></i>
							<span> {{__('THÊM CHIẾN DỊCH')}}</span>
                        </span>
                    </a>
                    <a href="{{route('admin.sms.sms-campaign-add')}}" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                    color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <div class="ss--background">
                <div class="ss--bao-filter">
                    <div class="row">
                        <div class="col-lg-5 form-group">
                            <div class="m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="product_name">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <input type="text" class="form-control" name="search_keyword"
                                           placeholder="{{__('Nhập tên hoặc mã chiến dịch')}}">
                                    {{--<div class="input-group-append">--}}
                                    {{--<a href="javascript:void(0)" onclick="SmsCampaign.refresh()"--}}
                                    {{--class="btn btn-primary m-btn--icon">--}}
                                    {{--<i class="la la-refresh"></i>--}}
                                    {{--</a>--}}
                                    {{--</div>--}}
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="m-input-icon m-input-icon--right" style="background-color: white">
                                <input onchange="SmsCampaign.filter()" readonly
                                       class="form-control m-input daterange-picker" id="created_at"
                                       name="created_at"
                                       autocomplete="off" placeholder="{{__('Chọn ngày tạo')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="m-input-icon m-input-icon--right" style="background-color: white">
                                <input onchange="SmsCampaign.filter()" readonly
                                       class="form-control m-input daterange-picker" id="day-sent"
                                       name="created_at"
                                       autocomplete="off" placeholder="{{__('Chọn ngày gửi')}}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="m-form m-form--label-align-right">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="row">
                                    @php $i = 0; @endphp
                                    @foreach ($FILTER as $name => $item)
                                        @if ($i > 0 && ($i % 4 == 0))
                                </div>
                                <div class="form-group m-form__group row align-items-center">
                                    @endif
                                    @php $i++; @endphp
                                    <div class="col-lg-3 input-group form-group">
                                        @if(isset($item['text']))
                                            <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        {{ $item['text'] }}
                                                    </span>
                                            </div>
                                        @endif
                                        {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
                                    </div>
                                    @endforeach
                                    <div class="col-lg-3 form-group">
                                        <button class="btn ss--btn-search btn-search  color_button"
                                                onclick="SmsCampaign.filter()">
                                            <span class="m--margin-left-20 m--margin-right-20">{{__('TÌM KIẾM')}}
                                            <i class="fa fa-search ss--icon-search"></i>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-content list-campaign m--margin-top-30">
                @include('admin::marketing.sms.campaign.list')
            </div>
        </div>
    </div>
    <!--end::Portlet-->

@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/marketing/sms/campaign/index.js?v='.time())}}"
            type="text/javascript"></script>

@stop