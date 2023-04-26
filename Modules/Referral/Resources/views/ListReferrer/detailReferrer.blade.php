@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('title_header')
    <span class="title_header"><img
                src="{{asset('static/backend/images/icon/icon-product.png')}}" alt="" style="height: 20px;">
        {{__('QUẢN LÝ REFERRAL')}}
    </span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .nav-item:hover {
            background-color: #4fc4cb;
            transition: 1s;

        }

        .nav-item:hover .nav-link {
            color: white;
            transition: 1s
        }
    </style>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="fa fas fa-list-ul"></i>
                     </span>
                    <h3 class="m-portlet__head-text">
                        {{__('CHI TIẾT NGƯỜI GIỚI THIỆU')}}
                    </h3>
                </div>
            </div>
        </div>
        <div class="m-portlet__body">
            <div class="row">
                <div class="col-lg-2">
                    <div class="form-group m-form__group">
                        <input type="hidden" id="staff_avatar" name="staff_avatar" value="">
                        <input type="hidden" id="staff_avatar_upload" name="staff_avatar_upload" value="">
                        <div class="form-group m-widget19">
                            <div class="m-widget19__pic">
                                <img class="m--bg-metal m-image img-sd" id="blah" src="https://vignette.wikia.nocookie.net/recipes/images/1/1c/Avatar.svg/revision/latest/scale-to-width-down/480?cb=20110302033947" alt="Hình ảnh" width="220px" height="220px">
                            </div>
                            <input accept="image/jpeg,image/png,image/jpeg,jpg|png|jpeg" data-msg-accept="Hình ảnh không đúng định dạng" id="getFile" type="file" onchange="uploadImage(this);" class="form-control" style="display:none">
                        </div>
                    </div>
                </div>
                <div class="col-lg-2">
                    <p style="font-weight: bold;">Bảo Bằng </p>
                    <p> <i class="fa fa-birthday-cake"> </i> Trống</p>
                    <p> <i class="la la-phone"> </i> 0123456789</p>
                    <p> <i class="flaticon2-location"> </i> 123 3 tháng 2</p>
                    <p> <i class="flaticon-mail-1"> </i> abc@gmail.com</p>
                </div>
                <div class="col-lg-2" style="font-size: 10px">
                    <p>Mã giới thiệu</p>
                    <p>Người giới thiệu</p>
                    <p>Số người đã giới thiệu</p>
                    <p>Tổng hoa hồng đã ghi nhận</p>
                    <p>Hoa hồng chưa ghi nhận</p>
                    <p>Hoa hồng khả dụng: 10.000.000</p>
                </div>
            </div>
            <ul class="nav nav-pills nav-fill" role="tablist" style="margin-bottom: -8px">
                <li class="nav-item">
                    <a class="nav-link " href="#m_tabs_5_1" style="font-weight: bold;">Danh sách người giới thiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#m_tabs_5_2" style="font-weight: bold;background-color: #4fc4cb;color: white;border-radius: 0px">Danh sách đơn hàng</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#m_tabs_5_4" style="font-weight: bold">Lịch sử nhận hoa hồng</a>
                </li>

            </ul>
            <hr style="    margin-top: 6px;margin-bottom: 10px;border: 0;border-top: 2px solid #4fc4cb">
            <div class="m-portlet__body" style="padding: 0px;padding-top: 12px;">
                <form class="frmFilter ss--background">
                    <div style="background-color: white;float: right">
                        <a href="">Xem thêm</a>
                    </div>

                </form>
            </div>
            <div class="table-content">
                <div class="m-portlet__body" style="padding: 0px;padding-top:12px">
                    <div class="table-responsive">
                        <table class="table table-striped m-table ss--header-table">
                            <thead>
                            <tr class="ss--nowrap">
                                <th class="ss--text-center">#</th>
                                <th class="">{{__('Hành động')}}</th>
                                <th class="ss--text-center">{{__('Mã đơn hàng')}}</th>
                                <th class="ss--text-center">{{__('Người giới thiệu')}}</th>
                                <th class="ss--text-center">{{__('Chính sách hoa hồng')}}</th>
                                <th class="ss--text-center">{{__('Giá trị đơn hàng')}}</th>
                                <th class="ss--text-center">{{__('Hoa hồng đơn hàng')}}</th>
                                <th class="ss--text-center">{{__('Trạng thái hoa hồng')}}</th>
                                <th class="ss--text-center">{{__('Ngày tạo')}}</th>
                                <th class="ss--text-center">{{__('Ngày hết hạn')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr class="ss--font-size-13 ss--nowrap">
                                <td class="ss--text-center">#</td>
                                <td>
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                            <label style="margin: 0 0 0 0px; padding-top: 0px">
                                                <input type="checkbox"
                                                       checked class="manager-btn" name="">
                                                <span></span>
                                            </label>
                                    </span>
                                </td>
                                <td class="ss--text-center">DH_123456789</td>
                                <td class="ss--text-center">Nguyen Van A</td>
                                <td class="ss--text-center"> Hoa hong don hang</td>
                                <td class="ss--text-center"> 100.000</td>
                                <td class="ss--text-center"> 10.000</td>
                                <td class="ss--text-center"> Đã ghi nhận</td>
                                <td class="ss--text-center"> 21/02/2022 14:10</td>
                                <td class="ss--text-center"> 21/02/2022 14:10</td>
                            </tr>
                            <tr class="ss--font-size-13 ss--nowrap">
                                <td class="ss--text-center">#</td>
                                <td>
                                    <span class="m-switch m-switch--icon m-switch--success m-switch--sm">
                                            <label style="margin: 0 0 0 0px; padding-top: 0px">
                                                <input type="checkbox"
                                                       checked class="manager-btn" name="">
                                                <span></span>
                                            </label>
                                    </span>
                                </td>
                                <td class="ss--text-center">DH_123456789</td>
                                <td class="ss--text-center">Nguyen Van A</td>
                                <td class="ss--text-center"> Hoa hong don hang</td>
                                <td class="ss--text-center"> 100.000</td>
                                <td class="ss--text-center"> 10.000</td>
                                <td class="ss--text-center"> Đã ghi nhận</td>
                                <td class="ss--text-center"> 21/02/2022 14:10</td>
                                <td class="ss--text-center"> 21/02/2022 14:10</td>
                            </tr>


                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- end table-content -->
        </div>
{{--        /////////////////////////////lich su nhan hoa hong--}}
        <div class="m-portlet__body">
            <ul class="nav nav-pills nav-fill" role="tablist" style="margin-bottom: -8px">
                <li class="nav-item">
                    <a class="nav-link " href="#m_tabs_5_1" style="font-weight: bold;">Danh sách người giới thiệu</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#m_tabs_5_2" style="font-weight: bold">Danh sách đơn hàng</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="#m_tabs_5_4" style="font-weight: bold;background-color: #4fc4cb;color: white;border-radius: 0px">Lịch sử nhận hoa hồng</a>
                </li>

            </ul>
            </ul>
            <hr style="    margin-top: 6px;margin-bottom: 10px;border: 0;border-top: 2px solid #4fc4cb">
            <div class="m-portlet__body" style="padding: 0px;padding-top: 12px;">
                <form class="frmFilter ss--background">
                    <div class="row ss--bao-filter">
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="product_name">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <select class="form-control select2">
                                        <option value="">Người thanh toán</option>
                                        <option value="1">Loại 1</option>
                                        <option value="2">Loại 2</option>
                                        <option value="3">Loại 3</option>
                                        <option value="4">Loại 4</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="product_name">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <select class="form-control select2">
                                        <option value="">Trạng thái</option>
                                        <option value="1">Loại 1</option>
                                        <option value="2">Loại 2</option>
                                        <option value="3">Loại 3</option>
                                        <option value="4">Loại 4</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <div class="form-group">
                                        <div class="m-input-icon m-input-icon--right">
                                            <input readonly class="form-control m-input daterange-picker"
                                                   style="background-color: #fff"
                                                   id="created_at"
                                                   name="created_at"
                                                   autocomplete="off" placeholder="@lang('Ngày yêu cầu')">
                                            <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group">
                                <div class="m-input-icon m-input-icon--right">
                                    <input readonly="" class="form-control date-picker-list"
                                           id="m_datepicker_1" style="background-color: #fff"
                                           name="date_end" value="" autocomplete="off"
                                           placeholder="Ngày thanh toán">
                                    <span class="m-input-icon__icon m-input-icon__icon--right">
                                                            <span><i class="la la-calendar"></i></span></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3">
                            <div class="form-group m-form__group">
                                <div class="input-group">
                                    <input type="hidden" name="search_type" value="product_name">
                                    <button class="btn btn-primary btn-search" style="display: none">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <select class="form-control select2">
                                        <option value="">Kỳ hoa hồng</option>
                                        <option value="1">Loại 1</option>
                                        <option value="2">Loại 2</option>
                                        <option value="3">Loại 3</option>
                                        <option value="4">Loại 4</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="m-portlet__body" style="padding-right: 15px;padding-top: 0px;padding-bottom: 12px">
                            <div class="text-right">
                                <a href="{{route('referral.listReferrer')}}"
                                   class="btn btn-refresh btn-primary color_button m-btn--icon" style="color: #fff">
                                    {{ __('XÓA BỘ LỌC') }}
                                    <i class="fa fa-eraser" aria-hidden="true"></i>
                                </a>

                                <button href="javascript:void(0)" onclick="product.search()"
                                        class="btn ss--btn-search">
                                    {{__('TÌM KIẾM')}}
                                    <i class="fa fa-search ss--icon-search"></i>
                                </button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
            <div class="table-content">
                <div class="m-portlet__body" style="padding: 0px;padding-top:12px">
                    <div class="table-responsive">
                        <table class="table table-striped m-table ss--header-table">
                            <thead>
                            <tr class="ss--nowrap">
                                <th class="ss--text-center">#</th>
                                <th class="ss--text-center">{{__('Phiếu chi')}}</th>
                                <th class="ss--text-center">{{__('Kỳ trả hoa hồng')}}</th>
                                <th class="ss--text-center">{{__('Hoa hồng theo kỳ')}}</th>
                                <th class="ss--text-center">{{__('Người thanh toán')}}</th>
                                <th class="ss--text-center">{{__('Ngày thanh toán')}}</th>
                                <th class="ss--text-center">{{__('Hình thức')}}</th>
                                <th class="ss--text-center">{{__('Trạng thái')}}</th>

                            </tr>
                            </thead>
                            <tbody>
                            <tr class="ss--font-size-13 ss--nowrap">
                                <td class="ss--text-center">#</td>
                                <td class="ss--text-center">TT_123456789</td>
                                <td class="ss--text-center">Kỳ 15/10/2022 - 31/10/2022</td>
                                <td class="ss--text-center"> 100.000</td>
                                <td class="ss--text-center">Nguyễn Văn A</td>
                                <td class="ss--text-center"> 21/02/2022 14:10</td>
                                <td class="ss--text-center"> TIền mặt</td>
                                <td class="ss--text-center"> Đã ghi nhận</td>

                            </tr>
                            <tr class="ss--font-size-13 ss--nowrap">
                                <td class="ss--text-center">#</td>
                                <td class="ss--text-center">TT_123456789</td>
                                <td class="ss--text-center">Kỳ 15/10/2022 - 31/10/2022</td>
                                <td class="ss--text-center"> 100.000</td>
                                <td class="ss--text-center">Nguyễn Văn A</td>
                                <td class="ss--text-center"> 21/02/2022 14:10</td>
                                <td class="ss--text-center"> TIền mặt</td>
                                <td class="ss--text-center"> Đã ghi nhận</td>

                            </tr>

                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- end table-content -->
        </div>
{{--        /////////////////////////////--}}
    </div>
    @include('admin::product.modal.excel-image')
@endsection
@section('after_script')

    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js?v='.time())}}"></script>
    <script src="{{asset('static/backend/js/admin/product/list.js?v='.time())}}" type="text/javascript"></script>
    <script src="{{asset('static/backend/js/admin/referral/add.js?v='.time())}}"
            type="text/javascript"></script>
    <script>
        $('.select2').select2();
    </script>
    <script >
        $.getJSON(laroute.route('translate'), function (json) {
            var arrRange = {};
            arrRange[json["Hôm nay"]] = [moment(), moment()];
            arrRange[json["Hôm qua"]] = [moment().subtract(1, "days"), moment().subtract(1, "days")];
            arrRange[json["7 ngày trước"]] = [moment().subtract(6, "days"), moment()];
            arrRange[json["30 ngày trước"]] = [moment().subtract(29, "days"), moment()];
            arrRange[json["Trong tháng"]] = [moment().startOf("month"), moment().endOf("month")];
            arrRange[json["Tháng trước"]] = [moment().subtract(1, "month").startOf("month"), moment().subtract(1, "month").endOf("month")];

            $("#created_at").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    "customRangeLabel": json['Tùy chọn ngày'],
                    daysOfWeek: [
                        json["CN"],
                        json["T2"],
                        json["T3"],
                        json["T4"],
                        json["T5"],
                        json["T6"],
                        json["T7"]
                    ],
                    "monthNames": [
                        json["Tháng 1 năm"],
                        json["Tháng 2 năm"],
                        json["Tháng 3 năm"],
                        json["Tháng 4 năm"],
                        json["Tháng 5 năm"],
                        json["Tháng 6 năm"],
                        json["Tháng 7 năm"],
                        json["Tháng 8 năm"],
                        json["Tháng 9 năm"],
                        json["Tháng 10 năm"],
                        json["Tháng 11 năm"],
                        json["Tháng 12 năm"]
                    ],
                    "firstDay": 1
                },
                ranges: arrRange
            });
            $("#closing_date").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    "customRangeLabel": json['Tùy chọn ngày'],
                    daysOfWeek: [
                        json["CN"],
                        json["T2"],
                        json["T3"],
                        json["T4"],
                        json["T5"],
                        json["T6"],
                        json["T7"]
                    ],
                    "monthNames": [
                        json["Tháng 1 năm"],
                        json["Tháng 2 năm"],
                        json["Tháng 3 năm"],
                        json["Tháng 4 năm"],
                        json["Tháng 5 năm"],
                        json["Tháng 6 năm"],
                        json["Tháng 7 năm"],
                        json["Tháng 8 năm"],
                        json["Tháng 9 năm"],
                        json["Tháng 10 năm"],
                        json["Tháng 11 năm"],
                        json["Tháng 12 năm"]
                    ],
                    "firstDay": 1
                },
                ranges: arrRange
            });
            $("#closing_due_date").daterangepicker({
                autoUpdateInput: false,
                autoApply: true,
                locale: {
                    cancelLabel: 'Clear',
                    format: 'DD/MM/YYYY',
                    "customRangeLabel": json['Tùy chọn ngày'],
                    daysOfWeek: [
                        json["CN"],
                        json["T2"],
                        json["T3"],
                        json["T4"],
                        json["T5"],
                        json["T6"],
                        json["T7"]
                    ],
                    "monthNames": [
                        json["Tháng 1 năm"],
                        json["Tháng 2 năm"],
                        json["Tháng 3 năm"],
                        json["Tháng 4 năm"],
                        json["Tháng 5 năm"],
                        json["Tháng 6 năm"],
                        json["Tháng 7 năm"],
                        json["Tháng 8 năm"],
                        json["Tháng 9 năm"],
                        json["Tháng 10 năm"],
                        json["Tháng 11 năm"],
                        json["Tháng 12 năm"]
                    ],
                    "firstDay": 1
                },
                ranges: arrRange
            });
        });
    </script>
@stop
