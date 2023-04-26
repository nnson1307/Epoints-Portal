@extends('layout')
@section('title_header')
    <span class="title_header">@lang('ticket::acceptance.manage_acceptance')</span>
@stop
@section('after_style')
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/customize.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('static/backend/css/sinh-custom.css') }}">
    <link rel="stylesheet" href="{{ asset('static/backend/css/son.css') }}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phieu-custom.css')}}">
    <style>
        .rate {
            float: left;
            height: 46px;
            padding: 0 10px;
        }

        .rate:not(:checked)>input {
            position: absolute;
            top: -9999px;
        }

        .rate:not(:checked)>label {
            float: right;
            width: 1em;
            overflow: hidden;
            white-space: nowrap;
            cursor: pointer;
            font-size: 30px;
            color: #ccc;
        }

        .rate:not(:checked)>label:before {
            content: '★ ';
        }

        .rate>input:checked~label {
            color: #ffc700;
        }

        .rate:not(:checked)>label:hover,
        .rate:not(:checked)>label:hover~label {
            color: #deb217;
        }

        .rate>input:checked+label:hover,
        .rate>input:checked+label:hover~label,
        .rate>input:checked~label:hover,
        .rate>input:checked~label:hover~label,
        .rate>label:hover~input:checked~label {
            color: #c59b08;
        }
        .listProductMaterialIncurredPopup td , .listProductMaterialIncurred td {
            vertical-align : inherit !important;
        }

        #appendModelAdd .select2 {
            width: 100% !important;
        }

        .delete-file {
            border-radius: 50%;
            border: 0;
        }

        .delete-file:hover {
            cursor: pointer;
            background: red;
            color: #fff;
        }

    </style>
