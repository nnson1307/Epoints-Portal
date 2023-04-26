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

        .type_of_criteria {
            font-weight: bold;
            font-size: 20px;
        }

        .form-control-label {
            font-weight: bold;
        }
        #week {
            border: 2px solid white;
            box-shadow: 0 0 0 1px #4fc4cb;
            appearance: none;
            border-radius: 50%;
            width: 12px;
            height: 12px;
            background-color: #fff;
            transition: all ease-in 0.2s;
        }

        .stepBar.step3 .step {
            width: 25%;
        }

        #week:checked {
            background-color: #4fc4cb;
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
                    <a href="{{route('referral.chooseOrderPrice',['id'=>$referral_program_id])}}">
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
                       onclick="CommissionCondition.save({{$referral_program_id}})">
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
                                       {{__('Thông tin hoa hồng')}}
                                    </li>
                                    <li class="step current">
                                        {{__('Chọn sản phẩm')}}
                                    </li>
                                    <li class="step current">
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
                <div >
                    <form id="condition-order-price">
                        <input type="hidden" id="referral_program_id" name="referral_program_id"
                               value="{{$referral_program_id}}">
                        <div class="col-xl-10 offset-xl-1">
                            <div class="m-form__section m-form__section--first">
                                <div class="container" style="max-width: 900px;">
                                    <div class="m-portlet__body">
                                        <h4 class="type_of_criteria">{{__('Điều kiện tính hoa hồng:')}}<b
                                                    class="text-danger">*</b></h4>
                                        <span>{{__('Đơn hàng đã thanh toán (toàn phần) thành công:')}}</span>
                                        <div style="display:flex;margin-top: 10px;">
                                        <span class="m-switch m-switch--icon m-switch--success m-switch--sm"
                                              style="align-items: center;display: flex;}">
                                            <label style="margin: 0 0 0 0px; padding-top: 0px">
                                                <input type="checkbox"
                                                       checked class="manager-btn" name="cps.total_order.is_transport_fee">
                                                <span></span>
                                            </label>
                                        </span>
                                            <span style="margin-top:5px"> &nbsp{{__(' Bao gồm phí vận chuyển ')}}</span>
                                        </div>
                                        <h4 class="type_of_criteria" style="margin-top:10px">{{__('Công thức tính:')}}<b
                                                    class="text-danger">*</b></h4>
                                        <input type="radio" id="week" name="cps.total_order.condition" value="1" checked>
                                          <label for="week">{{__('Trên mỗi đơn hàng')}}</label><br>
                                        <div class="m-portlet__body" style="padding: 1rem 0rem;">
                                            <div class="table-responsive">
                                                <table class="table table-striped m-table ss--header-table">
                                                    <thead>
                                                    <tr class="ss--nowrap">
                                                        <th class="ss--font-size-th ss--text-center">{{__('Hoa hồng cho 1 sản phẩm/dịch vụ/thẻ dịch vụ:')}}<b class="text-danger">*</b></th>
                                                        <th class="ss--font-size-th ss--text-center">{{__('Giá trị hoa hồng tối đa')}}
                                                        </th>

                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    <tr class="ss--font-size-13 ss--nowrap">
                                                        <td style="width:300px">
                                                            <div class="input-group" style="">
{{--                                                                {{dd($infoCondition,123)}}--}}
                                                                @if(count($infoCondition) == 0)
                                                                    <input type="text"
                                                                           class="form-control m-input numeric_child"
                                                                           id="order-commission-value"
                                                                           name="commission_value" value="0"
                                                                           aria-invalid="false">
                                                                    <input type="hidden" id="commission_type_condition" value="" name="commission_type_condition">
                                                                    <div class="input-group-append">
                                                                        <div class="btn-group btn-group-toggle"
                                                                             data-toggle="buttons">

                                                                            <label class="btn btn-secondary" onclick="CommissionCondition.change('percent')">
                                                                                <input type="radio"
                                                                                       id="commission_type_money"
                                                                                       name="commission_type"
                                                                                       value="percent"
                                                                                        {{isset($info['commission_type_choose']) && $info['commission_type_choose'] == '%' ? 'checked' : ''}}>
                                                                                %
                                                                            </label>
                                                                            <label class="btn btn-secondary active"  onclick="CommissionCondition.change('money')">
                                                                                <input type="radio"
                                                                                       id="commission_type_percent"
                                                                                       name="commission_type"
                                                                                       value="money"
                                                                                        {{isset($info['commission_type_choose']) && $info['commission_type_choose'] == 'VNĐ' ? 'checked' : ''}}>
                                                                                VNĐ
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                @else
                                                                    <input type="text"
                                                                           class="form-control m-input numeric_child"
                                                                           id="order-commission-value"
                                                                           name="commission_value"
                                                                           value="{{$infoCondition['commission_value']}}"
                                                                           aria-invalid="false">
                                                                    <input type="hidden" id="commission_type_condition" value="{{$infoCondition['commission_type']}}" name="commission_type_condition" >
                                                                    <div class="input-group-append">
                                                                        <div class="btn-group btn-group-toggle"
                                                                             data-toggle="buttons">
                                                                            <label class="btn btn-secondary commission_type_percent"  onclick="CommissionCondition.change('percent')">
                                                                                <input type="radio"
                                                                                       id="commission_type_percent" checked
                                                                                       name="commission_type"
                                                                                       value="percent" >
                                                                                %
                                                                            </label>
                                                                            <label class="btn btn-secondary commission_type_money" onclick="CommissionCondition.change('money')">
                                                                                <input type="radio"
                                                                                       id="commission_type_money"
                                                                                       name="commission_type"
                                                                                       value="money" >
                                                                                VNĐ
                                                                            </label>
                                                                        </div>
                                                                    </div>
                                                                @endif
                                                            </div>
                                                            <span class="error_valid_commission_value_1 color_red"></span>
                                                        </td>
                                                        <td style="width:300px">
                                                            <div class="input-group" id="input_max_order" style="padding-left: 0px;display:none" >
                                                                <input type="text"
                                                                       class="form-control m-input numeric_child"
                                                                       id="max-order" name="commission_max_value"
                                                                       value="{{$infoCondition['commission_max_value']}}"
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
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @include('admin::product.modal.excel-image')
        @endsection
        @section('after_script')

            <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>

            <script src="{{asset('static/backend/js/admin/product/list.js?v='.time())}}"
                    type="text/javascript"></script>
            <script src="{{asset('static/backend/js/admin/affiliate/add.js?v='.time())}}"
                    type="text/javascript"></script>
{{--            <script>--}}
{{--                $('.select2').select2();--}}
{{--                new AutoNumeric.multiple('#max-order,#order-commission-value',  {--}}
{{--                    currencySymbol: '',--}}
{{--                    decimalCharacter: '.',--}}
{{--                    digitGroupSeparator: ',',--}}
{{--                    decimalPlaces: 0--}}
{{--                });--}}

