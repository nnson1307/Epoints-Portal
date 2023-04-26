@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-services-card.png')}}" alt="" style="height: 20px;">
        {{__('THẺ DỊCH VỤ')}}
    </span>
@endsection
@section('content')
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-server"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHI TIẾT THẺ TIỀN ĐÃ BÁN')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body" id="autotable">
            <form class="frmFilter">

            </form>
            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Mã thẻ')}}: </label> <label for="">{{$code}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Loại thẻ')}}:</label> <label for="">
                                    {{--{{$detailCardSold['service_card_type']=='service'?'{{__('Thẻ dịch vụ')}}':'{{__('Thẻ tiền')}}'}}--}}
                                    {{__('Thẻ tiền')}}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Số lần sử dụng')}}:</label> <label for=""> 1 Lần</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Số tiền')}}:</label> <label for="">
                                    {{number_format($detailCardSold['money'],0,"",",")}} {{__('VNĐ')}}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('KH sử dụng')}}:</label>
                                <label for="">{{$detailCardSold['customer_name']}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Ngày sử dụng')}}:</label>
                                <label for="">
                                    {{$detailCardSold['actived_date']!=''?date_format(new DateTime($detailCardSold['actived_date']), 'd/m/Y'):''}}
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Trạng thái')}}: </label>
                                <label for="">
                                    @if($detailCardSold['actived_date']!='')
                                        <h6 class="m--font-success">{{__('Đã kích hoạt')}}</h6>
                                    @else
                                        <h6 class="m--font-danger">{{__('Chưa kích hoạt')}}</h6>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="col-lg-12">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.service-card.sold.service-money')}}"
                           class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                                           <span class="ss--text-btn-mobi">
                                            <i class="la la-arrow-left"></i>
                                            <span>{{__('HỦY')}}</span>
                                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section("after_style")

@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service-card/sold/service-card.js?v='.time())}}"
            type="text/javascript"></script>
@stop


