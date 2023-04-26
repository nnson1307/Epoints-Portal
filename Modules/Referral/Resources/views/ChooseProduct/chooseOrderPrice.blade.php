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
        .stepBar.step3 .step {
            width: 25%;
        }
    </style>
    <div class="m-portlet" id="autotable">
        <input type="hidden" id="referral_program_id" name="referral_program_id" value="{{$referral_program_id}}">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="la la-th-list"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHÍNH SÁCH HOA HỒNG CHO NGƯỜI GIỚI THIỆU')}}
                    </h3>

                    <div class="modal-footer" style="margin-left: 560px">
                        <div class="m-form__actions m--align-right w-100">
                            <button
                                    type="button" onclick="edit.editCommission()"
                                    data-dismiss="modal"
                                    class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						        <span>
						            <i class="la la-arrow-left"></i>
						            <span>{{__('HỦY')}}</span>
						        </span>
                            </button>
                        </div>
                    </div>
                    <div>
                        <button
                                type="button" onclick="edit.nextStep3()"
                                data-dismiss="modal"
                                class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md btn btn-success color_button">
                        <span>
                              <i class="la la-check"></i>
                              <span>{{__('TIẾP THEO')}}</span>
                           </span>
                        </button>
                    </div>
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
                                    <li class="step">
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
                                <div class="m-portlet__body">
                                    <h3 class="type_of_criteria">{{__('Chọn hàng hóa áp dụng ( Đơn hàng phải có 1 trong những hàng hóa này)')}} </h3>
                                    <form class="frmFilter ss--background">
                                        <div class="row ss--bao-filter">
                                            <div class="col-lg-3" style="flex: 0 0 33.33333%;max-width: 33.33333%;">
                                                <div class="input-group">
                                                    <input type="hidden" name="search_type" value="product_name">
                                                    <button class="btn btn-primary btn-search" style="display: none">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                    <select class="form-control select2" id="type-commodity"
                                                            name="type-commodity"
                                                            onchange="change.chooseTypeCommondity(this)">
                                                        <option value="all" selected>{{__('Tất cả')}}</option>
                                                        <option value="service">{{__('Dịch vụ')}}</option>
                                                        <option value="product">{{__('Sản phẩm')}}</option>
                                                        <option value="service_card">{{__('Thẻ dịch vụ')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-3" style="flex: 0 0 33.33333%;max-width: 33.33333%;">
                                                <div class="form-group m-form__group">
                                                    <div class="input-group">
                                                        <input type="hidden" name="search_type" value="product_name">
                                                        <button class="btn btn-primary btn-search"
                                                                style="display: none">
                                                            <i class="fa fa-search"></i>
                                                        </button>
                                                        <select class="form-control select2" id="group-commodity"
                                                                name="group-commodity"
                                                                onchange="change.chooseGroupCommodity(this)">
                                                            <option value="all">{{__('Tất cả')}}</option>
                                                            {{--                                                            <option value="{{'all|all'}}" selected>{{__('Tất cả')}}</option>--}}
                                                            {{--                                                            @foreach($listGroup as $k =>$v)--}}
                                                            {{--                                                                <option value={{$v['type'].'|'.$v['id']}}>{{$v['name']}}</option>--}}
                                                            {{--                                                            @endforeach--}}
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-3" style="flex: 0 0 33.33333%;max-width: 33.33333%;">
                                                <div class="input-group">
                                                    <input type="hidden" name="search_type" value="product_name">
                                                    <button class="btn btn-primary btn-search" style="display: none">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                    <select class="form-control select2" id="commodity" name="commodity"
                                                            onchange="change.addCommodity(this)">
                                                        <option value="all">{{__('Tất cả')}}</option>
                                                        {{--                                                        <option value="{{'all|all'}}" selected>{{__('Tất cả')}}</option>--}}
                                                        {{--                                                        @foreach($listCommodityAll as $k => $v)--}}
                                                        {{--                                                            <option value="{{$v['type'].'|'.$v['id']}}">{{$v['name']}}</option>--}}
                                                        {{--                                                        @endforeach--}}
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                    <div class="m-portlet__body" style="padding: 1rem 0rem;" id="commodity_table">
                                        <div class="table-responsive">
                                            <table class="table table-striped m-table ss--header-table">
                                                <thead>
                                                <tr class="ss--nowrap">
                                                    <th class="ss--font-size-th ss--text-center">#</th>
                                                    <th class="ss--font-size-th ss--text-center">{{__('Hành động')}}</th>
                                                    <th class="ss--font-size-th ss--text-center">{{__('Tên loại hàng hóa')}}</th>
                                                    <th class="ss--font-size-th ss--text-center">{{__('Tên nhóm hàng hóa')}}</th>
                                                    <th class="ss--font-size-th ss--text-center">{{__('Tên hàng hóa')}}</th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                @if(count($table) != 0)
                                                    @foreach($table as $k => $v)
                                                        <tr class="ss--font-size-13 ss--nowrap">
                                                            <td class="ss--text-center">{{($table->currentPage() - 1)*$table->perPage() + $k+1 }}</td>
                                                            <td class="ss--text-center">
                                                                <a href="javascript:void(0)"
                                                                   onclick="commodity.delete(this, {{$v}})"
                                                                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                                                                   title="Xóa">
                                                                    <i class="la la-trash"></i>
                                                                </a>
                                                            </td>
                                                            @if($v['object_type'] == 'products')
                                                                <td class="ss--text-center">{{__('Sản phẩm')}}</td>
                                                            @elseif($v['object_type'] == 'services')
                                                                <td class="ss--text-center">{{__('Dịch vụ')}}</td>
                                                            @else
                                                                <td class="ss--text-center">{{__('Thẻ dịch vụ')}}</td>
                                                            @endif
                                                            <td class="ss--text-center">{{$v['category_name']}}</td>
                                                            <td class="ss--text-center">{{$v['name']}}</td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr class="ss--font-size-13 ss--nowrap choose-all">
                                                        <td colspan="5" style="text-align:center">{{__('Đã chọn tất cả hàng hóa')}}
                                                        </td>
                                                    </tr>
                                                @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        {{ $table->links('referral::ChooseProduct.helpers.paging') }}
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
                <script src="{{asset('static/backend/js/admin/affiliate/add.js?v='.time())}}"
                        type="text/javascript"></script>

                <script>
                    $('.select2').select2();

                </script>
{{--                <script>--}}
{{--                    var change = {--}}
{{--                        chooseTypeCommondity: function () {--}}
{{--                            $.ajax({--}}
{{--                                url: laroute.route("referral.getGroupCommodity"),--}}
{{--                                method: "POST",--}}
{{--                                data: $("#type-commodity"),--}}
{{--                                success: function (res) {--}}
{{--                                    if (res.error == false) {--}}
{{--                                        $('#group-commodity').empty();--}}
{{--                                        $('#group-commodity').append(res.view);--}}
{{--                                        $(".choose-all").hide();--}}
{{--                                    } else {--}}
{{--                                        swal.fire(res.message, 'Kiểm tra lại dữ liệu nhập!', 'error');--}}
{{--                                    }--}}
{{--                                }--}}
{{--                            })--}}
{{--                        },--}}
{{--                        chooseGroupCommodity: function () {--}}
{{--                            $.ajax({--}}
{{--                                url: laroute.route("referral.getCommodity"),--}}
{{--                                method: "POST",--}}
{{--                                data: {--}}
{{--                                    type: $("#type-commodity").val(),--}}
{{--                                    group_commodity: $("#group-commodity").val(),--}}
{{--                                    referral_program_id: $('#referral_program_id').val()--}}
{{--                                },--}}
{{--                                success: function (res) {--}}
{{--                                    if (res.error == false) {--}}
{{--                                        $('#commodity').empty();--}}
{{--                                        $('#commodity').append(res.view);--}}
{{--                                    } else {--}}
{{--                                        swal.fire(res.message, ' Kiểm tra lại dữ liệu nhập!', 'error');--}}
{{--                                    }--}}
{{--                                }--}}
{{--                            })--}}
{{--                        },--}}
{{--                        addCommodity: function () {--}}
{{--                            $.ajax({--}}
{{--                                url: laroute.route("referral.addCommodity"),--}}
{{--                                method: "POST",--}}
{{--                                data: {--}}
{{--                                    type: $("#type-commodity").val(),--}}
{{--                                    group_commodity: $("#group-commodity").val(),--}}
{{--                                    commodity: $('#commodity').val(),--}}
{{--                                    referral_program_id: $('#referral_program_id').val()--}}
{{--                                },--}}
{{--                                success: function (res) {--}}
{{--                                    if (res.error == false) {--}}
{{--                                        $('#commodity_table').html(res.view);--}}
{{--                                        change.chooseGroupCommodity();--}}
{{--                                    } else {--}}
{{--                                        swal.fire(res.message, ' Kiểm tra lại dữ liệu nhập!', 'error');--}}
{{--                                    }--}}
{{--                                }--}}
{{--                            })--}}
{{--                        },--}}
{{--                    }--}}
{{--                    var commodity = {--}}
{{--                        delete: function (obj, idCommodity) {--}}
{{--                            let data = {--}}
{{--                                idCommodity: idCommodity,--}}
{{--                                referral_program_id: $('#referral_program_id').val()--}}
{{--                            };--}}
{{--                            $.ajax({--}}
{{--                                url: laroute.route("referral.deleteCommodity"),--}}
{{--                                method: "POST",--}}
{{--                                data: data,--}}
{{--                                success: function (res) {--}}
{{--                                    if (res.error == false) {--}}
{{--                                        window.location.href = laroute.route("referral.chooseOrderPrice", {'id': $('#referral_program_id').val()})--}}
{{--                                    } else {--}}
{{--                                        swal.fire(res.message, ' Xóa không thành công!', 'error');--}}
{{--                                    }--}}
{{--                                }--}}
{{--                            })--}}
{{--                        },--}}

{{--                        changePageProduct: function (page = 1) {--}}

{{--                            $.ajax({--}}
{{--                                url: laroute.route("referral.changePageProduct"),--}}
{{--                                method: "POST",--}}
{{--                                data: {--}}
{{--                                    referral_program_id: $('#referral_program_id').val(),--}}
{{--                                    page: page--}}
{{--                                },--}}
{{--                                success: function (res) {--}}
{{--                                    if (res.error == false) {--}}
{{--                                        $('#commodity_table').html(res.view);--}}
{{--                                    } else {--}}
{{--                                        swal.fire(res.message, ' Kiểm tra lại dữ liệu nhập!', 'error');--}}
{{--                                    }--}}
{{--                                }--}}
{{--                            })--}}
{{--                        }--}}
{{--                    }--}}
{{--                </script>--}}
{{--                <script >--}}
{{--                    var edit ={--}}
{{--                        editCommission: function () {--}}
{{--                            $.ajax({--}}
{{--                                url: laroute.route("referral.editCommission"),--}}
{{--                                method: "POST",--}}
{{--                                data: {--}}
{{--                                    referral_program_id: $('#referral_program_id').val()--}}
{{--                                },--}}
{{--                                success: function (res) {--}}
{{--                                    window.location.href = res.link;--}}
{{--                                }--}}
{{--                            })--}}
{{--                        },--}}
{{--                        nextStep3: function () {--}}
{{--                            $.ajax({--}}
{{--                                url: laroute.route("referral.step3ChooseOrderPrice"),--}}
{{--                                method: "POST",--}}
{{--                                data: {--}}
{{--                                    referral_program_id: $('#referral_program_id').val()--}}
{{--                                },--}}
{{--                                success: function (res) {--}}
{{--                                    window.location.href = res.link;--}}
{{--                                }--}}
{{--                            })--}}
{{--                        }--}}
{{--                    }--}}
{{--                </script>--}}

@stop

