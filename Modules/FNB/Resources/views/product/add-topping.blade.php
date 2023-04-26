@extends('layout')
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <style>
        .bg {
            background-color: #dff7f8;
            white-space: nowrap;
        }
    </style>
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ SẢN PHẨM')}}
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
                        {{__('Sản phẩm đi kèm')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
{{--                {{dd(redirect()->getUrlGenerator()->previous())}}--}}
                <a href="{{route('admin.product.edit',['id' => $id])}}"
                   class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                    <span class="ss--text-btn-mobi">
                    <i class="la la-arrow-left"></i>
                    <span>{{__('Trở lại')}}</span>
                    </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <form id="edit-product">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="col-12 ">
                            <div class="form-group m-form__group ">
                                <div class="row">
                                    <div class="col-4">
                                        <p>{{__('Sản phẩm đi kèm')}}</p>
                                    </div>
                                    <div class="col-8 text-right">
                                            <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                                <label>
                                                    <input type="checkbox" class="manager-btn-topping" name="is_topping" id="is_topping" {{$data['is_topping'] == 1 ? 'checked' : ''}}>
                                                    <span></span>
                                                </label>
                                            </span>
                                    </div>
                                </div>
                            </div>
                            <div class="block-topping" >
                                <div class="form-group m-form__group">
{{--                                    <select class="form-control list-topping-select" onchange="product.changeSelectTopping()">--}}
                                    <select class="form-control list-topping-select">
                                        <option value="">{{__('Chọn sản phẩm đính kèm')}}</option>
                                    </select>
                                </div>
                                <div class="form-group m-form__group list-topping">
                                    <div class="table-responsive">
                                        <table class="table m-table m-table--head-bg-default">
                                            <thead class="bg">
                                                <tr>
                                                    <th width="10%">#</th>
                                                    <th width="60%">{{__('Tên sản phẩm')}}</th>
                                                    <th width="30%">{{__('Số lượng')}}</th>
                                                    <th width="10%"></th>
                                                </tr>
                                            </thead>
                                            <tbody class="block-list-topping">
                                            @if(count($listTopping) != 0)
                                                @foreach(array_values($listTopping) as $key => $item)
                                                    <tr class="product_child_id_{{$item['product_child_id']}}">
                                                        <td>{{$key+1}}</td>
                                                        <td>
                                                            <input type="hidden" class="product_child_id" name="list[{{$item['product_child_id']}}][product_child_id]" value="{{$item['product_child_id']}}">
                                                            <input type="hidden" class="product_child_name" name="list[{{$item['product_child_id']}}][product_child_name]" value="{{$item['product_child_name']}}">
                                                            {!! $item['product_child_name'] !!}
                                                        </td>
                                                        <td><input class="quantity text-center" id="quantity_{{$item['product_child_id']}}" onchange="product.changeSelectTopping(true,'{{$item["product_child_id"]}}')" type="text" value="{{$item['quantity']}}" name="list[{{$item['product_child_id']}}][quantity]"></td>
                                                        <td>
                                                            <a class="remove_product" href="javascript:void(0)" style="color: #a1a1a1" onclick="product.removeTopping('{{$item["product_child_id"]}}')">
                                                                <i class="la la-trash"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <input id="idHidden" type="hidden" name="product_id" value="{{$id}}">
            </form>
        </div>
        <div class="modal-footer m--margin-right-20">
            <div class="form-group m-form__group m--margin-top-10">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.product.edit',['id' => $id])}}"
                           class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                                            <span class="ss--text-btn-mobi">
                                            <i class="la la-arrow-left"></i>
                                            <span>{{__('HỦY')}}</span>
                                            </span>
                        </a>
                        <button type="button" onclick="product.saveTopping()"
                                class="ss--btn-mobiles save-change btn-save m--margin-left-10 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-bottom-5">
                                                <span class="ss--text-btn-mobi">
                                            <i class="la la-check"></i>
                                            <span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
                                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('after_script')

    <script src="{{asset('static/backend/js/fnb/product/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        product._init();
        $(".quantity").TouchSpin({min: 1});
    </script>

@stop
