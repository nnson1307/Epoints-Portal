@extends('layout')
@section('after_style')
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
        .form-control-feedback {
            color: red;
        }
    </style>
    <!--begin::Portlet-->
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('DANH SÁCH NHÓM THUỘC TÍNH SẢN PHẨM')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('admin.product-attribute-group.add',session('routeList')))
                    <button href="javascript:void(0)"
                            data-toggle="modal"
                            data-target="#modalAdd"
                            onclick="productAttributeGroup.clearAdd()"
                            class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                                <span>
                                    <i class="fa fa-plus-circle"></i>
                                    <span> {{__('THÊM NHÓM THUỘC TÍNH')}}</span>
                                </span>
                    </button>
                    <a href="javascript:void(0)"
                       data-toggle="modal"
                       data-target="#modalAdd"
                       onclick="productAttributeGroup.clearAdd()"
                       class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                 color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter ss--background">
                <div class="row ss--bao-filter">
                    <div class="col-lg-4">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <input type="hidden" name="search_type" value="product_attribute_group_name">
                                <button class="btn btn-primary btn-search" style="display: none">
                                    <i class="fa fa-search"></i>
                                </button>
                                <input type="text" class="form-control" name="search_keyword"
                                       placeholder="{{__('Nhập tên nhóm thuộc tính')}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="row">
                            @php $i = 0; @endphp
                            @foreach ($FILTER as $name => $item)
                                @if ($i > 0 && ($i % 4 == 0))
                        </div>
                        <div class="form-group m-form__group row align-items-center">
                            @endif
                            @php $i++; @endphp
                            <div class="col-lg-12 input-group form-group">
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
                    <div class="col-lg-2">
                        <div class="form-group m-form__group">
                            <button href="javascript:void(0)" onclick="productAttributeGroup.search()"
                                    class="btn ss--btn-search">
                                {{__('TÌM KIẾM')}}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
            <div class="table-content m--margin-top-30">
                @include('admin::product-attribute-group.list')
            </div><!-- end table-content -->
        </div>
    </div>
    <!--end::Portlet-->
    <div class="modal fade" id="modalAdd" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('admin::product-attribute-group.add')
        </div>
    </div>
    <div class="modal fade" id="modalEdit" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <!-- Modal content-->
            @include('admin::product-attribute-group.edit')
        </div>
    </div>

    <div class="append-popup"></div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/product-attribute-group/list.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/fnb/product-attribute-group/script.js?v='.time())}}"
            type="text/javascript"></script>
@stop





