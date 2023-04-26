@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-staff.png')}}" alt=""
                style="height: 20px;"> {{__('QUẢN LÝ HOÀN ỨNG VẬT TƯ')}}</span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
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
                        {{__('HOÀN ỨNG VẬT TƯ')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)"
                       data-toggle="modal"
                       data-target="#add-popup"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('TẠO PHIẾU HOÀN ỨNG')}}</span>
                        </span>
                    </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter bg" action="{{route('ticket.refund')}}" method="GET">
                <input type="hidden" name="page" value="{{ (isset($params['page']) && $params['page'] ) ? $params['page']:'' }}">
                <div class="row padding_row">
                    @foreach ($searchConfig as $config)
                        @if ($config['active'])
                            @if ($config['type'] == 'select2')
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <select name="{{ $config['name'] }}"
                                            class="form-control select2 select2-active">
                                            <option value="">{{ $config['placeholder'] }}</option>
                                            @foreach ($config['data'] as $key => $value)
                                                <option value="{{ $key }}"{{ (isset($params[$config['name']]) && $params[$config['name']] == $key ) ? 'selected': '' }}>{{ $value }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @elseif($config['type'] == 'daterange_picker')
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input readonly class="form-control daterange-picker"
                                                style="background-color: #fff" name="{{ $config['name'] }}" autocomplete="off"
                                                placeholder="{{ $config['placeholder'] }}" value="{{ (isset($params[$config['name']]) && $params[$config['name']] ) ? $params[$config['name']]:'' }}">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                                <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            @elseif($config['type'] == 'text')
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="{{ $config['name'] }}"
                                            placeholder="{{ $config['placeholder'] }}" value="{{ (isset($params[$config['name']]) && $params[$config['name']] ) ? $params[$config['name']]:'' }}">
                                    </div>
                                </div>
                            @endif
                        @endif
                    @endforeach
                    <div class="col-lg-3">
                        <div class="d-flex">
                            <a class="btn btn-clear-form btn-refresh ss--button-cms-piospa m-btn--icon mr-3" href="{{route('ticket.refund')}}">
                                {{ __('XÓA BỘ LỌC') }}
                                <i class="fa fa-eraser" aria-hidden="true"></i>
                            </a>
                            <button type="submit" class="btn btn-primary color_button btn-search" style="display: block">
                                @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-content m--padding-top-30">
                @include('ticket::refund.list')
            </div><!-- end table-content -->
        </div>
    </div>
    @include('ticket::refund.popup.addPopup')
@stop
@section('after_script')
@include('ticket::language.lang')
    <script>
        var user_id = '{{ \Auth::id() }}';
        if ("{{session('remove_action')}}" == "success"){
            swal(
                "{{ __('Xóa phiếu hoàn ứng thành công') }}",
                '',
                'success'
            );
        }else if ("{{session('remove_action')}}" == "danger"){
            swal(
                "{{ __('Xóa phiếu hoàn ứng thất bại') }}",
                '',
                'warning'
            );
        }
    </script>
    <script src="{{asset('static/backend/js/ticket/refund/list.js?v='.time())}}" type="text/javascript"></script>
@stop
