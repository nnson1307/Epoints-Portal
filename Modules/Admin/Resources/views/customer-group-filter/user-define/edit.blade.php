@extends('layout')
@section("after_style")
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/customize.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('static/backend/css/sinh-custom.css')}}">
@endsection
@section('header')
    @include('components.header',['title'=> __('admin::user-group-notification.create.TITLE')])
@stop
@section('content')
    <style>
        #modal-add-user .table td {
            padding: 0.7rem;
            vertical-align: top;
            border-top: 1px solid #ebedf2;
        }

        #modal-add-user .modal-header {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: start;
            -ms-flex-align: start;
            align-items: flex-start;
            -webkit-box-pack: justify;
            -ms-flex-pack: justify;
            justify-content: space-between;
            padding: 1.2rem;
            border-bottom: 1px solid #ebedf2;
            border-top-left-radius: 0.3rem;
            border-top-right-radius: 0.3rem;
        }
        .table td {
            padding: 0.5rem;
            vertical-align: top;
            border-top: 1px solid #f4f5f8;
        }
    </style>
    <div id="form-adds">
        <div class="m-portlet">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                     <span class="m-portlet__head-icon">
                        <i class="fa fa-plus-circle"></i>
                     </span>
                        <h3 class="m-portlet__head-text">
                            {{__('CHỈNH SỬA NHÓM KHÁCH HÀNG TỰ ĐỊNH NGHĨA')}}
                        </h3>
                    </div>
                </div>
                <div class="m-portlet__head-tools">
                </div>
            </div>
            <div class="m-portlet__body">
                <div class="form-group">
                    <label>{{__('Tên nhóm khách hàng')}}<span class="required"><b class="text-danger">*</b></span></label>
                    <input class="form-control" id="name" autocomplete="off"
                           placeholder="{{__('Nhập tên nhóm khách hàng')}}" name="name" type="text"
                    value="{{$data['name']}}">
                    <span class="form-control-feedback error-name text-danger"></span>
                </div>
                <div class="form-group">
                    <div class="ss-font-size-13rem">
                        {{__('Danh sách khách hàng')}}
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-3 form-group">
                        <input type="text" name="define_full_name_1" id="define_full_name_1" class="form-control"
                               placeholder="{{__('Họ tên khách hàng')}}"
                               value="">
                    </div>
                    <div class="col-lg-3 form-group">
                        <input type="text" name="define_phone_1" id="define_phone_1" class="form-control"
                               placeholder="{{__('Số điện thoại')}}"
                               value="">
                    </div>
                    <div class="col-lg-3 form-group">
                        <select type="text" name="define_is_actived_1" id="define_is_actived_1" style="width: 100%"
                                class="form-control select-2 ss--select-2 ss-width-100pt">
                            <option value="">{{__('Chọn trạng thái')}}</option>
                            <option value="1">{{__('Hoạt động')}}</option>
                            <option value="0">{{__('Tạm ngưng')}}</option>
                        </select>
                    </div>
                    <div class="col-lg-3 form-group">
                        <button onclick="userGroupDefine.searchUserDefine()" class="btn ss--btn-search">
                            {{__('TÌM KIẾM')}}
                            <i class="fa fa-search ss--icon-search"></i>
                        </button>
                    </div>
                </div>
                <div class="form-group row">
                    <div class="col-lg-12 table-list-user-group-define">
                        {{--Danh sách khách hàng--}}
                        @include('admin::customer-group-filter.user-define.include.table-list-user')
                    </div>
                </div>
            </div>
            <div class="m-portlet__foot">
                <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit">
                    <div class="m-form__actions m--align-right">
                        <a href="{{route('admin.customer-group-filter')}}" class="btn btn-metal bold-huy m-btn m-btn--icon m-btn--wide m-btn--md">
						<span>
						<i class="la la-arrow-left"></i>
						<span>{{__('HỦY')}}</span>
						</span>
                        </a>
                        <button type="button" onclick="userGroupDefine.save(0)"
                                class="btn btn-success color_button son-mb m-btn m-btn--icon m-btn--wide m-btn--md btn3 m--margin-left-10">
							<span>
							<i class="la la-check"></i>
							<span>{{__('CẬP NHẬT THÔNG TIN')}}</span>
							</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" value="{{$id}}" id="customer_group_id">
    @include('admin::customer-group-filter.user-define.modal.add-user')
    @include('admin::customer-group-filter.user-define.modal.add-user-2')
    @include('admin::customer-group-filter.user-define.modal.import-excel')
@endsection
@section('after_script')
    <script src="{{asset('static/backend/js/admin/user-group/edit-user-group-define.js?v='.time())}}"
            type="text/javascript"></script>
@stop
