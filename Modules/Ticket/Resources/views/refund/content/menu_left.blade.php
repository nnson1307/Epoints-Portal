<div class="col-lg-3 break-work">
    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--mobile m-portlet--body-progress-">
        <div class="m-portlet__body">
            <div class="form-group m-form__group">
                <h3 class="m-portlet__head-text">
                    <i class="fa fa-th-large fz-1_5rem"></i>
                    {{__('Thông tin hoàn ứng')}}
                </h3>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-lg-5 font-weight-bold">
                    @lang('Mã phiếu'):
                </div>
                <div class="col-lg-7">
                    {{ $item->code }}
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-lg-5 font-weight-bold">
                    @lang('Người tạo'):
                </div>
                <div class="col-lg-7">
                    {{ $item->created_by_full_name }}
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-lg-5 font-weight-bold">
                    @lang('Thời gian tạo'):
                </div>
                <div class="col-lg-7">
                    {{ \Carbon\Carbon::parse($item->created_at)->format('d/m/Y H:i') }}
                </div>
            </div>
        </div>
    </div>
    <!--end::Portlet-->

    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--mobile m-portlet--body-progress-">
        <div class="m-portlet__body">
            <div class="form-group m-form__group">
                <h3 class="m-portlet__head-text">
                    <i class="fa fa-user fz-1_5rem"></i>
                    @lang('Thông tin nhân viên')
                </h3>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-lg-5 font-weight-bold">
                    @lang('Nhân viên'):
                </div>
                <div class="col-lg-7">
                    {{ $item->refund_by_full_name }}
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-lg-5 font-weight-bold">
                    @lang('Queue'):
                </div>
                <div class="col-lg-7">
                    {{ $item->refund_by_queue_name }}
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-lg-5 font-weight-bold">
                    @lang('Số điện thoại'):
                </div>
                <div class="col-lg-7">
                    {{ $item->refund_by_phone }}
                </div>
            </div>
            <div class="form-group m-form__group row">
                <div class="col-lg-5 font-weight-bold">
                    @lang('Email'):
                </div>
                <div class="col-lg-7">
                    {{ $item->refund_by_email }}
                </div>
            </div>
        </div>
    </div>
    <!--end::Portlet-->

    <!--begin::Portlet-->
    <div class="m-portlet m-portlet--mobile m-portlet--body-progress-">
        <div class="m-portlet__body">
            <div class="form-group m-form__group">
                <h3 class="m-portlet__head-text">
                    <i class="fa fa-check-circle fz-1_5rem"></i>
                    @lang('Thông tin duyệt')
                </h3>
            </div>
            <div class="form-group m-form__group d-flex pl-3">
                <div class="font-weight-bold">
                    <i class="fa fa-user-circle fz-1_5rem" aria-hidden="true"></i>
                </div>
                <div class="pl-3">
                    <div class="font-weight-bold">
                        {{ $item->approve_by_full_name }}
                    </div>
                    <div>
                        {{ $item->approve_by_department }}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Portlet-->

</div>