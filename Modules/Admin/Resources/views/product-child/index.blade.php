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
@section('before_style')
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            border: 1px solid #ddd;
        }
        th, td {
            text-align: left;
        }
        tr:nth-child(even) {
            background-color: #f2f2f2
        }
        .select2-selection__rendered {
            line-height: 10px !important;
        }
        .select2-selection {
            height: 14px !important;
        }

        .modal-custom {
            max-width: 80%;
            margin: 0 auto;
        }
    </style>
@endsection
<style>
    #product_suggest .select2-container{
        width: 100% !important;
    }
    .block-tags .select2-search__field {
        width: 100% !important;
    }
</style>
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--tabs">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                             <span class="m-portlet__head-icon">
                                <i class="la la-th-list"></i>
                             </span>
                            <h3 class="m-portlet__head-text">
                                {{__('CẤU HÌNH SẢN PHẨM THƯƠNG MẠI')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <ul class="nav nav-tabs m-tabs m-tabs-line m-tabs-line--right m-tabs-line-danger"
                            role="tablist">
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link active show" data-toggle="tab" href="#new"
                                   onclick="productChild.tabCurrent('new')"
                                   role="tab" aria-selected="false">
                                    {{__('MỚI')}}
                                </a>
                            </li>
{{--                            <li class="nav-item m-tabs__item">--}}
{{--                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#sale"--}}
{{--                                   onclick="productChild.tabCurrent('sale')"--}}
{{--                                   role="tab" aria-selected="false">--}}
{{--                                    GIẢM GIÁ--}}
{{--                                </a>--}}
{{--                            </li>--}}
                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#best_saller"
                                   onclick="productChild.tabCurrent('best_seller')"
                                   role="tab" aria-selected="false">
                                    {{__('BÁN CHẠY')}}
                                </a>
                            </li>

                            <li class="nav-item m-tabs__item">
                                <a class="nav-link m-tabs__link" data-toggle="tab" href="#product_suggest"
                                   onclick="productChild.tabCurrent('product_suggest')"
                                   role="tab" aria-selected="false">
                                    {{__('GỢI Ý')}}
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="m-portlet__body padding-5p2rem">
                    <div class="tab-content">
                        <div class="tab-pane active" id="new" role="tabpanel">
                            @include('admin::product-child.tab.new')
                        </div>
{{--                        <div class="tab-pane " id="sale" role="tabpanel">--}}
{{--                            @include('admin::product-child.tab.sale')--}}
{{--                        </div>--}}
                        <div class="tab-pane " id="best_saller" role="tabpanel">
                            @include('admin::product-child.tab.best-seller')
                        </div>
                        <div class="tab-pane " id="product_suggest" role="tabpanel">
                            @include('admin::product-child.tab.product-suggest')
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Portlet-->

        </div>
    </div>
    <div id="append-popup">

    </div>
@endsection
@section('after_script')
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
        $('.select2-suggest').select2();
        @if(count($getListProductSuggestConfig['listConfig']) != 0)
            var countSuggest = parseInt("{{count($getListProductSuggestConfig['listConfig'])}}");
        @else
            var countSuggest = 0;
        @endif


    </script>

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script>
        $(document).ready(function () {
            $(".suggest_tags").select2({
                placeholder: 'Chọn tags sản phẩm',
                language: {
                    noResults: function() {
                        return 'Không tìm thấy tags sản phẩm ';
                    },
                },
            });

            new AutoNumeric.multiple('{{$getListProductSuggestConfig['keyNumber']}}', {
                currencySymbol: '',
                decimalCharacter: '.',
                digitGroupSeparator: ',',
                decimalPlaces: 0,
                eventIsCancelable: true,
                minimumValue: 0
            });
        })
    </script>
    <script type="text/template" id="product-childs">
        <tr>
            <td class="ss--text-center stt hihhi">
                {stt}
            </td>
            <td>
                {product_child_name}
                <input type="hidden" class="product_child_id" value="{product_child_id}">
            </td>
            <td class="ss--text-center">
                {price}
            </td>
            <td class="ss--text-center">
                {unit}
            </td>
            <td class="ss--text-center">
                {cost}
            </td>
            <td>
                <button onclick="productChild.removeTr(this)"
                        class="ss--margin-top--8px m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Xóa">
                    <i class="la la-trash"></i>
                </button>
            </td>
        </tr>
    </script>
    <script type="text/template" id="product-childs-sale">
        <tr>
            <td class="ss--text-center stt hihhi">
                {stt}
            </td>
            <td>
                {product_child_name}
                <input type="hidden" class="product_child_id" value="{product_child_id}">
            </td>
            <td class="ss--text-center">
                {price}
            </td>
            <td class="ss--text-center">
                {unit}
            </td>
            <td class="ss--text-center">
                {cost}
            </td>
            <td class="ss--font-size-13 ss--text-center" style="width: 150px">
                <input class="form-control2 m-input input-percent-sale
                       ss--text-center ss--width-150"
                        value="0">
            </td>
            <td>
                <button onclick="productChild.removeTr(this)"
                        class="ss--margin-top--8px m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="{{__('Xóa')}}">
                    <i class="la la-trash"></i>
                </button>
            </td>
        </tr>
    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.js"></script>
    <script src="{{asset('static/backend/js/admin/product-child/script.js?v='.time())}}" type="text/javascript"></script>
@stop
