@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('CHÍNH SÁCH HOA HỒNG CHO NGƯỜI GIỚI THIỆU')}}
    </span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .modal-backdrop {
            position: relative !important;
        }
        .type_of_criteria{
            font-weight: bold;
            font-size: 20px;
        }
        .form-control-label{
            font-weight: bold;
        }
    </style>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHÍNH SÁCH HOA HỒNG CHO NGƯỜI GIỚI THIỆU')}}
                    </h3>
                    <a href="{{route('referral.addCommission')}}">
                    <div class="modal-footer" style="margin-left: 560px">
                        <div class="m-form__actions m--align-right w-100">
                            <button data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                            </button>
                        </div>
                    </div>
                    </a>
                    <a href="{{route('referral.commissionConditionService')}}"
                       class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 button_edit">
                                    <span>
                                    <i class="la la-check"></i>
                                    <span>{{__('TIẾP THEO')}}</span>
                                    </span>
                    </a>
                </div>
            </div>
        </div>
        <div class="m-wizard m-wizard--5 m-wizard--success m-wizard--step-first" id="m_wizard">
            <div class="m-portlet__padding-x">
            </div>
            <div class="m-wizard__head m-portlet__padding-x">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="m-wizard__nav">
                            <div class="steps_3">
                                <ol class="stepBar step3">
                                    <li class="step current">
                                        1
                                    </li>
                                    <li class="step current" >
                                        2
                                    </li>
                                    <li class="step" >
                                        3
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="m-wizard__form-step m-wizard__form-step--current" id="m_wizard_form_step_2">
                <div class="row">
                    <div class="col-xl-10 offset-xl-1">
                        <div class="m-form__section m-form__section--first">
                            <div class="container" style="max-width: 900px;">
                                <div class="m-portlet__body" >
                                    <h3 class="type_of_criteria">Chọn dịch vụ</h3>
                                    <form class="frmFilter ss--background">
                                        <div class="row ss--bao-filter">
                                            <div class="col-lg-3" style="flex: 0 0 50%;max-width: 50%;">
                                                <div class="input-group">
                                                    <input type="hidden" name="search_type" value="product_name">
                                                    <button class="btn btn-primary btn-search" style="display: none">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                    <select class="form-control select2">
                                                        <option value="">Chọn nhóm dịch vụ</option>
                                                        <option value="1">Giá trị 1</option>
                                                        <option value="2">Giá trị 2</option>
                                                        <option value="3">Giá trị 3</option>
                                                        <option value="4">Giá trị 4</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3" style="flex: 0 0 50%;max-width: 50%;">
                                                <div class="form-group m-form__group">
                                                    <div class="input-group">
                                                        <input type="hidden" name="search_type" value="product_name">
                                                        <button class="btn btn-primary btn-search" style="display: none">
                                                            <i class="fa fa-search"></i>
                                                        </button>
                                                        <select class="form-control select2">
                                                            <option value="">Chọn dịch vụ</option>
                                                            <option value="1">Giá trị 1</option>
                                                            <option value="2">Giá trị 2</option>
                                                            <option value="3">Giá trị 3</option>
                                                            <option value="4">Giá trị 4</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="m-portlet__body" style="padding: 1rem 0rem;" >
                                    <div class="table-responsive">
                                        <table class="table table-striped m-table ss--header-table">
                                            <thead>
                                            <tr class="ss--nowrap">
                                                <th class="ss--font-size-th ss--text-center">#</th>
                                                <th class="ss--font-size-th ss--text-center">{{__('Hành động')}}</th>
                                                <th class="ss--font-size-th ">{{__('Nhóm dịch vụ')}}</th>
                                                <th class="ss--font-size-th ss--text-center">{{__('Tên dịch vụ')}}</th>
                                                <th class="ss--font-size-th ss--text-center">{{__('Giá dịch vụ')}}</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr class="ss--font-size-13 ss--nowrap">
                                                <td class="ss--text-center">#</td>
                                                <td class="ss--text-center">
                                                    <a href="javascript:void(0)" onclick="listPackage.delete('14')" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" title="Xóa">
                                                        <i class="la la-trash"></i>
                                                    </a>
                                                </td>
                                                <td>Chăm sóc mặt</td>
                                                <td class="ss--text-center">Chăm sóc mặt</td>
                                                <td class="ss--text-center">100.000</td>
                                            </tr>
                                            <tr class="ss--font-size-13 ss--nowrap">
                                                <td class="ss--text-center">#</td>
                                                <td class="ss--text-center">
                                                    <a href="javascript:void(0)" onclick="listPackage.delete('14')" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" title="Xóa">
                                                        <i class="la la-trash"></i>
                                                    </a>
                                                </td>
                                                <td>Chăm sóc mặt</td>
                                                <td class="ss--text-center">Chăm sóc mặt</td>
                                                <td class="ss--text-center">100.000</td>
                                            </tr>
                                            <tr class="ss--font-size-13 ss--nowrap">
                                                <td class="ss--text-center">#</td>
                                                <td class="ss--text-center">
                                                    <a href="javascript:void(0)" onclick="listPackage.delete('14')" class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" title="Xóa">
                                                        <i class="la la-trash"></i>
                                                    </a>
                                                </td>
                                                <td>Chăm sóc mặt</td>
                                                <td class="ss--text-center">Chăm sóc mặt</td>
                                                <td class="ss--text-center">100.000</td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('admin::product.modal.excel-image')
        @endsection
        @section('after_script')

            <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
            <script src="{{asset('static/backend/js/admin/product/list.js?v='.time())}}" type="text/javascript"></script>

            <script>
                $('.select2').select2();
            </script>
@stop

