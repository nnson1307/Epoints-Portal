@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('static/backend/images/icon/icon-staff.png') }}" alt="" style="height: 20px;">
        {{ __('QUẢN LÝ CHIẾN DỊCH ZNS') }}</span>
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
                        {{ __('DANH SÁCH MẪU ZNS') }}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <div class="dropdown" style="margin-right: 15rem !important;">
                    <button class="btn ss--button-cms-piospa dropdown-toggle" type="button" id="dropdownMenu2"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('TẠO MỚI') }}</span>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenu2">
                        @foreach ($list_type_template_follower as $key => $value)
                            <a href="{{ route('zns.template-follower.add',['type_template_follower'=>$key]) }}"
                               class="dropdown-item"
                               type="button">{{$value}}</a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="d-flex m-portlet__head">
            <ul class="nav nav-tabs m-0 align-items-center justify-content-center">
                <li class="nav-item">
                    <a class="nav-link" href="{{route('zns.template')}}">{{__('ZNS Template API')}}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="{{route('zns.template-follower')}}">{{__('ZNS FOLLOWER API')}}</a>
                </li>
            </ul>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter ss--background m--margin-bottom-30">
                <div class="ss--bao-filter">
                    <div class="row">
                        <div class="col-lg-3 form-group">
                            <div class="m-form__group">
                                <div class="input-group">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <input type="text" class="form-control" name="search"
                                           placeholder="{{ __('Nhập thông tin tìm kiếm') }}"
                                           value="{{ isset($params['search']) && $params['search'] ? $params['search'] : '' }}">
                                    <input type="hidden" name="page"
                                           value="{{ isset($params['page']) && $params['page'] ? $params['page'] : '' }}">
                                </div>
                            </div>
                        </div>
                        {{--                        <div class="col-lg-2 form-group">--}}
                        {{--                            <select name="oa" class="form-control select2 select2-active-choose-first" id="">--}}
                        {{--                                <option value="">@lang('Chọn OA')</option>--}}
                        {{--                            </select>--}}
                        {{--                        </div>--}}
                        <div class="col-lg-3 form-group">
                            <select name="type_template_follower"
                                    class="form-control select2 select2-active-choose-first" id="">
                                <option value="">@lang('Chọn loại mẫu')</option>
                                @foreach ($list_type_template_follower as $key => $value)
                                    <option value="{{$key}}"{{ (isset($params['type_template_follower']) && $params['type_template_follower'] == $key ) ? 'selected': '' }}>{{$value}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input readonly class="form-control m-input daterange-picker"
                                       style="background-color: #fff" name="created_at"
                                       autocomplete="off" placeholder="{{ __('Ngày tạo') }}"
                                       value="{{ isset($params['created_at']) && $params['created_at'] ? $params['created_at'] : '' }}">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                        <div class="col-lg-3 form-group">
                            <div class="d-flex">
                                <a href="{{route('zns.template-follower')}}"
                                   class="btn ss--button-cms-piospa m-btn--icon mr-3">
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
            </form>
            <div class="table-content">
                @include('zns::template.follower.list')
            </div>
            <!-- end table-content -->
        </div>
    </div>
    <div class="modal fade" id="confirm-clone" role="dialog">
        @include('zns::template.follower.modal_clone')
    </div>
@stop
@section('after_script')
    <script src="{{ asset('static/backend/js/zns/template/list.js?v=' . time()) }}" type="text/javascript"></script>
@stop
