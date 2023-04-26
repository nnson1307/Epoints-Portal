@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    <link href="toggle-radios.css" rel="stylesheet"/>
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css?v='.time())}}">
@endsection
@section('title_header')
    <span class="title_header"><img src="{{asset('static/backend/images/icon/icon-product.png')}}" alt=""
                                    style="height: 20px;">
    {{__('QUẢN LÝ REFERRAL')}}
</span>
@endsection
@section('content')
    <meta http-equiv="refresh" content="number">
    <style>
        .m-portlet {
            margin-bottom: 0px
        }

        .info-commission {
            padding: 30px;
            margin-bottom: 10px;
        }

        .info-commission-left .bottom {
            margin-bottom: 10px;
            font-size: 15px;
            font-weight: bold
        }

        .des {
            font-weight: 500;
        }

        .des-condition {
            font-size: 15px;
        }

        .image-commission {
            width: 300px;
            height: 150px;
            background-repeat: no-repeat;
            background-size: 100% 100%;
            background-image: url({{$data['img']}})
        }

        .new {
            color: #4fc4cb
        }

        .cancel {
            color: silver;
        }

        .pending {
            color: yellow;
        }

        .waiting {
            color: orange
        }

        .approved {
            color: royalblue
        }

        .reject {
            color: red;
        }

        .finish {
            color: silver;
        }

        .actived {
            color: green;
        }

        .cps {
            color: royalblue
        }

        .cpi {
            color: royalblue
        }

        .cus {
            color: royalblue
        }

        .all {
            color: green
        }

        .his {
            margin-top: 10px;
        }

        .stop {
            background-color: yellow;
            color: black;
            border-color: yellow;
        }

        .not_find {
            text-align: center;
            /* border: 1px solid; */
            padding-top: 40px;
            padding-bottom: 40px;
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
                    <h3 class="m-portlet__head-text ">
                        {{__('THÔNG TIN CHÍNH SÁCH')}}
                    </h3>
                </div>
            </div>
            <div style="float:right;    margin-top: 5px">
                <a href="{{route('referral.policyCommission')}}"
                   class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                <span>
                    <i class="la la-arrow-left"></i>
                    <span>{{__('TRỞ VỀ')}}</span>
                </span>
                </a>
                @if($data['status'] == 'new')
                    <button type="button" onclick="statechange.change('{{$data['referral_program_id']}}','gửi duyệt')"
                            data-dismiss="modal"
                            class="btn btn-success bold-huy m-btn m-btn--icon m-btn--wide m-btn--md"
                            style=" background-color: orange">
                <span>
                    <i class="fa fa-pencil-alt"></i>
                    <span>{{__('GỬI DUYỆT')}}</span>
                </span>
                    </button>
                @endif
                @if($data['status'] == 'waiting')
                    <button type="button" onclick="statechange.change('{{$data['referral_program_id']}}','duyệt')"
                            data-dismiss="modal"
                            class="btn btn-success bold-huy m-btn m-btn--icon m-btn--wide m-btn--md"
                            style=" background-color: royalblue">
                <span>
                    <i class="fa fa-pencil-alt"></i>
                    <span>{{__('DUYỆT')}}</span>
                </span>
                    </button>
                    <button type="button" onclick="statechange.change('{{$data['referral_program_id']}}','từ chối')"
                            data-dismiss="modal"
                            class="btn btn-danger bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                    <span>
                        <i class="fa fa-pencil-alt"></i>
                        <span>{{__('TỪ CHỐI')}}</span>
                    </span>
                    </button>
                @endif
                @if($data['status'] == 'approved' || $data['status'] == 'waiting')
                    <button type="button" onclick="statechange.change('{{$data['referral_program_id']}}','hủy')"
                            data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                <span>
                    <i class="fa fa-pencil-alt"></i>
                    <span>{{__('HỦY')}}</span>
                </span>
                    </button>
                @endif
                @if($data['status'] == 'reject')
                    <button type="button" onclick="statechange.change('{{$data['referral_program_id']}}','lưu nháp')"
                            data-dismiss="modal"
                            class="btn btn-success bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                <span>
                    <i class="fa fa-pencil-alt"></i>
                    <span>{{__('LƯU NHÁP')}}</span>
                </span>
                    </button>
                @endif
                @if($data['status'] == 'actived')
                    <button type="button" onclick="statechange.change('{{$data['referral_program_id']}}','dừng')"
                            data-dismiss="modal"
                            class="btn btn-success bold-huy m-btn m-btn--icon m-btn--wide m-btn--md stop"
                            style=" color: black">
                <span>
                    <i class="fa fa-pencil-alt"></i>
                    <span>{{__('DỪNG')}}</span>
                </span>
                    </button>
                    <button type="button" onclick="statechange.change('{{$data['referral_program_id']}}','kết thúc')"
                            data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                <span>
                    <i class="fa fa-pencil-alt"></i>
                    <span>{{__('KẾT THÚC')}}</span>
                </span>
                    </button>
                @endif
                @if($data['status'] == 'pending')
                    <button type="button" onclick="statechange.change('{{$data['referral_program_id']}}','tiếp tục')"
                            data-dismiss="modal"
                            class="btn btn-success bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                <span>
                    <i class="fa fa-pencil-alt"></i>
                    <span>{{__('TIẾP TỤC')}}</span>
                </span>
                    </button>
                    <button type="button" onclick="statechange.change('{{$data['referral_program_id']}}','hủy')"
                            data-dismiss="modal"
                            class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
                <span>
                    <i class="fa fa-pencil-alt"></i>
                    <span>{{__('HỦY')}}</span>
                </span>
                    </button>
                @endif
            </div>
        </div>
    </div>
    <div class="m-portlet info-commission" id="autotable">
        <div class="row">
            <div class="col-sm info-commission-left">
                <div class="bottom">
                    <span>{{__('Tên chính sách:')}}</span>
                    <span class="des">{{$data['referral_program_name']}}</span>
                </div>

                <div class="bottom">
                    <span>{{__('Trạng thái:')}}</span>
                    @switch($data['status'])
                        @case('new')
                        <span class="des new">{{__('Nháp')}}</span>
                        @break
                        @case('waiting')
                        <span class="des waiting">{{__('Chờ duyệt')}}</span>
                        @break
                        @case('approved')
                        <span class="des approved">{{__('Đã duyệt')}}</span>
                        @break
                        @case('actived')
                        <span class="des actived">{{__('Đang hoạt động')}}</span>
                        @break
                        @case('pending')
                        <span class="des pending">{{__('Tạm dừng')}}</span>
                        @break
                        @case('cancel')
                        <span class="des cancel">{{__('Đã hủy')}}</span>
                        @break
                        @case('reject')
                        <span class="des reject">{{__('Đã từ chối')}}</span>
                        @break
                        @case('finish')
                        <span class="des finish">{{__('Kết thúc')}}</span>
                        @break
                    @endswitch
                </div>
                <div class="bottom">
                    <span>{{__('Loại chính sách:')}}</span>
                    @if($data['type'] == 'cps')
                        <span class="des cps">{{__('CPS')}}</span>
                    @else
                        <span class="des cpi">{{__('CPI')}}</span>
                    @endif
                </div>
                <div class="bottom">
                    <span>{{__('Áp dụng cho:')}}</span>
                    @if($data['apply_for'] == 'customer')
                        <span class="des cus">{{__('Khách hàng')}}</span>
                    @else
                        <span class="des all">{{__('Tất cả')}}</span>
                    @endif

                </div>
                <div class="bottom">
                    <span>{{__('Thời gian hiệu lực:')}}</span>
                    <span class="des"> {{__('Từ ')}} {{$data['date_start']}} - {{$data['date_end']}}</span>
                </div>
                <div class="bottom">
                    @if($data['type'] == 'cps' && $data['referral_criteria_code']  == 'total_order')
                        <span>{{__('Tính theo:')}}</span>
                        <span class="des">{{__('Tổng giá trị đơn hàng')}}</span>
                    @else
                        <span class="des"></span>
                    @endif

                </div>
                <div class="bottom">
                    <span>{{__('Nội dung:')}}</span>
                    <span class="des">
                    {!!$data['description']!!}
                </span>


                </div>

            </div>
            <div class="col-sm">
                <span style="font-size: 15px;font-weight: bold">{{__('Ảnh hiển thị trên app:')}}</span>
                <div class="image-commission">
                </div>
            </div>
        </div>
    </div>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="la la-th-list"></i>
                </span>
                    <h3 class="m-portlet__head-text ">
                        {{__('ĐIỀU KIỆN TÍNH HOA HỒNG')}}
                    </h3>
                </div>
            </div>
            <div style="float:right;    margin-top: 18px">
            <span class="m-portlet__head-icon">
{{--                <i class="fas fa-angle-down"></i>--}}
            </span>
            </div>
        </div>
        @if($data['type'] == 'cps')
            <div class="m-portlet info-commission" id="autotable">
                <div class="col-sm info-commission-left" style="padding: 0;margin-bottom:5px">
                    <div class="bottom">
                        <span>{{__('Điều kiện tính hoa hồng:')}}</span>
                    </div>
                    <span class="des-condition">{{__('Đơn hàng ở trạng thái Đã thanh toán')}}</span>

                </div>
                @if($data['trainsport_fee'] ==1)
                    <div>
                        <i class="la la-check-circle-o" style="color:green"></i>
                        <label for="transport-fee" class="des-condition">{{__('Bao gồm phí vận chuyển')}}</label><br>
                    </div>
                @else
                    <div>
                        <i class="la la-circle-o" style="color:green"></i>
                        <label for="transport-fee" class="des-condition">{{__('Bao gồm phí vận chuyển')}}</label><br>
                    </div>
                @endif
                <div class="col-sm info-commission-left" style="padding: 0;margin-bottom:5px">
                    <div class="bottom">
                        <span>{{__('Công thức tính:')}}</span>
                    </div>
                </div>
                <table class="table table-striped m-table ss--header-table">
                    <thead>
                    <tr class="ss--nowrap">
                        <th class="ss--font-size-th ss--text-center">{{__('Hoa hồng cho 1 sản phẩm/dịch vụ/thẻ dịch vụ:')}}
                            <b
                                    class="text-danger">*</b></th>
                        <th class="ss--font-size-th ss--text-center">{{__('Giá trị hoa hồng tối đa')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr class="ss--font-size-13 ss--nowrap">
                        <td style="width:300px">
                            <div class="input-group" style="">
                        <span id="config_content" name="config_content" type="text"
                              class="form-control m-input class">
                            <p>
                              {{isset($data['commission_value'])&&$data['commission_value'] != null ?  $data['commission_value'] : ''}}
                             </p>
                        </span>
                                @if($data['commission_type'] == 'money')
                                    <div class="input-group-append">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <div class="input-group-append">
                                                <span class="input-group-text text_type_default">VNĐ</span>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="input-group-append">
                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                            <div class="input-group-append">
                                                <span class="input-group-text text_type_default">%</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <span class="error_valid_commission_value_1 color_red"></span>
                        </td>
                        <td style="width:300px">
                            <div class="input-group" style="">
                        <span id="config_content" name="config_content" type="text"
                              class="form-control m-input class">
                            <p>
                              {{$data['commission_max_value']}}
                             </p>
                        </span>
                                <div class="input-group-append">
                                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <div class="input-group-append">
                                            <span class="input-group-text text_type_default">VNĐ</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        @else
            <div class="m-portlet info-commission" id="autotable">
                <div class="m-wizard__form-step m-wizard__form-step--current" id="m_wizard_form_step_2">
                    <div class="row">
                        <div class="col-xl-10 offset-xl-1">
                            <div class="m-form__section m-form__section--first">
                                <div class="container" style="max-width: 900px;">
                                    <form id="commission-condition">
                                        <div class="m-portlet__body">
                                            <h4 class="type_of_criteria"
                                                style="font-size:15px;font-weight: bold">{{__('Điều kiện tính hoa hồng:')}}</h4>
                                            <span style="font-size:15px">{{__('Khách hàng đăng kí tài khoản và nhập mã giới thiệu thành công:')}}</span>
                                            @if($conditionCPI != [])
                                            <div style="display:flex;margin-top: 10px;">
                                        <span class="fas fa-toggle-on"
                                              style="align-items: center;display: flex;">
                                            <label style="margin: 0 0 0 0px; padding-top: 0px">
{{--                                                <input type="checkbox"--}}
{{--                                                       checked class="manager-btn" name="">--}}
                                                <span></span>
                                            </label>
                                        </span>
                                                <span style="margin-top:10px;font-size:15px"> &nbsp {{__(' Thời gian sử dụng app ')}}</span>
                                                &nbsp
                                                <div class="input-group" style="width: 10%">
                                            <span id="config_content" name="config_content" type="text"
                                                  class="form-control m-input class">
                                                    <p>{{$conditionCPI['compare']}}</p>
                                                </span>
                                                </div>
                                                &nbsp
                                                <div class="input-group" style="width: 10%">
                                            <span id="config_content" name="config_content" type="text"
                                                  class="form-control m-input class">
                                                    <p>{{$conditionCPI['time_use_time']}}</p>
                                                </span>

                                                </div>
                                                <div style="margin-top:10px;font-size:15px">
                                                    <span> &nbsp{{__(' phút trong ')}}</span>
                                                </div>
                                                &nbsp
                                                <div class="input-group" style="width: 10%">
                                            <span id="config_content" name="config_content" type="text"
                                                  class="form-control m-input class">
                                                    <p>{{$conditionCPI['time_use_date']}}</p>
                                                </span>
                                                </div>
                                                <div style="margin-top:10px;font-size:15px">
                                                    <span style="margin-top:5px"> &nbsp{{__(' ngày. ')}}</span>
                                                </div>
                                            </div>
                                            @else
                                                <div style="display:flex;margin-top: 10px;">
                                        <span class="fas fa-toggle-off"
                                              style="align-items: center;display: flex">
                                            <label style="margin: 0 0 0 0px; padding-top: 0px">
{{--                                                <input type="checkbox"--}}
{{--                                                       checked class="manager-btn" name="">--}}
                                                <span></span>
                                            </label>
                                        </span>
                                                    <span style="margin-top:10px;font-size:15px"> &nbsp {{__(' Thời gian sử dụng app ')}}</span>
                                                    &nbsp
                                                    <div class="input-group" style="width: 10%">
                                            <span id="config_content" name="config_content" type="text"
                                                  class="form-control m-input class">
                                                    <p></p>
                                                </span>
                                                    </div>
                                                    &nbsp
                                                    <div class="input-group" style="width: 10%">
                                            <span id="config_content" name="config_content" type="text"
                                                  class="form-control m-input class">
                                                    <p></p>
                                                </span>

                                                    </div>
                                                    <div style="margin-top:10px;font-size:15px">
                                                        <span> &nbsp{{__(' phút trong ')}}</span>
                                                    </div>
                                                    &nbsp
                                                    <div class="input-group" style="width: 10%">
                                            <span id="config_content" name="config_content" type="text"
                                                  class="form-control m-input class">
                                                    <p></p>
                                                </span>
                                                    </div>
                                                    <div style="margin-top:10px;font-size:15px">
                                                        <span style="margin-top:5px"> &nbsp{{__(' ngày. ')}}</span>
                                                    </div>
                                                </div>
                                                @endif

                                            <div class="m-portlet__body"
                                                 style="padding: 1rem 0rem;width: 500px;margin-left: 156px;">
                                                <div class="table-responsive">
                                                    <table class="table table-striped m-table ss--header-table">
                                                        <thead>
                                                        <tr class="ss--nowrap">
                                                            <th class="ss--font-size-th ss--text-center">{{__('Hoa hồng cho 1 lần thỏa điều kiện:')}}
                                                                <b class="text-danger">*</b></th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <tr class="ss--font-size-13 ss--nowrap">
                                                            <td>
                                                                <div class="input-group" style="padding-left: 0px;">
                                                                    <div class="input-group">
                                                                <span id="config_content" name="config_content"
                                                                      type="text"
                                                                      class="form-control m-input class">
                                                                    <p> {{isset($conditionCPI['commission_value'])&&$conditionCPI['commission_value'] != null ?  $conditionCPI['commission_value'] : ''}}</p>

                                                                </span>
                                                                        <div class="input-group-append">
                                                                            <span class="input-group-text text_type_default">VNĐ</span>
                                                                        </div>
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
                                        <input type="hidden" name="referral_program_id" value="">
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="la la-th-list"></i>
                </span>
                    <h3 class="m-portlet__head-text ">
                        {{__('Cấu hình tỷ lệ Chiết Khấu')}}
                    </h3>
                </div>
            </div>
            <div style="float:right;    margin-top: 18px">
            <span class="m-portlet__head-icon">
{{--                <i class="fas fa-angle-down"></i>--}}
            </span>
            </div>
        </div>
        <div class="m-portlet info-commission" id="autotable">
            <table class="table table-striped m-table ss--header-table">
                <thead>
                <tr class="ss--nowrap">
                    <th class="ss--font-size-th ss--text-center">{{__('Người giới thiệu')}}</th>
                    <th class="ss--font-size-th ss--text-center">{{__('Chiết khấu nhận')}} <b class="text-danger"> *</b></th>
                </tr>
                </thead>
                <tbody>
                @foreach($dataRate as $rateItem)
                    <tr class="ss--font-size-13 ss--nowrap">
                        <td class=" ss--text-center">
                            <div class="out-presenter">
                                <p class="presenter"> Cấp {{$rateItem['level']}}</p>
                            </div>
                        </td>
                        <td class="ss--text-center">
                            <div class="input-group mb-3">
                                <input disabled value="{{$rateItem['percent']}}" type="text" class="form-control" placeholder="100">
                                <span class="input-group-text" id="basic-addon2">%</span>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>


    </div>
    @if($data['type'] == 'cps')
        <div class="m-portlet" id="autotable">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="la la-th-list"></i>
                </span>
                        <h3 class="m-portlet__head-text ">
                            {{__('DANH SÁCH ÁP DỤNG')}}
                        </h3>
                    </div>
                </div>
                <div style="float:right;    margin-top: 18px">
            <span class="m-portlet__head-icon">
{{--                <i class="fas fa-angle-down"></i>--}}
            </span>
                </div>
            </div>
            <div class="m-portlet info-commission" id="autotable">
                <table class="table table-striped m-table ss--header-table">
                    <thead>
                    <tr class="ss--nowrap">
                        <th class="ss--font-size-th ss--text-center">#</th>
                        <th class="ss--font-size-th ss--text-center">{{__('Tên danh mục')}}</th>
                        <th class="ss--font-size-th ss--text-center">{{__('Tên sản phẩm')}}</th>
                        {{--                    <th class="ss--font-size-th ss--text-center">{{__('Giá vốn')}}</th>--}}
                        {{--                    <th class="ss--font-size-th ss--text-center">{{__('Giá bán')}}</th>--}}
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($commodity) != 0)
                        @foreach($commodity as $k => $v)
                            <tr class="ss--font-size-13 ss--nowrap">
                                <td class="ss--text-center">{{isset($page) ? ($page-1)*10 + $k+1 :$k+1}}</td>
                                <td class="ss--text-center">{{$v['commodity']['category_name']}}</td>
                                <td class="ss--text-center">{{$v['commodity']['name']}}</td>
                                {{--                        <td class="ss--text-center">cost</td>--}}
                                {{--                        <td class="ss--text-center">{{isset($v['commodity']['price'])? $v['commodity']['price']: ''}}</td>--}}
                            </tr>
                        @endforeach
                    @else
                        <tr class="ss--font-size-13 ss--nowrap">
                            <td colspan="5" style="text-align:center">Đã chọn tất cả hàng hóa</td>
                        </tr>
                    @endif
                    </tbody>
                </table>
                {{ $commodity->appends($page)->links('helpers.paging-load') }}
            </div>
        </div>
    @endif
    <div class="m-portlet" id="autotable">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                <span class="m-portlet__head-icon">
                    <i class="la la-th-list"></i>
                </span>
                    <h3 class="m-portlet__head-text ">
                        {{__('LỊCH SỬ THAY ĐỔI')}}
                    </h3>
                </div>
            </div>
            <div style="float:right;    margin-top: 18px">
            <span class="m-portlet__head-icon">
{{--                <i class="fas fa-angle-down"></i>--}}
            </span>
            </div>
        </div>
        <div class="m-portlet__body" style="padding: 0px;padding-left: 30px; padding-right: 30px;margin-top: 20px">
            <form class="frmFilter ss--background search-history">
                <div class="row ss--bao-filter">
                    <div class="col-lg-3" style="    flex: 0 0 50%;max-width: 50%;">
                        <div class="form-group m-form__group">
                            <div class="input-group">
                                <select class="form-control select2" id="perfomer" name="perfomer"
                                        onchange="search.choosePerfomer(this)">
                                    <option value="">Người tạo</option>
                                    @foreach($filter as $k=>$v)
                                        <option value="{{$v['staff_id']}}" {{isset($param['perfomer']) && $param['perfomer'] == $v['staff_id']? 'selected':''}}>{{$v['full_name']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3" style="    flex: 0 0 50%;max-width: 50%;">
                        <div class="form-group">
                            <div class="m-input-icon m-input-icon--right">
                                <div class="form-group">
                                    <div class="m-input-icon m-input-icon--right" id="created_at">
                                        <input readonly class="form-control m-input daterange-picker"
                                               style="background-color: #fff"
                                               id="date_apply"
                                               name="date_apply"
                                               autocomplete="off" placeholder="@lang('Ngày áp dụng')">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            <div id="history">
                @if(count($log)!=0)
                    <div class="col-12 mt-3 ml-2 block-list-history pt-5 pb-5">
                        <div class="container">
                            <div class="row">
                                <div class="main-timeline w-100">
                                    @foreach($log as $k => $v)
                                        <div class="timeline">
                                            <div class="timeline-icon"></div>
                                            <div class="timeline-content">
                                                <span class="date">{{$v['day']}}</span>
                                                {{__('Lúc')}} {{$v['hour']}}<br>
                                                - <strong>{{$v['staff_name']}}</strong> {{$v['content']}}<br>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="not_find">
                        <i class="la la-search-plus"> </i>
                        <span>@lang('Chưa có dữ liệu')</span>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <input type="hidden" name="referral_program_id" id="referral_program_id" value="{{$data['referral_program_id']}}">
    </div>

@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/affiliate/add.js?v='.time())}}"
            type="text/javascript"></script>
@stop
