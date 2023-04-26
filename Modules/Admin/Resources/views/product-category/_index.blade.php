@extends('layout')
@section('content')

    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .form-control-feedback {
            color: red;
        }
    </style>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <h3 class="m--font-success m--font-bold m-portlet__head-text">
                        <i class="la la-th-list m--margin-right-5"></i>
                        {{__('DANH SÁCH NHÓM SẢN PHẨM')}}
                    </h3>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                <a href="javascript:void(0)"
                   data-toggle="modal"
                   data-target="#modalAdd"
                   onclick="productCategory.clearModalAdd()"
                   class="btn btn-primary m-btn m-btn--icon m-btn--pill">
                                <span>
                                    <i class="fa flaticon-plus"></i>
                                    <span> {{__('THÊM DANH MỤC')}}</span>
                                </span>
                </a>
            </div>
        </div>
        <div class="m-portlet__body">
            <form class="frmFilter m--margin-top-10 m--margin-top-20">
                <div class="m-demo__preview">
                    <div class="alert alert-success2">
                        <div class="row m--margin-top-15">
                            <div class="col-lg-3">
                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <input type="hidden" name="search_type" value="category_name">
                                        <button class="btn btn-primary btn-search" style="display: none">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <input type="text" class="form-control" name="search_keyword"
                                               placeholder="{{__('Nhập tên danh mục')}}">
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
                                    <div class="col-lg-12 input-group">
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
                                <a href="javascript:void(0)" onclick="productCategory.search()"
                                   class="btn btn-info m-btn--icon">
                                    Tìm kiếm
                                    <i class="la la-search"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="m-form m-form--label-align-right m--margin-bottom-20">
                </div>
            </form>
            <div class="table-content">
                @include('admin::product-category.list')
            </div><!-- end table-content -->
        </div>
    </div>
    <div class="modal fade" id="modalAdd" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            @include('admin::product-category.add')
        </div>
    </div>
    <div class="modal fade" id="modalEdit" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            @include('admin::product-category.edit')
        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/product-category/list.js')}}" type="text/javascript"></script>
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@stop
