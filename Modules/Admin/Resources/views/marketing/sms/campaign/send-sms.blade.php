@extends('layout')
@section('content')
    <style>
        .modal-lg {
            max-width: 75%;
        }
    </style>
    <div class="row">
        <div class="col-md-4">
            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
												<span class="m-portlet__head-icon m--hide">
													<i class="la la-gear"></i>
												</span>
                            <h3 class="m-portlet__head-text">
                                {{__('Thông tin chiến dịch')}}
                            </h3>
                        </div>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="form-group m-form__group">
                        <label for="">{{__('Chiến dịch')}}:</label>
                        <select name="smsCampaign" id="smsCampaign" class="form-control">
                            <option value="">{{__('Chọn chiến dịch')}}</option>
                            @foreach($smsCampaign as $key=>$value)
                                <option value="{{$key}}">{{{$value}}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5">
                            <label for="">{{__('Loại tin nhắn')}}:</label>
                        </div>
                        <div class="col-lg-7">
                            <input type="text" id="type" disabled="disabled" class="form-control" value="Chăm sóc khách hàng">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5">
                            <label for="">{{__('Loại đầu số')}}:</label>
                        </div>
                        <div class="col-lg-7">
                            <input id="brandname_id" type="text" disabled="disabled" class="form-control" value="">
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5">
                            <label for="">{{__('Thời gian gửi')}}:</label>
                        </div>
                        <div class="col-lg-7">
                            <input id="value" type="text" disabled="disabled" class="form-control" value="">
                        </div>
                    </div>
                    {{--<div class="form-group m-form__group row">--}}
                        {{--<div class="col-lg-5">--}}
                            {{--<label for="">{{__('Nhắc lịch trước')}}:</label>--}}
                        {{--</div>--}}
                        {{--<div class="col-lg-7">--}}
                            {{--<input type="text" disabled="disabled" class="form-control" value="">--}}
                        {{--</div>--}}
                    {{--</div>--}}
                    <div class="form-group m-form__group row">
                        <div class="col-lg-5">
                            <label for="">{{__('Trạng thái')}}:</label>
                        </div>
                        <div class="col-lg-7 status">
                            {{--<span class="m-badge m-badge--warning m-badge--wide "></span>--}}
                        </div>
                    </div>
                    <div class="form-group m-form__group row">
                        <div class="col-lg-6  pull-left">
                            <button class="btn btn-primary col-lg-12">{{__('Gửi tin')}}</button>
                        </div>
                        <div class="col-lg-6 pull-right">
                            <button class="btn btn-block col-lg-12">{{__('Tạm ngưng')}}</button>
                        </div>
                    </div>
                </div>
            </div>

            <!--end::Portlet-->
        </div>
        <div class="col-md-8">
            <!--begin::Portlet-->
            <div class="m-portlet m-portlet--tab">
                <div class="m-portlet__head">
                    <div class="m-portlet__head-caption">
                        <div class="m-portlet__head-title">
												<span class="m-portlet__head-icon m--hide">
													<i class="la la-gear"></i>
												</span>
                            <h3 class="m-portlet__head-text">
                                {{__('Danh sách khách hàng')}}
                            </h3>
                        </div>
                    </div>
                    <div class="m-portlet__head-tools">
                        <button type="button" class="btn btn-sm btn-primary m-btn m-btn--icon m-btn--pill"
                                data-toggle="modal"
                                data-target="#m_modal_4">
                            <i class="fa flaticon-plus m--margin-right-5"></i>{{__('Import file')}}
                        </button>
                        <button type="button" class="btn btn-sm btn-primary m-btn m-btn--icon m-btn--pill"
                                data-toggle="modal"
                                data-target="#m_modal_4">
                            <i class="fa flaticon-plus m--margin-right-5"></i>{{__('Thêm khách hàng')}}
                        </button>
                    </div>
                </div>
                <div class="m-portlet__body">
                    <div class="table-content">
                        @include('admin::marketing.sms.campaign.list-customer')
                    </div>
                </div>
            </div>

            <!--end::Portlet-->
        </div>
    </div>
    <div class="modal fade show" id="m_modal_4" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            @include('admin::marketing.sms.campaign.add-customer')
        </div>
    </div>
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/marketing/sms/campaign/send-sms.js')}}"
            type="text/javascript"></script>
    <script type="text/template" id="customer-list-tpl">
        <tr>
            <td>{stt}</td>
            <td>
                {name}
                <input type="hidden" name="customer_id" value="{customer_id}">
            </td>
            <td>
                {phone}
            </td>
            <td>{birthday}</td>
            <td>{gender}</td>
            <td>{branch_name}</td>
            <td>
                <label class="m-checkbox m-checkbox--air">
                    <input class="check" name="check" type="checkbox">
                    <span></span>
                </label>
            </td>
        </tr>
    </script>
@endsection