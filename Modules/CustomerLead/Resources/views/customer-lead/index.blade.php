@extends('layout')
@section('title_header')
    <span class="title_header">@lang('QUẢN LÝ KHÁCH HÀNG TIỀM NĂNG')</span>
@stop
@section('content')
    <style>
        .modal-backdrop {
            position: relative !important;
        }

        .form-control-feedback {
            color: red;
        }

        .select2 {
            width: 100% !important;
        }
        .scroll-chat {
            min-height: 50px !important;
            max-height: 400px !important;
            overflow-y: scroll;
            margin-bottom: 20px;
        }
    </style>
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/phu-custom.css?v='.time())}}"> --}}
    <div class="m-portlet m-portlet--head-sm">
        <div class="m-portlet__head">
            <div class="m-portlet__head-caption">
                <div class="m-portlet__head-title">
                    <span class="m-portlet__head-icon">
                        <i class="flaticon-list-1"></i>
                    </span>
                    <h2 class="m-portlet__head-text">
                        @lang("DANH SÁCH KHÁCH HÀNG TIỀM NĂNG")
                    </h2>
                </div>
            </div>
            <div class="m-portlet__head-tools">
                @if(in_array('customer-lead.assign', session('routeList')))
                    <a href="{{route('customer-lead.assign')}}"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-5">
                                    <span>
                                        <span> @lang('PHÂN BỔ')</span>
                                    </span>
                    </a>
                    <a href="javascript:void(0)" onclick="listLead.revoke()"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-5">
                                        <span>
                                            <span> @lang('THU HỒI')</span>
                                        </span>
                    </a>
                @endif
                <form action="{{route('customer-lead.export-all')}}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" id="search_filter" name="search">
                    <input type="hidden" id="allocation_date_filter" name="allocation_date">
                    <input type="hidden" id="created_at_filter" name="created_at">
                    <input type="hidden" id="is_convert_filter" name="is_convert">
                    <input type="hidden" id="tag_id_filter" name="tag_id">
                    <input type="hidden" id="customer_type_filter" name="customer_type">
                    <input type="hidden" id="assign_filter" name="assign">
                    <input type="hidden" id="customer_source_filter" name="customer_source">
                    <input type="hidden" id="sale_id_filter" name="sale_id">
                    <input type="hidden" id="pipeline_code_filter" name="pipeline_code">
                    <input type="hidden" id="journey_code_filter" name="journey_code">

                    <button type="submit" class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                        <span>
                                            <i class="la la-files-o"></i>
                                            <span>
                                                {{__('Xuất dữ liệu')}}
                                            </span>
                                        </span>
                    </button>
                </form>
                @if(in_array('customer-lead.import-excel', session()->get('routeList')))
                    <a href="javascript:void(0)" onclick="index.importExcel()"
                       class="btn btn-info btn-sm m-btn m-btn--icon m-btn--pill color_button m--margin-right-10">
                                        <span>
                                            <i class="la la-files-o"></i>
                                            <span>
                                                {{__('Nhập file')}}
                                            </span>
                                        </span>
                    </a>
                @endif
                @if(in_array('customer-lead.kan-ban-view', session('routeList')))
                    <a href="{{route('customer-lead.kan-ban-view')}}"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc m--margin-right-5">
                                    <span>
                                        <i class="la la-eye"></i>
                                        <span> @lang('KAN BAN VIEW')</span>
                                    </span>
                    </a>
                @endif
                @if(in_array('customer-lead.create',session('routeList')))
                    <a href="{{ route('customer-lead.add') }}"
                       class="btn btn-primary m-btn btn-sm color_button m-btn--icon m-btn--pill btn_add_pc">
                                    <span>
                                        <i class="fa fa-plus-circle"></i>
                                        <span> @lang('THÊM KHÁCH HÀNG TIỀM NĂNG')</span>
                                    </span>
                    </a>
                    <a href="javascript:void(0)" onclick="create.popupCreate(false)"
                       class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                                 color_button btn_add_mobile"
                       style="display: none">
                        <i class="fa fa-plus-circle" style="color: #fff"></i>
                    </a>
                @endif
            </div>
        </div>
        <div class="m-portlet__body">
            <div id="autotable">
                <form class="frmFilter bg">
                    <div class="padding_row">
              
                        <div class="form-group">
                            <div class="row">
                                <div class="col-lg-3 form-group">
                                    <input type="text" class="form-control" name="search"
                                           placeholder="@lang("Nhập tên khách hàng, mã khách hàng hoặc nội dung chăm sóc")">
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input readonly class="form-control m-input daterange-picker" style="background-color: #fff"
                                               id="created_at"
                                               name="created_at" placeholder="@lang('NGÀY TẠO')">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <div class="m-input-icon m-input-icon--right">
                                        <input readonly class="form-control m-input daterange-picker" style="background-color: #fff"
                                               id="allocation_date" name="allocation_date" placeholder="@lang('NGÀY PHÂN BỔ')">
                                        <span class="m-input-icon__icon m-input-icon__icon--right">
                                    <span><i class="la la-calendar"></i></span></span>
                                    </div>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <select class="form-control m-input select" name="is_convert">
                                        <option value="">@lang('Chọn tình trạng chuyển đổi')</option>
                                        <option value="1">@lang('Chuyển đổi thành công')</option>
                                        <option value="0">@lang('Chưa chuyển đổi')</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                @php $i = 0; @endphp
                                @foreach ($FILTER as $name => $item)
                                    @if ($i > 0 && ($i % 4 == 0))
                            </div>
                            <div class="form-group m-form__group row align-items-center">
                                @endif
                                @php $i++; @endphp
                                <div class="col-lg-3 form-group input-group">
                                    @if(isset($item['text']))
                                        <div class="input-group-append">
                                        <span class="input-group-text">
                                            {{ $item['text'] }}
                                        </span>
                                        </div>
                                    @endif
                                    {!! Form::select($name, $item['data'], $item['default'] ?? null, ['class' => 'form-control m-input m_selectpicker']) !!}
                                </div>
                                @endforeach
                                <div class="col-lg-3 form-group">
                                    <select class="form-control m-input journey select" name="journey_code">
                                        <option value="">@lang('Chọn hành trình')</option>
                                    </select>
                                </div>
                                <div class="col-lg-3 form-group">
                                    <button class="btn btn-primary color_button btn-search">
                                        @lang('TÌM KIẾM') <i class="fa fa-search ic-search m--margin-left-5"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
                <div class="table-content m--padding-top-30">
                    {{-- @include('customer-lead::customer-lead.list') --}}
                </div>
            </div>
        </div>
    </div>
    <div id="my-modal"></div>
    <div id="popup-work-edit"></div>
    <div id="vund_popup"></div>
    <div id="zone-popup-show"></div>
    @include('customer-lead::customer-lead.modal-excel')
