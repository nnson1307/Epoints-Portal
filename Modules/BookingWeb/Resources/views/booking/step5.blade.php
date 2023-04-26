<div class="kt-wizard-v3__content" data-ktwizard-type="step-content">
    <div class="kt-heading kt-heading--md"></div>
    <div class="kt-form__section kt-form__section--first">
        <div class="kt-wizard-v3__form">
            <label class="kt-font-bold">{{__('Nhập thông tin liên hệ')}}</label>
            <div class="form-group">
                <label>{{__('Họ & tên khách hàng')}} <span class="color-red">*</span></label>
                <input type="text" class="form-control" name="full_name" id="full_name"  placeholder="{{__('Nhập tên khách hàng...')}}">
            </div>
            <div class="row">
                <div class="form-group col-lg-6">
                    <label>{{__('Số điện thoại')}} <span class="color-red">*</span></label>
                    <input type="text" class="form-control" id="phone" name="phone"  placeholder="{{__('Nhập số điện thoại...')}}">
                </div>
                <div class="form-group col-lg-6">
                    <label>{{__('Email')}}</label>
                    <input type="text" class="form-control" id="email" name="email"  placeholder="{{__('Nhập email...')}}">
                </div>
            </div>
            <div class="form-group">
                <label>{{__('Ghi chú')}}</label>
                <textarea class="form-control" id="description" name="description" rows="3"></textarea>
            </div>
        </div>
    </div>
</div>