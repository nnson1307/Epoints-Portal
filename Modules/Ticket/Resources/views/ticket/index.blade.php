@extends('layout')
@section('title_header')
    <span class="title_header"><img src="{{ asset('uploads/admin/icon/icon-order.png') }}" alt="" style="height: 20px;">
        @lang('QUẢN LÝ TICKET')</span>
@stop
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phieu-custom.css')}}">
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("DANH SÁCH TICKET")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <form action="{{route('ticket.export-excel')}}" method="POST">
                    {{ csrf_field() }}
                    <button type="submit" class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                        <span>
                                            <i class="la la-files-o"></i>
                                            <span>
                                                {{__('Xuất dữ liệu')}}
                                            </span>
                                        </span>
                    </button>
                </form>

                <a href="javascript:void(0)" onclick="ticket.configSearch()"
                    class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm mr-3">
                    <span>
                        <i class="fa fa-cog"></i>
                        <span> {{ __('CẤU HÌNH') }}</span>
                    </span>
                </a>
                <a href="{{ route('ticket.add') }}"
                    class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                    <span>
                        <i class="fa fa-plus-circle"></i>
                        <span> {{ __('THÊM TICKET') }}</span>
                    </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">
                    <input type="hidden" name="ticket_index" value="1">
                    <div class="row padding_row">
                        @foreach ($searchConfig as $config)
                            @if ($config['active'])
                                @if ($config['type'] == 'select2')
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <select name="{{ $config['name'] }}"
                                                class="form-control select2 select2-active">
                                                <option value="">{{ $config['placeholder'] }}</option>
                                                @foreach ($config['data'] as $key => $value)
                                                    <option value="{{ $key }}">{{ $value }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @elseif($config['type'] == 'daterange_picker')
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <div class="m-input-icon m-input-icon--right">
                                                <input readonly class="form-control daterange-picker"
                                                    style="background-color: #fff" name="{{ $config['name'] }}" autocomplete="off"
                                                    placeholder="{{ $config['placeholder'] }}">
                                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                                    <span><i class="la la-calendar"></i></span></span>
                                            </div>
                                        </div>
                                    </div>
                                @elseif($config['type'] == 'text')
                                    <div class="col-lg-3">
                                        <div class="form-group">
                                            <input type="text" class="form-control" name="{{ $config['name'] }}"
                                                placeholder="{{ $config['placeholder'] }}">
                                        </div>
                                    </div>
                                @endif
                            @endif
                        @endforeach
                        <div class="col-lg-3">
                            <div class="d-flex">
                                <button class="btn btn-clear-form btn-refresh ss--button-cms-piospa m-btn--icon mr-3">
                                    {{ __('XÓA BỘ LỌC') }}
                                    <i class="fa fa-eraser" aria-hidden="true"></i>
                                </button>
                                <button class="btn btn-primary color_button btn-search" style="display: block">
                                    @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="table-content m--padding-top-30" id="table-content-ticket">
                    @include('ticket::ticket.list')
                </div>
            </div>
        </div>
    </div>
    </div>
    <div id="my-modal"></div>
    @include('ticket::ticket.popup.config_search')
@endsection
@section('after_style')
    <link rel="stylesheet" href="{{ asset('static/backend/css/son.css') }}">
    <link rel="stylesheet" href="{{ asset('static/backend/css/customize.css') }}">
@stop
@section('after_script')
    @include('ticket::language.lang')
    <script src="{{ asset('static/backend/js/ticket/ticket/script.js?v=' . time()) }}" type="text/javascript"></script>
    <script>
        @if(isset($params['ticket_status_id']))
            $('.frmFilter [name="ticket_status_id"]').val("{{$params['ticket_status_id']}}").trigger('change');
        @endif
    </script>
@stop
