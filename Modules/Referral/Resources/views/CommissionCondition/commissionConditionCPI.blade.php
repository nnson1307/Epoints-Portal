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
                    <a href="{{route('referral.editInfoCommission',['id'=> $referral_program_id])}}">
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
                       onclick="condition.saveCPI({{$referral_program_id}})">
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
                            <div class="steps_2">
                                <ol class="stepBar step3">
                                    <li class="step current" style="width: 33%">
                                       {{__(' Thông tin hoa hồng')}}
                                    </li>
                                    <li class="step current" style="width: 33%">
                                        {{__('Điều kiện tính')}}
                                    </li>
                                    <li class="step">
                                        {{__('Cấu hình tỷ lệ Chiết Khấu')}}
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
                                <form id="commission-condition">
                                <div class="m-portlet__body" >
                                    <h4 class="type_of_criteria">{{__('Điều kiện tính hoa hồng:')}}<b class="text-danger">*</b></h4>
                                    <span>{{__('Khách hàng đăng kí tài khoản và nhập mã giới thiệu thành công:')}}</span>
                                    <div style="display:flex;margin-top: 10px;">
                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm"
                                              style="align-items: center;display: flex;}">
                                            <label style="margin: 0 0 0 0px; padding-top: 0px">
                                                <input type="checkbox"
                                                       checked class="manager-btn" name="">
                                                <span></span>
                                            </label>
                                        </span>

                                        <span style="margin-top:5px"> &nbsp{{__(' Thời gian sử dụng app ')}}</span>
                                        <select style="width:50px;margin-left:5px" name="cpi.time_use_condition">
{{--                                            <option value=">" {{isset($data['compare']) && $data['compare'] == '>' ? 'selected' : ''}}> &#62</option>--}}
                                            <option value=">=" {{isset($data['compare']) && $data['compare'] == '>=' ? 'selected' : ''}}> &#8805</option>
{{--                                            <option value="<"  {{isset($data['compare']) && $data['compare'] == '<' ? 'selected' : ''}}> &#60</option>--}}
                                            <option value="<="  {{isset($data['compare']) && $data['compare'] == '<=' ? 'selected' : ''}}> &#8804</option>
                                            <option value="="  {{isset($data['compare']) && $data['compare'] == '=' ? 'selected' : ''}}> =</option>
                                        </select>
                                        &nbsp
                                        <input type="text" style="width:80px;margin-left:5px" name="cpi.time_use_time"
                                               placeholder="30" value="{{isset($data['time_use_time'])?$data['time_use_time']:'30'}}">
                                        <span style="margin-top:5px"> &nbsp{{__(' phút trong ')}}</span>
                                        <input type="text" style="width:80px;margin-left:5px" name="cpi.time_use_date"
                                               placeholder="30" value="{{isset($data['time_use_date'])?$data['time_use_date']:'30'}}">
                                        <span style="margin-top:5px"> &nbsp{{__(' ngày. ')}}</span>
                                    </div>

                                    <div class="m-portlet__body" style="padding: 1rem 0rem;width: 500px;margin-left: 156px;" >
                                        <div class="table-responsive">
                                            <table class="table table-striped m-table ss--header-table">
                                                <thead>
                                                <tr class="ss--nowrap">
                                                    <th class="ss--font-size-th ss--text-center">{{__('Hoa hồng cho 1 lần thỏa điều kiện:')}}<b class="text-danger">*</b></th>

                                                </tr>
                                                </thead>
                                                <tbody>
                                                <tr class="ss--font-size-13 ss--nowrap" >
                                                    <td>
                                                        <div class="input-group" style="padding-left: 0px;">
                                                            <input type="text" class="form-control m-input numeric_child" id="commission_value" name="commission_value"
                                                                   value="{{isset($data['commission_value'])?$data['commission_value']:'0'}}"
                                                                   aria-invalid="false">

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
                                    <input type="hidden" name="referral_program_id" value="{{$referral_program_id}}">
                                </form>
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
                new AutoNumeric.multiple('#commission_value', {
                    currencySymbol: '',
                    decimalCharacter: '.',
                    digitGroupSeparator: ',',
                    decimalPlaces: 0
                });
            </script>
            <script>
                var condition = {
                    saveCPI: function(id){
                        $.ajax({
                            url: laroute.route("referral.saveConditionCPI"),
                            method: "POST",
                            data: $("#commission-condition").serialize(),
                            success: function (res) {
                                if (res.error == false) {
                                    swal(res.message,"Nhấn OK để tiếp tục!","success").then(function () {
                                        window.location.href = laroute.route("referral.editMultiLevelConfig", {id : id})
                                    });
                                }else{
                                    swal("Lưu cấu hình thất bại!", res.message , "error").then(function () {
                                    });
                                }
                            }
                        });
                    }

                }

            </script>
@stop

