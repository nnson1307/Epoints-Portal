@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ THẺ BẢO HÀNH')</span>
@stop
@section('content')
    <style>
        .err {
            color: red;
        }
    </style>
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                         <i class="fa fa-plus-circle"></i>
                     </span>
                    <h2 class="m-portlet__head-text">
                        @lang('CHI TIẾT THẺ BẢO HÀNH')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="{{route('warranty-card')}}"
                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                            <span>
                                <i class="la la-arrow-left"></i>
                                <span>@lang('HỦY')</span>
                            </span>
                </a>
                @if ($data['status'] == 'new')
                    <a href="{{route('warranty-card.edit', $data['warranty_card_id'])}}"
                       class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="la la-check"></i>
                                <span>@lang('CHỈNH SỬA')</span>
                        </span>
                    </a>
                @endif

                @if(in_array('maintenance.create',session('routeList')) && $isUse == 1)
                    <a href="{{route('maintenance.create', ['warranty_code' => $data['warranty_card_code']])}}" target="_blank"
                       class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                                <span> @lang('THÊM PHIẾU BẢO TRÌ')</span>
                            </span>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="form-group">
                <ul class="nav nav-tabs nav-pills" role="tablist" style="margin-bottom: 0;">
                    <li class="nav-item">
                        <a class="nav-link active son" data-toggle="tab" show
                           href="javascript:void(0)"
                           onclick="detailCard.loadTab('info', '{{$data['warranty_card_id']}}')">@lang("Thông tin thẻ bảo hành")</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link son" data-toggle="tab"
                           href="javascript:void(0)"
                           onclick="detailCard.loadTab('maintenance', '{{$data['warranty_card_id']}}')">@lang("Danh sách phiếu bảo trì")</a>
                    </li>
                </ul>
            </div>

            <div class="form-group tab_detail">
                @include('warranty::warranty-card.tab-detail.info')
            </div>
        </div>
    </div>
    @include('warranty::warranty-card.modal-add-image')
    <div id="my-modal"></div>
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
    <script src="{{asset('static/backend/js/warranty/dropzone.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/warranty/warranty-card/script.js?v='.time())}}"
            type="text/javascript"></script>
    {{--Script phiếu bảo trì--}}
    <script src="{{asset('static/backend/js/warranty/maintenance/script.js?v='.time())}}"
            type="text/javascript"></script>
    {{--End script phiếu bảo trì--}}
    <script>
        edit._init();
    </script>
    <script type="text/template" id="imgeShow">
        <div class="wrap-img image-show-child list-image-new">
            <input type="hidden" name="img-sv" value="{link_hidden}" class="product_image">
            <img class='m--bg-metal m-image img-sd '
                 src='{{'{link}'}}' alt='{{__('Hình ảnh')}}' width="100px" height="100px">
            <span class="delete-img-sv" style="display: block;">
                <a href="javascript:void(0)" onclick="productImage.removeImage(this)">
                    <i class="la la-close"></i>
                </a>
            </span>
        </div>
    </script>
    <script type="text/template" id="type-receipt-tpl">
        <div class="row">
            <label class="col-lg-3 font-13">{label}:<span
                        style="color:red;font-weight:400">{money}</span></label>
            <div class="input-group input-group-sm col-lg-6" style="height: 30px;">
                <input onkeyup="receipt.changeAmountReceipt(this)" style="color: #008000"
                       class="form-control m-input amount" placeholder="{{__('Nhập giá tiền')}}"
                       aria-describedby="basic-addon1"
                       name="{name_cash}" id="{id_cash}" value="0">
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon1">{{__('VNĐ')}}
                    </span>
                </div>
            </div>
        </div>
    </script>
    <script type="text/template" id="payment_method_tpl">
        <div class="row mt-3 method payment_method_{id}">
            <label class="col-lg-4 font-13">{label}:<span
                        style="color:red;font-weight:400">{money}</span></label>
            <div class="input-group input-group-sm col-lg-6" style="height: 30px;">
                <input onkeyup="receipt.changeAmountReceipt(this)" style="color: #008000" class="form-control m-input"
                       placeholder="{{__('Nhập giá tiền')}}"
                       aria-describedby="basic-addon1"
                       name="payment_method" id="payment_method_{id}" value="0">
                <div class="input-group-append">
                    <span class="input-group-text" id="basic-addon1">{{__('VNĐ')}}
                    </span>
                </div>
            </div>
            <div class="col-lg-2" style="display:{displayQrCode};">
                <button type="button" onclick="receipt.genQrCode(this, '{id}')"
                        class="btn btn-primary btn-sm m-btn m-btn--custom color_button">
                    @lang('TẠO QR')
                </button>
            </div>
        </div>
    </script>
@endsection
