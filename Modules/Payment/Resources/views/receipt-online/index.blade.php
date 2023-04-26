@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ PHIẾU THU')</span>
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
                        @lang("DANH SÁCH GIAO DỊCH THANH TOÁN ONLINE")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="row padding_row">
                        <div class="col-lg-5">
                            <div class="form-group">
                                <input type="text" class="form-control" name="search"
                                       placeholder="@lang("Nhập thông tin tìm kiếm")">
                            </div>
                        </div>
                        <div class="col-lg-5 form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input readonly class="form-control m-input daterange-picker"
                                       style="background-color: #fff"
                                       id="payment_time"
                                       name="payment_time"
                                       autocomplete="off" placeholder="@lang('THỜI GIAN')">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                        <div class="col-lg-2 form-group">
                            <button class="btn btn-primary color_button btn-search">
                                @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                            </button>
                        </div>
                    </div>
                    <div class="padding_row">
                        <div class="row">
                            @php $i = 0; @endphp
                            @foreach ($FILTER as $name => $item)
                                @if ($i > 0 && ($i % 4 == 0))
                        </div>
                        <div class="form-group m-form__group row align-items-center">
                            @endif
                            @php $i++; @endphp
                            <div class="col-lg-3 form-group input-group">
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
                        </div>
                    </div>
                </form>
                <div class="table-content m--padding-top-30">
                    @include('payment::receipt-online.list')
                </div>
            </div>
        </div>
    </div>
    <form id="form-print-bill" target="_blank" action="{{route('receipt.print-bill')}}" method="GET">
        <input type="hidden" name="print_receipt_id" id="receipt_id" value="">
    </form>
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>

    <script src="{{asset('static/backend/js/payment/receipt-online/script.js')}}"
            type="text/javascript"></script>

    <script>
        listReceiptOnline._init();
    </script>
@stop