{{--            </script>--}}
{{--            <script>--}}
{{--                var CommissionCondition = {--}}
{{--                    save: function () {--}}
{{--                        $.ajax({--}}
{{--                            url: laroute.route("referral.saveConditonOrderPrice"),--}}
{{--                            method: "POST",--}}
{{--                            data: $("#condition-order-price").serialize(),--}}
{{--                            success: function (res) {--}}
{{--                                if (res.error == true) {--}}
{{--                                    swal("Lỗi", res.message, "error");--}}
{{--                                } else {--}}
{{--                                    swal("Lưu thành công!", "Nhấn OK để tiếp tục!", "success").then(function () {--}}
{{--                                        window.location.href = laroute.route("referral.policyCommission")--}}
{{--                                    });--}}
{{--                                }--}}
{{--                            }--}}
{{--                        });--}}
{{--                    },--}}
{{--                    change: function (obj) {--}}
{{--                        if (obj== "percent") {--}}
{{--                            $("#input_max_order").show();--}}
{{--                            $('#commission_type_condition').val('percent');--}}
{{--                        } else {--}}
{{--                            $("#input_max_order").hide();--}}
{{--                            $('#commission_type_condition').val('money')--}}
{{--                        }--}}
{{--                    },--}}
{{--                };--}}
{{--                if( $('#commission_type_condition').val()  == 'percent'){--}}
{{--                    $(".commission_type_percent").trigger("click");--}}
{{--                }else{--}}
{{--                    $(".commission_type_money").trigger("click");--}}
{{--                }--}}

{{--            </script>--}}
@stop