@endsection
@section("after_style")
    <link rel="stylesheet" href="{{asset('static/backend/css/son.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" href="{{asset('static/backend/css/phu-custom.css')}}">
@stop
@section('after_script')
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/customer-comment.js?v='.time())}}"
        type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/script.js?v='.time())}}"
            type="text/javascript"></script>
    <script src="{{asset('static/backend/js/customer-lead/customer-lead/work.js?v='.time())}}"
            type="text/javascript"></script>
   
    <script src="{{asset('static/backend/js/admin/service/autoNumeric.min.js')}}"></script>
    <script>
        var decimal_number = {{isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0}};
    </script>

    <script>
        let loadingCreate = false;
        listLead._init();
        @if(isset($param['id']))
            listLead.detail({{$param['id']}})
        @endif
        $(".m_selectpicker").selectpicker();
    </script>
    <script type="text-template" id="tpl-phone">
        <div class="form-group m-form__group div_phone_attach">
            <div class="input-group">
                <input type="hidden" class="number_phone" value="{number}">
                <input type="text" class="form-control phone phone_attach" placeholder="@lang('Số điện thoại')">
                <div class="input-group-append">
                    <a class="btn btn-secondary" href="javascript:void(0)" onclick="view.removePhone(this)">
                        <i class="la la-trash"></i>
                    </a>
                </div>
            </div>
            <span class="error_phone_attach_{number} color_red"></span>
        </div>
    </script>
    <script type="text-template" id="tpl-email">
        <div class="form-group m-form__group div_email_attach">
            <div class="input-group">
                <input type="hidden" class="number_email" value="{number}">
                <input type="text" class="form-control email_attach" placeholder="@lang('Email')">
                <div class="input-group-append">
                    <a class="btn btn-secondary" href="javascript:void(0)" onclick="view.removeEmail(this)">
                        <i class="la la-trash"></i>
                    </a>
                </div>
            </div>
            <span class="error_email_attach_{number} color_red"></span>
        </div>
    </script>
    <script type="text-template" id="tpl-fanpage">
        <div class="form-group m-form__group div_fanpage_attach">
            <div class="input-group">
                <input type="hidden" class="number_fanpage" value="{number}">
                <input type="text" class="form-control fanpage_attach" placeholder="@lang('Fan page')">
                <div class="input-group-append">
                    <a class="btn btn-secondary" href="javascript:void(0)" onclick="view.removeFanpage(this)">
                        <i class="la la-trash"></i>
                    </a>
                </div>
            </div>
            <span class="error_fanpage_attach_{number} color_red"></span>
        </div>
    </script>
    <script type="text/template" id="tpl-type">
        <div class="form-group m-form__group">
            <div class="form-group m-form__group">
                <label class="black_title">
                    @lang('Mã số thuế'):<b class="text-danger">*</b>
                </label>
                <input type="text" class="form-control m-input" id="tax_code" name="tax_code"
                       placeholder="@lang('Mã số thuế')">
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    @lang('Người đại diện'):<b class="text-danger">*</b>
                </label>
                <input type="text" class="form-control m-input" id="representative" name="representative"
                       placeholder="@lang('Người đại diện')">
            </div>
            <div class="form-group m-form__group">
                <label class="black_title">
                    @lang('Hot line'):<b class="text-danger">*</b>
                </label>
                <input type="text" class="form-control m-input" id="hotline" name="hotline"
                       placeholder="@lang('Hot line')">
            </div>
        </div>
    </script>
    <script type="text/template" id="tpl-contact">
        <tr class="tr_contact">
            <td>
                <input type="hidden" class="number_contact" value="{number}">
                <input type="text" class="form-control m-input full_name_contact" placeholder="@lang('Họ và tên')">
                <span class="error_full_name_contact_{number} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input phone phone_contact" placeholder="@lang('Số điện thoại')">
                <span class="error_phone_contact_{number} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control email_contact" placeholder="@lang('Email')">
                <span class="error_email_contact_{number} color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input address_contact" placeholder="@lang('Địa chỉ')">
                <span class="error_address_contact_{number} color_red"></span>
            </td>
            <td>
                <a class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill" href="javascript:void(0)" onclick="view.removeContact(this)">
                    <i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
    <script type="text-template" id="tpl-object">
        <tr class="add-object">
            <td style="width:15%;">
                <select class="form-control object_type" style="width:100%;"
                        onchange="detail.changeObjectType(this)">
                    <option></option>
                    <option value="product">@lang('Sản phẩm')</option>
                    <option value="service">@lang('Dịch vụ')</option>
                    <option value="service_card">@lang('Thẻ dịch vụ')</option>
                </select>
                <span class="error_object_type color_red"></span>
            </td>
            <td style="width:25% !important;">
                <select class="form-control object_code" style="width:100%;"
                        onchange="detail.changeObject(this)">
                    <option></option>
                </select>
                <span class="error_object color_red"></span>
            </td>
            <td>
                <input type="text" class="form-control m-input object_price" name="object_price" style="background-color: white;"
                       id="object_price_{stt}" value="" readonly>
                <input type="hidden" class="object_id" name="object_id">
            </td>
            <td style="width: 145px !important;">
                <input type="text" class="form-control m-input btn-ct-input object_quantity" name="object_quantity"
                       id="object_quantity_{stt}" style="text-align: center; height: 30px !important" value="">
            </td>
            <td>
                <input type="text" class="form-control m-input object_discount" name="object_discount"
                       id="object_discount_{stt}" value="">
            </td>
            <td>
                <input type="text" class="form-control m-input object_amount" name="object_amount" style="background-color: white;"
                       id="object_amount_{stt}" value="" readonly>
            </td>

            <td>
                <a href="javascript:void(0)" onclick="detail.removeObject(this)"
                   class="m-portlet__nav-link btn m-btn m-btn--hover-primary m-btn--icon m-btn--icon-only m-btn--pill"
                   title="@lang('Xóa')"><i class="la la-trash"></i>
                </a>
            </td>
        </tr>
    </script>
    <script type="text-template" id="tpl-data-error">
        <input type="hidden" name="full_name[]" value="{full_name}">
        <input type="hidden" name="phone[]" value="{phone}">
        <input type="hidden" name="phone_attack[]" value="{phone_attack}">
        <input type="hidden" name="birthday[]" value="{birthday}">
        <input type="hidden" name="province_name[]" value="{province_name}">
        <input type="hidden" name="district_name[]" value="{district_name}">
        <input type="hidden" name="gender[]" value="{gender}">
        <input type="hidden" name="email[]" value="{email}">
        <input type="hidden" name="email_attach[]" value="{email_attach}">
        <input type="hidden" name="address[]" value="{address}">
        <input type="hidden" name="customer_type[]" value="{customer_type}">
        <input type="hidden" name="pipeline[]" value="{pipeline}">
        <input type="hidden" name="customer_source[]" value="{customer_source}">
        <input type="hidden" name="business_clue[]" value="{business_clue}">
        <input type="hidden" name="fanpage[]" value="{fanpage}">
        <input type="hidden" name="fanpage_attack[]" value="{fanpage_attack}">
        <input type="hidden" name="zalo[]" value="{zalo}">
        <input type="hidden" name="tag[]" value="{tag}">
        <input type="hidden" name="sale_id[]" value="{sale_id}">
        <input type="hidden" name="tax_code[]" value="{tax_code}">
        <input type="hidden" name="representative[]" value="{representative}">
        <input type="hidden" name="hotline[]" value="{hotline}">
        <input type="hidden" name="error[]" value="{error}">
    </script>
@stop
