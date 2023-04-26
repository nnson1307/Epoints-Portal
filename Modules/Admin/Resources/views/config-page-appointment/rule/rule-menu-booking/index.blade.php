<div class="form-group m-form__group">
    <div class="row">
        <div class="col-lg-3">
            <span>{{__('Tắt')}}/{{__('mở trang')}}</span>
        </div>
        <div class="col-lg-9">
            <span>{{__('Chọn trang khách hàng có thể truy cập khi vào trang đặt lịch')}}</span>
        </div>
    </div>
</div>
<div class="form-group m-form__group">
    <div class="row">
        <div class="col-lg-3">

        </div>
        <div class="col-lg-9">
            <div id="autotable-rule-menu">
                <div class="table-content">
                    @include('admin::config-page-appointment.rule.rule-menu-booking.list-menu')
                </div>
            </div>
        </div>
    </div>
</div>
<div class="form-group m-form__group">
    <div class="row">
        <div class="col-lg-3">
            <span>{{__('Tùy chỉnh trang đặt lịch')}}</span>
        </div>
        <div class="col-lg-9">
            <span>{{__('Tùy chỉnh các bước trong trang đặt lịch')}}</span>
        </div>
    </div>
</div>
<div class="form-group m-form__group">
    <div class="row">
        <div class="col-lg-3">

        </div>
        <div class="col-lg-9">
            <div id="autotable-rule-booking">
                <div class="table-content">
                    @include('admin::config-page-appointment.rule.rule-menu-booking.list-booking')
                </div>
            </div>
        </div>
    </div>
</div>
