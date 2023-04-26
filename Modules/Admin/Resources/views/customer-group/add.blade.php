<div class="modal-content">
    <div class="modal-header">
        <h4 class="modal-title ss--title m--font-bold">
            <i class="fa fa-plus-circle ss--icon-title m--margin-right-5"></i>
            {{__('THÊM NHÓM KHÁCH HÀNG')}}</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
    </div>
    <div class="modal-body">
        <div class="form-group">
            <label>
                {{__('Tên nhóm khách hàng')}}:<b class="text-danger">*</b>
            </label>
            <div class="{{ $errors->has('group_name') ? ' has-danger' : '' }}">
                <input type="text" id="group-name-add" name="group_name" class="form-control m-input"
                       placeholder="{{__('Nhập tên nhóm khách hàng')}}">
                <span class="error-group-name"></span>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <div class="m-portlet__foot m-portlet__no-border m-portlet__foot--fit ss--width--100">
            <div class="m-form__actions m--align-right">
                <button data-dismiss="modal"
                        class="ss--btn-mobiles m--margin-bottom-5 btn btn-metal m-btn m-btn--icon m-btn--wide m-btn--md ss--btn">
                    <span class="ss--text-btn-mobi">
                    <i class="la la-arrow-left"></i>
                    <span>{{__('HỦY')}}</span>
                    </span>
                </button>
                <button type="button" onclick="customerGroup.addClose()"
                        class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                    <span class="ss--text-btn-mobi">
                    <i class="la la-check"></i>
                    <span>{{__('LƯU THÔNG TIN')}}</span>
                    </span>
                </button>
                <button type="button" onclick="customerGroup.add()"
                        class="ss--btn-mobiles m--margin-bottom-5 btn ss--button-cms-piospa m-btn m-btn--icon m-btn--wide m-btn--md m--margin-left-10">
                <span class="ss--text-btn-mobi">
                <i class="fa fa-plus-circle m--margin-left-10"></i>
                <span>{{__('LƯU & TẠO MỚI')}}</span>
                </span>
                </button>
            </div>
        </div>
    </div>
</div>


{{--<div class="modal-content modal-content-tb">--}}
{{--<div class="modal-header-sss">--}}
{{--<h5 class="modal-title" id="exampleModalLabel">--}}

{{--</h5>--}}
{{--<button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}

{{--<i class="la la-times-circle-o"></i>--}}

{{--</button>--}}
{{--</div>--}}
{{--<div class="modal-body-sss" style="text-align: center;">--}}
{{--<div class="form-group">--}}
{{--<i class="la la-check" style=""></i>--}}
{{--<img src="{{asset('uploads/default/check-success.png')}}" alt="" class="m--margin-bottom-20">--}}
{{--<br>--}}
{{--<span style="font-weight:500;color: #474747">--}}
{{--BẠN ĐÃ {{__('CẬP NHẬT THÔNG TIN')}} SẢN PHẨM THÀNH CÔNG--}}
{{--</span>--}}
{{--</div>--}}
{{--<div class="ss--background-img-tb">s</div>--}}
{{--</div>--}}
{{--<div class="modal-footer" style=" background-image: {{asset('')}};">--}}
{{--<button type="button" class="btn btn-secondary" data-dismiss="modal">--}}
{{--Close--}}
{{--</button>--}}
{{--<button type="button" class="btn btn-primary">--}}
{{--Save changes--}}
{{--</button>--}}
{{--</div>--}}
{{--</div>--}}