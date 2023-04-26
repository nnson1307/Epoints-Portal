<div id="autotable1">

    <div class="form-group m-form__group">
        <div class="float-left w-50 alert_mb">
            <div class="alert alert-info alert-dismissible fade show alert_banner" style="display: none" role="alert">
               {{__('Số lượng banner đã đạt giới hạn tối đa')}}
            </div>
        </div>
        <div class="float-right add_mb" style="margin-bottom: 35px">
            <a class="btn btn-primary btn-sm color_button m-btn m-btn--icon m-btn--pill btn_add_pc"
               href="javascript:void(0)"
               onclick="banner.modal_add()">
                        <span>
						    <i class="fa fa-plus-circle"></i>
							<span>{{__('THÊM BANNER')}}</span>
                        </span>
            </a>
            <a href="javascript:void(0)"
               onclick="banner.modal_add()"
               class="btn btn-outline-accent m-btn m-btn--icon m-btn--icon-only m-btn--pill
                 color_button btn_add_mobile"
               style="display: none">
                <i class="fa fa-plus-circle" style="color: #fff"></i>
            </a>
        </div>
    </div>
    @include('admin::config-page-appointment.banner-slider.add')
    @include('admin::config-page-appointment.banner-slider.edit')
    <div class="table-content">
        @include('admin::config-page-appointment.banner-slider.list')
    </div><!-- end table-content -->
</div>