@endsection
@section('content')
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">

                    </span>
                    <h2 class="m-portlet__head-text">
                        <i class="fa fa-edit ss--icon-title m--margin-right-5"></i>
                        @lang('ticket::acceptance.edit_acceptance')
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">

            </div>
        </div>

        <form id="form-acceptance">
            <input type="hidden" name="ticket_acceptance_id" value="{{$detailAcceptance['ticket_acceptance_id']}}">
            <div class="m-portlet__body">
                <div class="row">
                    <div class="col-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('ticket::acceptance.ticket_code'):<b class="text-danger">*</b>
                            </label>
                            <input type="hidden" name="ticket_id" value="{{$detailAcceptance['ticket_id']}}">
                            <div class="input-group">
                                <select class="form-control select2 select2-active" id="ticket_id" onchange="Acceptance.changeTicket()">
                                    <option value="">{{__('Chọn ticket')}}</option>
                                    @foreach ($listTicket as $key => $value)
                                        <option value="{{ $value['ticket_id'] }}" {{$detailAcceptance['ticket_id'] == $value['ticket_id'] ? 'selected' : ''}}>{{ $value['ticket_code'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('ticket::acceptance.acceptance_name'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input type="text" name="title" id="title" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('ticket::acceptance.customer_name'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <input type="hidden" id="customer_id" name="customer_id" value="">
                                <input type="text" id="customer_full_name" disabled class="form-control" placeholder="Tên khách hàng" value="">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('ticket::acceptance.attach'):<b class="text-danger">*</b>
                            </label>
                            <div class="input-group">
                                <label for="getFile" class="btn btn-primary color_button btn-search">
                                    Upload
                                </label>
                                <input accept="image/jpeg,image/png,jpg|png|jpeg, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" id="getFile" onchange="uploadImage(this)" type="file" style="display: none">
                            </div>
                        </div>
                        <div class="row listFile div_file">

                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('ticket::acceptance.sign_by'):
                            </label>
                            <div class="input-group">
                                <input type="text" name="sign_by" class="form-control" placeholder="@lang('ticket::acceptance.sign_by')" value="{{$detailAcceptance['sign_by']}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('ticket::acceptance.sign_date'):
                            </label>
                            <div class="input-group">
                                <input type="text" name="sign_date"  class="form-control date-timepicker" placeholder="@lang('ticket::acceptance.sign_date')" value="{{$detailAcceptance['sign_date'] != '' && $detailAcceptance['sign_date'] != null ? \Carbon\Carbon::parse($detailAcceptance['sign_date'])->format('d/m/Y H:i') : ''}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group m-form__group">
                            <label class="black_title">
                                @lang('ticket::acceptance.status'):
                            </label>
                            <div class="input-group">
                                <select class="form-control selectEdit" name="status">
                                    <option value="new" {{$detailAcceptance['status'] == 'new' ? 'selected' : ''}}>Mới</option>
                                    <option value="approve" {{$detailAcceptance['status'] == 'approve' ? 'selected' : ''}}>Đã ký</option>
                                    <option value="cancel" {{$detailAcceptance['status'] == 'cancel' ? 'selected' : ''}}>Huỷ</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 pt-3">
                        <h5>@lang('ticket::acceptance.list_material')</h5>
                        <table class="table table-striped m-table m-table--head-bg-default mt-2" id="table-config">
                            <thead class="bg">
                            <tr>
                                <th>#</th>
                                <th class="">{{__('ticket::acceptance.product_incurred_code')}}</th>
                                <th class="">{{__('ticket::acceptance.product_incurred_name')}}</th>
                                <th class="text-center">{{__('ticket::acceptance.quantity_approve')}}</th>
                                <th class="text-center">{{__('ticket::acceptance.quantity_reality')}}</th>
                                <th class="text-center">{{__('ticket::acceptance.quantity_return')}}</th>
                                <th class="text-center">{{__('ticket::acceptance.unit')}}</th>
                            </tr>
                            </thead>
                            <tbody class="listProductMaterial">
                            <tr class="text-center">
                                <td colspan="7">{{__('ticket::acceptance.no_data')}}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="col-12 pt-3">
                        <h5>{{__('ticket::acceptance.list_incurred')}}</h5>
                        <table class="table table-striped m-table m-table--head-bg-default mt-2" id="table-config">
                            <thead class="bg">
                            <tr>
                                <th>#</th>
                                <th class="">{{__('ticket::acceptance.product_incurred_code')}}</th>
                                <th class="">{{__('ticket::acceptance.product_incurred_name')}}</th>
                                <th class="text-center">{{__('ticket::acceptance.quantity')}}</th>
                                <th class="text-center">{{__('ticket::acceptance.unit')}}</th>
                                <th class="text-center">{{__('ticket::acceptance.price')}}</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody class="listProductMaterialIncurred">
                            <tr class="text-center listProductMaterialIncurredNone">
                                <td colspan="7">{{__('ticket::acceptance.no_data')}}</td>
                            </tr>
                            </tbody>
                        </table>
{{--                        <button type="button" class="btn btn-primary color_button btn-search" style="display: block" onclick="Acceptance.showPopupAdd()">--}}
{{--                            <i class="fa fa-plus ic-search m--margin-left-5"></i> {{__('ticket::acceptance.add')}}--}}
{{--                        </button>--}}

                        <a href="javascript:void(0)" onclick="Acceptance.showPopupAdd()"
                           class="btn ss--button-cms-piospa m-btn m-btn--icon m-btn--pill btn_add_pc btn-sm">
                            <span>
                                <i class="fa fa-plus-circle"></i>
                                <span> {{__('ticket::acceptance.add')}}</span>
                            </span>
                        </a>

                    </div>
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="m-form__actions m--align-right">
                    <a href="{{route('ticket.acceptance')}}" class="ss--btn-mobiles btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn m--margin-bottom-5">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-arrow-left"></i>
                                <span>{{__('ticket::acceptance.cancel')}}</span>
                            </span>
                    </a>

                    <button onclick="Acceptance.saveAcceptanceEdit()" type="button" class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa ss--btn m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                            <span class="ss--text-btn-mobi">
                                <i class="la la-check"></i>
                                <span>{{__('ticket::acceptance.save')}}</span>
                            </span>
                    </button>

                </div>
            </div>
        </form>
        <div id="appendpopupAdd"></div>
    </div>
@endsection
@section('after_script')
    @include('ticket::language.lang')
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var obj = {};
        var objIdProduct = {};
        var count = 0;
        var countFile = {{count($listFile)}};
    </script>
    <script type="text/template" id="productIncurred">
        <tr class="incurred_block_product" id="incurred_block_product_{number}">
            <input type="hidden" name="product_id" class="product_id" value="">
            <td class="col_1" id="col_1_{number}">{numberShow}</td>
            <td><input type="text" class="form-control p-1 product_code"></td>
            <td><input type="text" class="form-control p-1 product_name"></td>
            <td class="text-center">
                <div id="incurred_block_material_product_{number}">
                    <button type="button" class="d-inline form-control" style="width: inherit" onclick="Acceptance.changeNumberIncurred({number},'sub')"> - </button>
                    <input type="text" id="incurred_material_product_{number}" class="form-control d-inline text-center p-1 product_quantity" style="width : 45%" value="1" onfocusout="Acceptance.changeNumberIncurred({number},'')">
                    <button type="button" class="d-inline form-control" style="width: inherit" onclick="Acceptance.changeNumberIncurred({number},'plus')"> + </button>
                </div>
            </td>
            <td><input type="text" class="form-control p-1 product_unit"></td>
            <td><input type="text" class="form-control p-1 product_money" id="money_{number}"></td>
            <td>
                <button type="button" onclick="Acceptance.deleteRowIncurred({number})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Xóa">
                    <i class="la la-trash"></i>
                </button>
            </td>
        </tr>
    </script>
    <script type="text/template" id="addFile">
        <div class="col-12 block-file-{countFile} mb-2">
            <button type="button" class="delete-file" onclick="Acceptance.removeFile({countFile})">X</button>
            <input type="hidden" name="pathFile[{countFile}][path]" value="{link}">
            <a href="{link}" class="file_ticket">{link}</a>
        </div>
    </script>
    <script type="text/template" id="productIncurredSelect">
        <tr class="incurred_block_product" id="incurred_block_product_{number}">
            <input type="hidden" name="product_id" class="product_id" value="{product_id}">
            <td class="col_1" id="col_1_{number}">{numberShow}</td>
            <td><input type="text" class="form-control p-1 product_code" value="{product_code}"></td>
            <td><input type="text" class="form-control p-1 product_name" value="{product_name}"></td>
            <td class="text-center">
                <div id="incurred_block_material_product_{number}">
                    <button type="button" class="d-inline form-control" style="width: inherit" onclick="Acceptance.changeNumberIncurred({number},'sub')"> - </button>
                    <input type="text" id="incurred_material_product_{number}" class="form-control d-inline text-center p-1 product_quantity" style="width : 45%" value="1" onfocusout="Acceptance.changeNumberIncurred({number},'')">
                    <button type="button" class="d-inline form-control" style="width: inherit" onclick="Acceptance.changeNumberIncurred({number},'plus')"> + </button>
                </div>
            </td>
            <td><input type="text" class="form-control p-1 product_unit" value="{product_unit}"></td>
            <td><input type="text" class="form-control p-1 product_money" id="money_{number}" value="{product_money}"></td>
            <td>
                <button type="button" onclick="Acceptance.deleteRowIncurred({number})" class="m-portlet__nav-link btn m-btn m-btn--hover-danger m-btn--icon m-btn--icon-only m-btn--pill" title="Xóa">
                    <i class="la la-trash"></i>
                </button>
            </td>
        </tr>
    </script>
    <script>
        $(document).ready(function () {
            $('.selectEdit').select2();
            $(".date-timepicker").datetimepicker({
                todayHighlight: !0,
                autoclose: !0,
                pickerPosition: "bottom-left",
                format: "dd/mm/yyyy hh:ii",
                startDate : '{{$detailAcceptance['date_issue']}}',
                endDate : new Date(),
                // locale: 'vi'
            });

            Acceptance.changeTicket(`{{$detailAcceptance['title']}}`);
            @foreach($listIncurred as $item)
                count++;
                @if($item['product_id'] != '' && $item['product_id'] != null)
                    objIdProduct[`{{$item['product_id']}}`] = `{{$item['product_id']}}`;
                @endif
                obj[`{{$item['product_name']}}`] = {
                    product_id : `{{$item['product_id']}}`,
                    product_code : `{{$item['product_code']}}`,
                    product_name : `{{$item['product_name']}}`,
                    product_quantity : `{{$item['quantity']}}`,
                    product_unit : `{{$item['unit_name']}}`,
                    product_money : `{{number_format($item['money'],0)}}`
                }
            @endforeach
            Acceptance.addIncurredEdit();
            $('#ticket_id').prop("disabled",true);
        });
    </script>

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script src="{{asset('static/backend/js/ticket/acceptance/list.js?v='.time())}}" type="text/javascript"></script>

@stop
