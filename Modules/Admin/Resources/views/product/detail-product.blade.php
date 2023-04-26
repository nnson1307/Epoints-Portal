@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ SẢN PHẨM')}}
    </span>
@endsection
@section('content')
    <style>
        .img {
            /*border-radius: 10px;*/
            /*vertical-align: middle;*/
            width: 100px;
            height: 100px;
            margin: 2px;
            float: left;
        }
        span.select2{
            width: 100% !important;
        }
        #popup-list-serial .modal-dialog {
            max-width: 70%;
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
                        {{__('CHI TIẾT SẢN PHẨM')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
            </div>
        </div>
        <div class="m-portlet__body">

            <div class="row">
                <div class="col-lg-6">
                    <div class="form-group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Tên sản phẩm')}}: </label>
                                <label>{{$dataDetailProduct->productName}}</label>
                            </div>
                        </div>
                    </div>
{{--                    <div class="form-group">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-lg-12">--}}
{{--                                <label>{{__('Tên sản phẩm')}} (EN): </label>--}}
{{--                                <label>{{$dataDetailProduct->productNameEn}}</label>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Mã sản phẩm')}}: </label>
                                <label>{{$dataDetailProduct->productCode}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Danh mục')}}: </label>
                                <label>{{$dataDetailProduct->productCategory}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Nhãn hiệu')}}:</label>
                                <label>{{$dataDetailProduct->productModelName}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Đơn vị tính')}}:</label>
                                <label>{{$dataDetailProduct->unitName}}</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Giá nhập')}}: </label>
                                <label>{{number_format($dataDetailProduct->productCost, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-12">
                                <label>{{__('Giá bán')}}: </label>
                                <label>{{number_format($dataDetailProduct->productPrice, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label>{{__('Cảnh báo tồn kho')}}:
                                    @if($dataDetailProduct->isInventoryWarning==1)
                                        {{__('Có')}}
                                    @else
                                        {{__('Không')}}
                                    @endif
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group m-form__group">
                        <div class="row">
                            <div class="col-lg-6">
                                <label>{{__('Sản phẩm quà tặng')}}:</label>
                                <label class="m-checkbox m-checkbox--air m-checkbox--solid m--margin-left-20">
                                    @if($dataDetailProduct->isPromo==1)
                                        <input  id="is-inventory-warning" checked class="check-inventory-warning"
                                               type="checkbox">
                                    @else
                                        <input  id="is-inventory-warning" class="check-inventory-warning"
                                               type="checkbox">
                                    @endif
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    @if(in_array('fnb.orders',session('routeList')))
                        <div class="form-group m-form__group">
                            <div class="row">
                                <div class="col-lg-6">
                                    <label>{{__('Loại sản phẩm đính kèm')}}:</label>
                                    <label class="m-checkbox m-checkbox--air m-checkbox--solid m--margin-left-20">
                                        <input  {{$dataDetailProduct->is_topping == 1 ? 'checked' : ''}} disabled
                                                type="checkbox">
                                        <span></span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="form-group m-form__group">
                        <span class="mr-3">{{__('Quản lý sản phẩm theo')}}</span>
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid m--margin-left-20">
                            <input disabled class="manager-btn-fix" name="inventory_management" {{in_array($dataDetailProduct['inventory_management'],['all','packet']) ? 'checked' : ''}}
                            type="checkbox" value="packet">{{__('Số lô')}}
                            <span></span>
                        </label>
                        <label class="m-checkbox m-checkbox--air m-checkbox--solid m--margin-left-20">
                            <input disabled class="manager-btn-fix" name="inventory_management" {{in_array($dataDetailProduct['inventory_management'],['all','serial']) ? 'checked' : ''}}
                            type="checkbox" value="serial">{{__('Serial number/ IMEI')}}
                            <span></span>
                        </label>
                    </div>
                </div>
            </div>
            <hr>
            <div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <div class="row">
                                        <div class="col-lg-3">
                                            <label>{{__('Hình đại diện')}}:</label>
                                        </div>
                                        <div class="input-group m-input-group col-lg-9">
                                            <div class="">
                                                @if($dataDetailProduct->avatar!= null)
                                                    <img src="{{asset($dataDetailProduct->avatar)}}" class="img">
                                                @else
                                                    <b>{{__('Không có hình đại diện')}}.</b>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group m-form__group row">
                                    <div class="col-lg-3">
                                        <labe>{{__('Hình ảnh')}}:</labe>
                                    </div>
                                    <div class="col-lg-9">
                                        @if($imageProduct->count()>0)
                                            @foreach($imageProduct as $item)
                                                <img src="{{asset($item['name'])}}" class="img">
                                            @endforeach
                                        @else
                                            <b>{{__('Không có hình ảnh')}}.</b>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-12 m--margin-top-20">
                        {{--Table version--}}
                        <div class="table-responsive">
                            <table id="add-product-version"
                                   class="table table-striped m-table ss--header-table ss--nowrap">
                                <thead>
                                <tr class="ss--font-size-th">
                                    <th>#</th>
                                    <th>{{__('MÃ SẢN PHẨM CON')}}</th>
                                    <th>{{__('TÊN SẢN PHẨM CON')}}</th>
                                    <th class="ss--text-center">{{__('TỒN KHO')}}</th>
                                    <th class="ss--text-center">{{__('GIÁ GỐC')}}</th>
                                    <th class="ss--text-center">{{__('GIÁ BÁN')}}</th>
                                    <th class="ss--text-center ss--width-150">{{__('HIỂN THỊ APP')}}</th>
                                    <th class="ss--text-center ss--width-150">{{__('MẶC ĐỊNH')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($dataProductChild as $key=>$value)
                                    <tr class="ss--font-size-13">
                                        <td>{{($key+1)}}</td>
                                        <td>{{ $value['product_code'] }}</td>
                                        <td>{{ $value['product_child_name'] }}</td>
                                        <td class="ss--text-center">
                                            @if(in_array($dataDetailProduct['inventory_management'],['all','serial']))
                                                <a href="javascipt:void(0)" onclick="detailProduct.showPopup(`{{ $value['product_code'] }}`)">{{$value['total_warehouse']}}</a>
                                            @else
                                                <a href="javascipt:void(0)">{{$value['total_warehouse']}}</a>
                                            @endif
                                        </td>
                                        <td class="ss--text-center">{{number_format($value['cost'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                                        <td class="ss--text-center">{{number_format($value['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                                        <td class="text-center">
                                            <label class="m-checkbox m-checkbox--air m-checkbox--solid">
                                                <input style="text-align: center" {{$value['is_display'] == 1 ? 'checked' : ''}} type="checkbox">
                                                <span></span>
                                            </label>
                                        </td>
                                        @include('admin::product.append.append-default',['is_master' => $value['is_master']])
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <div class="col-lg-12">
                <div class="form-group m-form__group">
                    <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
                        <div class="m-form__actions m--align-right">
                            <button onclick="location.href='{{route('admin.product')}}'"
                                    class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                                     <span class="ss--text-btn-mobi">
                                        <i class="la la-arrow-left"></i>
                                        <span>{{__('HỦY')}}</span>
                                    </span>
                            </button>
                            <button type="button"
                                    class="ss--btn-mobiles btn ss--button-cms-piospa ss--btn m--margin-left-10 m-btn m-btn--icon m-btn--wide m-btn--md btn-save m--margin-bottom-5"
                                    onclick="location.href='{{route('admin.product.edit',$dataDetailProduct->productId)}}'">
                                        <span class="ss--text-btn-mobi">
                                            <i class="la la-edit"></i>
                                            <span>{{__('CHỈNH SỬA')}}</span>
                                        </span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="showPopup"></div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/product/detail-product.js?v='.time())}}" type="text/javascript"></script>
@endsection
