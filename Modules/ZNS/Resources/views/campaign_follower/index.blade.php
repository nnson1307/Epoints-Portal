@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt="" style="height: 20px;">
        {{ __('QUẢN LÝ CHIẾN DỊCH ZNS') }}</span>
@endsection
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phieu-custom.css')}}">
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
                        {{ __('DANH SÁCH CHIẾN DỊCH') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <div class="dropdown mr-3">
                    <button class="btn ss--button-cms-piospa dropdown-toggle" type="button" id="dropdownMenu2"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('TẠO CHIẾN DỊCH') }}</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        <a href="{{ route('zns.campaign.add',['campaign_type'=>'zns']) }}" class="dropdown-item"
                           type="button">{{__('Tạo chiến dịch Zalo template API')}}</a>
                        <a href="{{ route('zns.campaign-follower.add',['campaign_type'=>'follower']) }}" class="dropdown-item"
                           type="button">{{__('Tạo chiến dịch Zalo Follower API')}}</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex m-portlet__head">
            <ul class="nav nav-tabs m-0 align-items-center justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('zns.campaign')}}">{{__('ZNS Template API')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('zns.campaign-follower')}}">{{__('ZNS FOLLOWER API')}}</a>
                </li>
            </ul>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter ss--background m--margin-bottom-30">
                <div class="ss--bao-filter">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <div class="m-form__group">
                                        <div class="input-group">
                                            <input type="hidden" name="page"
                                                   value="{{ (isset($params['page']) && $params['page'] ) ? $params['page']:'' }}">
                                            <input type="text" class="form-control" name="search"
                                                   placeholder="{{ __('Nhập thông tin tìm kiếm') }}"
                                                   value="{{ (isset($params["search"]) && $params["search"] ) ? $params["search"]:'' }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <select name="oa" class="form-control select2 select2-active-choose-first">
                                        <option value="">@lang('Chọn OA')</option>
                                        {{-- @foreach ($groupRequest as $name => $item)
                                            <option value="{{ $item['ticket_issue_group_id'] }}">{{ $item['name'] }}</option>
                                        @endforeach --}}
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <select name="status" class="form-control select2 select2-active-choose-first" id="">
                                        <option value="">@lang('Chọn trạng thái')</option>
                                        @foreach ($campaign_status as $key => $item)
                                            <option value="{{ $key }}"{{ (isset($params["status"]) && $params["status"] == $key ) ? " selected":'' }}>{{ $item }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input readonly class="form-control m-input daterange-picker"
                                               style="background-color: #fff" name="created_at"
                                               autocomplete="off" placeholder="{{ __('Ngày tạo') }}"
                                               value="{{ (isset($params["created_at"]) && $params["created_at"] ) ? $params["created_at"]:'' }}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input readonly class="form-control m-input daterange-picker"
                                               style="background-color: #fff" name="time_sent"
                                               autocomplete="off" placeholder="{{ __('Ngày gửi') }}"
                                               value="{{ (isset($params["time_sent"]) && $params["time_sent"] ) ? $params["time_sent"]:'' }}">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                            <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="d-flex">
                                        <a href="{{ route('zns.campaign-follower') }}" class="btn btn-clear-form btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                            {{ __('XÓA BỘ LỌC') }}
                                            <i class="fa fa-eraser" aria-hidden="true"></i>
                                        </a>
                                        <button class="btn btn-primary color_button btn-search" style="display: block">
                                            @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-content">
                @include('zns::campaign_follower.list')
            </div>
            <!-- end table-content -->
        </div>
    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/zns/campaign_follower/list.js')}}" type="text/javascript"></script>
@stop
