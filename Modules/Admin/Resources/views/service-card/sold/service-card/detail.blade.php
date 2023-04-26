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
    <style>
        .img-70 {
            position: relative;
            width: 70px;
            height: 70px;
            border-radius: 5px
        }
    </style>
    <div class="m-portlet">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="la la-server"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHI TIẾT THẺ DỊCH VỤ ĐÃ BÁN')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>

        <div class="m-portlet__body" id="autotable">
            <form class="frmFilter">
                <input type="hidden" id="code" name="code" value="{{$code}}">
            </form>
            <div class="row">
                <div class="col-lg-4">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Mã thẻ')}}:</label> <label for="">{{$code}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Loại thẻ')}}: </label> <label for="">{{__('Thẻ dịch vụ')}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Hạn sử dụng')}}:</label> <label for="">
                                    {{$detailCardSold['expired_date']!=''?date_format(new DateTime($detailCardSold['expired_date']), 'd/m/Y'):''}}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Số lần sử dụng')}}:</label>
                                <label for="">
                                    @if($detailCardSold['number_using']==0)
                                        {{__('Không giới hạn')}}
                                    @else
                                        {{$detailCardSold['number_using']}}
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>@lang('Còn lại'):</label>
                                <label for="">
                                    @if($detailCardSold['number_using']==0)
                                        {{__('Không giới hạn')}}
                                    @else
                                        {{$detailCardSold['number_using']-$detailCardSold['count_using']}}
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Trạng thái')}}:</label>
                                <label for="">
                                    @if($detailCardSold['is_actived'] == 1)
                                        @if ($detailCardSold['is_deleted'] == 0)
                                            <h6 class="m--font-success">{{__('Đã kích hoạt')}}</h6>
                                        @else
                                            <h6 class="m--font-danger">{{__('Đã huỷ')}}</h6>
                                        @endif
                                    @else
                                        <h6 class="m--font-danger">{{__('Chưa kích hoạt')}}</h6>
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>@lang('Ghi chú'):</label>
                                <label for="">
                                    {{$detailCardSold['note']}}
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-content list-history">
                @include('admin::service-card.sold.service-card.list-detail')
            </div>
        </div>
        <div class="modal-footer">
            <div class="col-lg-12">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.service-card.sold.service-card')}}"
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
    @include('admin::service-card.sold.service-card.modal-add-image')
    @include('admin::service-card.sold.service-card.carousel')
@endsection
@section("after_style")

@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/service-card/sold/dropzone.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/service-card/sold/service-card.js?v='.time())}}"
            type="text/javascript"></script>
    <script type="text/template" id="imageOld">
        <div class="wrap-img image-show-child list-image-old mr-2">
            <input type="hidden" name="image_id" value="{id_image}" class="service_card_sold_image">
            <img class='m--bg-metal m-image img-sd '
                 src='{{'{link}'}}' alt='{{__('Hình ảnh')}}' width="100px" height="100px">
            <span class="delete-img" style="display: block;">
                <a href="javascript:void(0)" onclick="serviceCardSoldImage.remove_img(this)">
                    <i class="la la-close"></i>
                </a>
            </span>

        </div>
    </script>

    <script type="text/template" id="template-tpl">
        <div class="carousel-item {status}">
            <img class="d-block w-100" src="{image}" height="500px">
        </div>
    </script>

    <script type="text/template" id="append-image">
        <div class="img-70 image-show-child list-image-new">
            <img class='m--bg-metal img-sd '
                 src="{link}" width="50px" height="50px">
        </div>
    </script>
@stop

