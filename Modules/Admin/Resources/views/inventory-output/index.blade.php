@extends('layout')
@section('title_header')
    <span class="title_header"><img
                src="{{asset('uploads/admin/icon/icon-kho.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ KHO')}}
    </span>
@endsection
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('content')
    <div class="m-portlet" id="autotable">
        <form class="frmFilter">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="ss--title m--font-bold m-portlet__head-text">
                            <i class="la la-th-list ss--icon-title m--margin-right-5"></i>
                            {{__('DANH SÁCH PHIẾU XUẤT KHO')}}
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                    <a href="javascript:void(0)"
                       onclick="location.href='{{route('admin.inventory-output.add')}}'"
                       class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span> {{__('TẠO PHIẾU XUẤT KHO')}}</span>
                        </span>
                    </a>
                    <a  onclick="location.href='{{route('admin.inventory-output.add')}}'" class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                color_button btn_add_mobile" style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="ss--background">
                    <div class="row ss--bao-filter2">
                        <div class="col-lg-6">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="po_code">
                                    <button id="search" class="btn btn-primary btn-search"
                                            style="display: none"></button>
                                    <input type="text" class="form-control" name="search_keyword"
                                           placeholder="{{__('Nhập mã phiếu')}}">
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <input onkeyup="InventoryOut.removeAllInput(this)"
                                       class="form-control m-input daterange-picker" id="created_at2"
                                       name="created_at" autocomplete="off" placeholder="{{__('Chọn ngày tạo')}}ngày tạo">
                                <span class="m-input-icon__icon m-input-icon__icon--right">
                                 <span><i class="la la-calendar"></i></span></span>
                            </div>
                        </div>
                    </div>
                    <div class="row m--padding-left-15 m--padding-right-15">
                        <div class="col-lg-10">
                            <div class="row">
                                @php $i = 0; @endphp
                                @foreach ($FILTER as $name => $item)
                                    @if ($i > 0 && ($i % 4 == 0))
                            </div>
                            <div class="form-group m-form__group row align-items-center">
                                @endif
                                @php $i++; @endphp
                                <div class="col-lg-3 input-group form-group">
                                    @if(isset($item['text']))
                                        <div class="input-group-append">
                        <span class="input-group-text">
                            {{ $item['text'] }}
                        </span>
                                        </div>
                                    @endif
                                    {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input']) !!}
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-lg-2 form-group">
                            <button href="javascript:void(0)" onclick="InventoryInput.search()"
                                    class="btn ss--btn-search pull-right">
                                {{__('TÌM KIẾM')}}
                                <i class="fa fa-search ss--icon-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="table-content m--margin-top-30">
                    @include('admin::inventory-output.list')
                </div>
                <!-- end table-content -->
            </div>
        </form>

    </div>
    <input type="hidden" id="totalInput" value="">
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/inventory-output/list.js?v='.time())}}"
            type="text/javascript"></script>
@stop