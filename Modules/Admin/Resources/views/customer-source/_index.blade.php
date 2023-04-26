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
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--creative m-portlet--first m-portlet--bordered-semi" id="autotable">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
                            <div class="m-input-icon m-portlet__head-icon">
                            </div>
                            <h3 class="m-portlet__head-text">
                            </h3>
                            <h2 style=" white-space:nowrap"
                                class="m-portlet__head-label m-portlet__head-label--primary">
                                <span><i class="la 	la-sitemap m--margin-right-5"></i>{{__('DANH SÁCH NGUỒN KHÁCH HÀNG')}} </span>
                            </h2>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <a href="javascript:void(0)"
                           data-toggle="modal"
                           data-target="#modalAdd"
                           onclick="customerSource.clearAdd()"
                           class="btn btn-primary m-btn m-btn--icon m-btn--pill">
                        <span>
						    <i class="fa flaticon-plus"></i>
							<span> {{__('Thêm nguồn khách hàng')}}</span>
                        </span>
                        </a>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <!--begin: Search Form -->
                    <form class="frmFilter">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group m-form__group">
                                    <div class="input-group">
                                        <input type="hidden" name="search_type" value="customer_source_name">
                                        <button class="btn btn-primary btn-search" style="display: none">
                                            <i class="fa fa-search"></i>
                                        </button>
                                        <input type="text" class="form-control" name="search_keyword"
                                               placeholder="{{__('Nhập tên nguồn khách hàng')}}">
                                        <div class="input-group-append">
                                            <a href="javascript:void(0)" onclick="customerSource.search()"
                                               class="btn btn-primary m-btn--icon">
                                                {{__('Tìm kiếm')}}
                                                <i class="la la-search"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="m-form m-form--label-align-right m--margin-bottom-20">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="row">
                                        @php $i = 0; @endphp
                                        @foreach ($FILTER as $name => $item)
                                            @if ($i > 0 && ($i % 4 == 0))
                                    </div>
                                    <div class="form-group m-form__group row align-items-center">
                                        @endif
                                        @php $i++; @endphp
                                        <div class="col-lg-6 input-group">
                                            @if(isset($item['text']))
                                                <div class="input-group-append">
                                                    <span class="input-group-text">
                                                        {{ $item['text'] }}
                                                    </span>
                                                </div>
                                            @endif
                                            {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input','title'=>'Chọn trạng thái']) !!}
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <div class="table-content">
                        @include('admin::customer-source.list')
                    </div><!-- end table-content -->
                </div>

            </div>
            <!--end::Portlet-->
        </div>
    </div>
    <div class="modal fade show" id="modalAdd" role="dialog">
        <div class="modal-dialog" role="document">
            <!-- Modal content-->
            @include('admin::customer-source.add')
        </div>
    </div>
    <div class="modal fade show" id="modalEdit" role="dialog">
        <div class="modal-dialog" role="document">
            <!-- Modal content-->
            @include('admin::customer-source.edit')
        </div>
    </div>
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/admin/customer-source/list.js')}}" type="text/javascript"></script>
@stop

