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
                    <a href="{{route('referral.chooseGroupService')}}">
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
                    <a href="javascript:void(0)"
                       class="btn btn-success color_button son-mb  m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10 button_edit"
                        onclick="Commission.save()">
                                    <span>
                                    <i class="la la-check"></i>
                                    <span>{{__('LƯU')}}</span>
                                    </span>
                    </a>
{{--                    <div class="row form-group submit">--}}
{{--                        <div class="col-xl-12 col-lg-12 d-flex justify-content-end pr-5">--}}
{{--                            <button type="button" onclick="SurveyPublic.save()"--}}
{{--                            < class="btn btn-primary color_button btn-search kt-padding-l-40 kt-padding-r-40">--}}
{{--                            <i class="la la-check"></i>--}}
{{--                            {{ __('HOÀN THÀNH') }}--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
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
                                    <li class="step current" >
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
                                    <h4 class="type_of_criteria">Điều kiện tính hoa hồng:<b class="text-danger">*</b></h4>
                                    <span>Đơn hàng đã thanh toán thành công:</span>
                                    <div class="m-portlet__body" style="padding: 1rem 0rem;" >
                                        <div class="table-responsive">
                                            <table class="table table-striped m-table ss--header-table">
                                                <thead>
                                                <tr class="ss--nowrap">
                                                    <th class="ss--font-size-th ss--text-center">Hoa hồng cho 1 sản phẩm/dịch vụ/thẻ dịch vụ:<b class="text-danger">*</b></th>
                                                    <th class="ss--font-size-th ss--text-center">Giá trị hoa hồng tối đa</th>

                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="ss--font-size-13 ss--nowrap" >
                                                    <td style="width:300px">
                                                        <div class="input-group" style="">
                                                            <input type="text" class="form-control m-input numeric_child" id="order-commission-value-1" name="order-commission-value" value="0" aria-invalid="false">

                                                            <div class="input-group-append">
                                                                <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                                    <label class="btn btn-secondary active">
                                                                        <input type="radio" name="config-operation-1" checked="" value="0"> VNĐ                                </label>
                                                                    <label class="btn btn-secondary">
                                                                        <input type="radio" name="config-operation-1" value="1">
                                                                        %
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <span class="error_valid_commission_value_1 color_red"></span>
                                                    </td>
                                                    <td style="width:300px">
                                                        <div class="input-group" style="padding-left: 0px;">
                                                            <input type="text" class="form-control m-input numeric_child" id="max-order-1" name="max-order" value="0" aria-invalid="false">

                                                            <div class="input-group-append">
                                                                <span class="input-group-text text_type_default">VNĐ</span>
                                                            </div>
                                                        </div>
                                                        <span class="error_valid_max_value_1 color_red"></span>
                                                    </td>
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
        </div>
        @include('admin::product.modal.excel-image')
        @endsection
        @section('after_script')

            <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
            <script src="{{asset('static/backend/js/admin/product/list.js?v='.time())}}" type="text/javascript"></script>

            <script>
                $('.select2').select2();
            </script>
            <script>
                var Commission = {
                    save: function(){
                        $.ajax({
                            success: function (res) {
                                swal("Lưu thành công!", "Nhấn OK để tiếp tục!", "success");
                            }
                        });
                    }

                }

            </script>
@stop

