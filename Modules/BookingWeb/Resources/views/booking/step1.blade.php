<div class="kt-wizard-v3__content" data-ktwizard-type="step-content"
     data-ktwizard-state="current">
    <div class="kt-heading kt-heading--md"></div>
    <div class="kt-form__section kt-form__section--first">
        <div class="kt-wizard-v3__form">
            <label class="kt-font-bold">{{__('Chọn chi nhánh')}}</label>
            <div class="row">
                <div class="form-group col-lg-4">
                    <select class="form-control" id="province_id" name="province_id" style="width:100%;">
                        <option></option>
                        @foreach($optionProvince as $key=>$value)
                            @foreach($province_default as $v)
                                <option value="{{$key}}">{{$value}}</option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-lg-4">
                    <select class="form-control" id="district_id" name="district_id" style="width:100%;">
                        <option></option>
                    </select>
                </div>
                <div class="form-group col-lg-4">
                    <button type="button" class="btn color-button" onclick="step1.filter()">
                        {{__('TÌM KIẾM')}} <i class="la la-search"></i>
                    </button>
                </div>
            </div>

            <div class="form-group list-branch">
                @include('bookingweb::booking.list.list-step1')
            </div>
        </div>
    </div>
</div>